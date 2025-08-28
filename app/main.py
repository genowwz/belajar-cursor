from fastapi import FastAPI, File, UploadFile, Form, HTTPException
from fastapi.responses import StreamingResponse, HTMLResponse, JSONResponse
import asyncio
import httpx
import pandas as pd
from bs4 import BeautifulSoup
import io
import re
from typing import List, Optional


APP_TITLE = "Adobe Stock Keyword Volume Scraper"


app = FastAPI(title=APP_TITLE)


USER_AGENT = (
    "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) "
    "Chrome/124.0 Safari/537.36"
)


async def fetch_search_html(client: httpx.AsyncClient, keyword: str, locale: str = "en_US") -> str:
    # Use the public search page and parse total results from title/meta
    url = "https://stock.adobe.com/search"
    params = {"k": keyword}
    headers = {"User-Agent": USER_AGENT, "Accept-Language": "en-US,en;q=0.9"}
    resp = await client.get(url, params=params, headers=headers, timeout=30.0)
    resp.raise_for_status()
    return resp.text


def parse_total_from_html(html: str) -> Optional[int]:
    # Try from <title> e.g. "Cat Images – Browse 9,527,183 Stock Photos..."
    try:
        soup = BeautifulSoup(html, "html.parser")
        title = soup.title.get_text(strip=True) if soup.title else ""
        # Look for patterns like "Browse 9,527,183" or "9,527,183 Results"
        patterns = [
            r"Browse\s+([0-9,]+)",
            r"([0-9,]+)\s+Results",
            r"of\s+([0-9,]+)\s+results",
        ]
        for pattern in patterns:
            m = re.search(pattern, title, flags=re.IGNORECASE)
            if m:
                return int(m.group(1).replace(",", ""))
        # Fallback: OpenGraph title
        og_title = soup.find("meta", attrs={"property": "og:title"})
        if og_title and og_title.get("content"):
            txt = og_title["content"]
            for pattern in patterns:
                m = re.search(pattern, txt, flags=re.IGNORECASE)
                if m:
                    return int(m.group(1).replace(",", ""))
    except Exception:
        return None
    return None


async def get_keyword_count(client: httpx.AsyncClient, keyword: str) -> Optional[int]:
    html = await fetch_search_html(client, keyword)
    return parse_total_from_html(html)


def dataframe_from_excel(file_bytes: bytes) -> pd.DataFrame:
    with io.BytesIO(file_bytes) as bio:
        df = pd.read_excel(bio, engine="openpyxl")
    if df.empty:
        raise ValueError("Uploaded file has no rows")
    return df


def to_csv_response(df: pd.DataFrame, filename: str) -> StreamingResponse:
    csv_bytes = df.to_csv(index=False).encode("utf-8")
    return StreamingResponse(
        io.BytesIO(csv_bytes),
        media_type="text/csv",
        headers={"Content-Disposition": f"attachment; filename={filename}"},
    )


@app.get("/", response_class=HTMLResponse)
async def index() -> str:
    return (
        """
        <html>
            <head><title>Adobe Stock Keyword Volume Scraper</title></head>
            <body>
            <h2>Adobe Stock Keyword Volume Scraper</h2>
            <form action="/scrape" method="post" enctype="multipart/form-data">
                <label>Excel file (.xlsx): <input name="file" type="file" accept=".xlsx" required /></label>
                <br/>
                <label>Column name with keywords: <input name="column" type="text" placeholder="keywords" required /></label>
                <br/>
                <label>Max concurrent (default 5): <input name="concurrency" type="number" min="1" max="20" value="5" /></label>
                <br/>
                <button type="submit">Scrape</button>
            </form>
            </body>
        </html>
        """
    )


@app.post("/scrape")
async def scrape(
    file: UploadFile = File(...),
    column: str = Form(...),
    concurrency: int = Form(5),
):
    if not file.filename.lower().endswith(".xlsx"):
        raise HTTPException(status_code=400, detail="Please upload a .xlsx file")
    file_bytes = await file.read()
    try:
        df = dataframe_from_excel(file_bytes)
    except Exception as exc:
        raise HTTPException(status_code=400, detail=f"Failed to read Excel: {exc}")

    if column not in df.columns:
        raise HTTPException(status_code=400, detail=f"Column '{column}' not found in Excel. Available: {list(df.columns)}")

    keywords_series = df[column].astype(str).fillna("").str.strip()
    keywords: List[str] = [kw for kw in keywords_series.tolist() if kw]
    if not keywords:
        raise HTTPException(status_code=400, detail="No keywords found in the selected column")

    semaphore = asyncio.Semaphore(max(1, min(concurrency, 20)))

    async with httpx.AsyncClient(http2=True, follow_redirects=True, headers={"User-Agent": USER_AGENT}) as client:
        async def bound_task(kw: str):
            async with semaphore:
                await asyncio.sleep(0.3)
                try:
                    count = await get_keyword_count(client, kw)
                except Exception:
                    count = None
                return {"keyword": kw, "count": count}

        results = await asyncio.gather(*(bound_task(kw) for kw in keywords))

    out_df = pd.DataFrame(results)
    return to_csv_response(out_df, filename="adobe_stock_counts.csv")


@app.get("/health")
async def health() -> JSONResponse:
    return JSONResponse({"status": "ok"})

