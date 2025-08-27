<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Rich Flow Capital — Simulasi Performa (3D Enhanced)</title>
  <meta name="description" content="Simulasi performa Rich Flow Capital — EV-based, lot-aware, reproducible. Visual 3D dan playback."/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r152/three.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
  <style>
    :root{
      --bg:#071026; --card:#0b1624; --muted:#b9c9da; --accent:#ffd166; --accent2:#06b6d4; --danger:#ef4444; --success:#22c55e;
    }
    html,body{height:100%; margin:0; font-family:'Inter',system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif; background:linear-gradient(180deg,#03060a 0%,var(--bg) 100%); color:#e6eef6}
    .glass{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border:1px solid rgba(255,255,255,0.04); backdrop-filter: blur(6px)}
    .card{background:var(--card); border:1px solid rgba(255,255,255,0.04); border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.35)}
    .btn{transition:transform .14s ease, box-shadow .14s ease}
    .btn:hover{transform:translateY(-3px)}
    .small{font-size:13px; color:var(--muted)}
    .kpi{border-radius:8px; padding:10px; border:1px solid rgba(255,255,255,0.03)}
    .section-pad{padding-top:40px;padding-bottom:40px}
    .muted{color:var(--muted)}
    .accent-txt{background:linear-gradient(90deg,var(--accent2),var(--accent)); -webkit-background-clip:text; color:transparent; text-shadow:0 1px 6px rgba(6,182,212,0.25)}
    .shadow-soft{box-shadow:0 8px 20px rgba(2,6,23,0.6)}
    table td, table th{vertical-align:middle}
    @media (max-width:768px){ .hide-sm{display:none} }
    .label { font-size:12px; color:var(--muted) }
    .input { padding:8px; border-radius:6px; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.03); color:inherit }
    /* 3D background canvas */
    #bg3d{position:fixed; inset:0; z-index:-1; background:radial-gradient(1200px 800px at 20% 10%, rgba(6,182,212,0.04), transparent 60%), radial-gradient(800px 600px at 80% 30%, rgba(255,209,102,0.03), transparent 60%)}
    #bgDim{position:fixed; inset:0; z-index:0; background:rgba(0,0,0,0.28); pointer-events:none}
  </style>
</head>
<body>
  <canvas id="bg3d"></canvas>
  <div id="bgDim"></div>
  <header class="glass sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[var(--accent2)] to-[var(--accent)] grid place-items-center text-black font-bold">RF</div>
        <div><div class="font-bold">Rich Flow Capital</div><div class="small">Private Investment Management</div></div>
      </div>
      <nav class="hidden md:flex items-center gap-6 small">
        <a href="#performance" class="hover:text-white">Performa</a>
        <a href="#investor" class="hover:text-white">Investor Tools</a>
        <a href="/" class="hover:text-white">Home</a>
        <a href="#apply" class="text-black bg-gradient-to-r from-[var(--accent2)] to-[var(--accent)] px-3 py-2 rounded-lg font-semibold">Request Access</a>
      </nav>
      <div class="flex items-center gap-2">
        <button id="toggle3D" class="hidden md:inline-flex btn glass px-3 py-2 rounded-lg small">3D: On</button>
        <button id="openRisk" class="md:hidden btn small glass px-3 py-2 rounded-lg">Info</button>
      </div>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-4 section-pad">
    <!-- HERO + Controls -->
    <section class="grid md:grid-cols-2 gap-6 items-center mb-6">
      <div>
        <div class="small glass inline-flex items-center gap-2 px-3 py-1 rounded-full">Private • Invitation Only</div>
        <h1 class="mt-4 text-3xl md:text-4xl font-extrabold leading-tight">Pertumbuhan Modal Berdasarkan <span class="accent-txt">Kinerja Nyata</span></h1>
        <p class="muted mt-3">Simulasi EV-based, lot-aware. Tidak menjanjikan hasil. Sesuaikan parameter dengan skala modal nyata (lihat kalkulator).</p>
        <div class="mt-4 flex flex-wrap gap-3">
          <button id="runDefault" class="btn bg-gradient-to-r from-[var(--accent2)] to-[var(--accent)] text-black px-4 py-2 rounded-lg font-semibold">Jalankan Simulasi Default</button>
          <button id="showDetails" class="btn glass px-4 py-2 rounded-lg">Tampilkan Rincian</button>
        </div>
        <div class="mt-4 small muted">
          <strong>Catatan:</strong> Masukkan <em>Risk Harian</em> sebagai persen (mis. <code>0.8</code> = 0.8% per hari). <br>
          Lot Risk Unit = seberapa besar 1 lot mengkonsumsi unit pool (default=1).
        </div>
      </div>

      <div class="card p-4">
        <h4 class="font-semibold">Kontrol Simulasi</h4>
        <div class="mt-3 grid grid-cols-2 gap-3">
          <div>
            <div class="label">Win Rate (%)</div>
            <input id="uiWin" class="input" type="number" min="20" max="85" value="55" />
          </div>
          <div>
            <div class="label">Risk-Reward (R)</div>
            <input id="uiRR" class="input" type="number" min="1" max="6" step="0.5" value="2" />
          </div>
          <div>
            <div class="label">Risk Harian (%)</div>
            <input id="uiDailyRisk" class="input" type="number" min="0.05" max="5" step="0.05" value="0.8" />
          </div>
          <div>
            <div class="label">Trades / hari</div>
            <input id="uiTrades" class="input" type="number" min="1" max="20" value="4" />
          </div>
          <div>
            <div class="label">Durasi (bulan)</div>
            <select id="uiMonths" class="input"><option>1</option><option selected>3</option><option>6</option><option>12</option></select>
          </div>
          <div>
            <div class="label">Lot Risk Unit (unit)</div>
            <input id="uiLotUnitInput" class="input" type="number" min="0.01" step="0.01" value="1" />
          </div>
          <div>
            <div class="label">Management Fee (%)</div>
            <input id="uiFee" class="input" type="number" min="0" max="50" value="20" />
          </div>
          <div>
            <div class="label">Seed (reproducible)</div>
            <input id="uiSeed" class="input" type="number" value="202509" />
          </div>
        </div>
        <div class="mt-3 small muted">
          Set parameter lalu tekan <strong>Jalankan Simulasi Default</strong>. Grafik & tabel akan terisi otomatis.
        </div>
      </div>
    </section>

    <!-- PERFORMANCE -->
    <section id="performance" class="mb-6">
      <div class="grid md:grid-cols-3 gap-6">
        <div class="card p-4">
          <h4 class="font-semibold">KPI Ringkas</h4>
          <div class="mt-3 grid gap-2">
            <div class="kpi"><div class="small muted">Gross P/L</div><div id="gross" class="font-bold">-</div></div>
            <div class="kpi"><div class="small muted">Fee</div><div id="fee" class="font-bold">-</div></div>
            <div class="kpi"><div class="small muted">Net to Investor</div><div id="net" class="font-bold">-</div></div>
            <div class="kpi"><div class="small muted">Final Pool (unit)</div><div id="final" class="font-bold">-</div></div>
          </div>
          <div class="mt-4 small muted">
            <label class="block">Tampilan</label>
            <div class="mt-2 flex gap-2">
              <button id="btnDaily" class="btn glass px-3 py-2 rounded">Harian</button>
              <button id="btnWeekly" class="btn glass px-3 py-2 rounded">Mingguan</button>
              <select id="perfMonths" class="ml-auto input">
                <option value="1">1 bulan</option>
                <option value="3" selected>3 bulan</option>
                <option value="6">6 bulan</option>
              </select>
            </div>
          </div>
        </div>

        <div class="md:col-span-2 card p-4">
          <div class="flex items-center justify-between mb-3 small muted">
            <div class="flex items-center gap-2">
              <button id="playBtn" class="btn glass px-3 py-1 rounded">▶ Play</button>
              <select id="speedSel" class="input">
                <option value="1">1x</option>
                <option value="2">2x</option>
                <option value="4">4x</option>
              </select>
            </div>
            <div class="flex-1 mx-3"><input id="playRange" type="range" min="0" max="0" value="0" class="w-full"></div>
            <div id="playLabel">0/0</div>
          </div>
          <div style="height:280px">
            <canvas id="equityChart" style="width:100%;height:100%"></canvas>
          </div>
          <div class="mt-4 grid md:grid-cols-3 gap-3">
            <div class="kpi text-center"><div class="small muted">Total Lots</div><div id="lots" class="font-bold">-</div></div>
            <div class="kpi text-center"><div class="small muted">Win Rate (observed)</div><div id="wr" class="font-bold">-</div></div>
            <div class="kpi text-center"><div class="small muted">Avg Return / Lot</div><div id="art" class="font-bold">-</div></div>
          </div>
        </div>
      </div>

      <div class="card p-4 mt-4">
        <h4 class="font-semibold">Ringkasan Periode</h4>
        <div class="mt-3 overflow-x-auto">
          <table class="w-full text-left small muted">
            <thead class="border-b border-white/6">
              <tr>
                <th class="p-2">Periode</th>
                <th class="p-2">Gross P/L</th>
                <th class="p-2">Fee</th>
                <th class="p-2">Net to Investor</th>
                <th class="p-2">#Lots</th>
              </tr>
            </thead>
            <tbody id="periodTable"></tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- Investor tools simplified -->
    <section id="investor" class="mb-6 grid md:grid-cols-2 gap-6">
      <div class="card p-4">
        <h4 class="font-semibold">Kalkulator — Skala ke Modal Riil</h4>
        <p class="muted mt-2">Mapping contoh: 1 unit = Rp100.000 (ubah sesuai kebutuhan).</p>
        <div class="mt-3 grid grid-cols-2 gap-2">
          <div><div class="label">Modal Investor (IDR)</div><input id="realCapital" class="input" type="number" value="200000000" /></div>
          <div><div class="label">Unit -> IDR (1 unit = ? IDR)</div><input id="unitToIDR" class="input" type="number" value="100000" /></div>
          <div><div class="label">Share Investor (%)</div><input id="sharePct" class="input" type="number" value="30" /></div>
          <div></div>
        </div>
        <div class="mt-3"><button id="mapCalc" class="btn bg-gradient-to-r from-[var(--accent2)] to-[var(--accent)] text-black px-3 py-2 rounded">Hitung Estimasi</button></div>
        <div id="mapOut" class="mt-3 hidden">
          <div class="kpi"><div class="small muted">Estimasi Gross (IDR)</div><div id="mappedGross" class="font-bold">-</div></div>
          <div class="kpi mt-2"><div class="small muted">Estimasi ke Investor (IDR)</div><div id="mappedInvestor" class="font-bold">-</div></div>
          <div class="kpi mt-2"><div class="small muted">Modal Akhir (IDR)</div><div id="mappedFinal" class="font-bold">-</div></div>
        </div>
      </div>

      <div class="card p-4">
        <h4 class="font-semibold">Unduh Dokumen (Mock)</h4>
        <div class="mt-3 flex flex-col gap-2">
          <button id="dlProfile" class="btn glass px-3 py-2 rounded">Download Company Profile (TXT)</button>
          <button id="dlRAB" class="btn glass px-3 py-2 rounded">Download Lampiran RAB (CSV)</button>
        </div>
      </div>
    </section>

    <footer class="mt-6 small muted text-center">© <span id="year"></span> Rich Flow Capital — Simulator Prototype. Bukan jaminan hasil.</footer>
  </main>

  <!-- Risk modal -->
  <div id="riskModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60">
    <div class="card p-4 max-w-2xl rounded">
      <div class="flex justify-between items-center">
        <div><strong>Pengungkapan Risiko</strong><div class="small muted">Baca sebelum menggunakan</div></div>
        <button id="closeRisk" class="btn glass px-3 py-1 rounded">Tutup</button>
      </div>
      <div class="mt-3 small muted">Investasi berisiko tinggi; simulasi hanya ilustrasi. Tidak ada jaminan imbal hasil tetap.</div>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', ()=>{
    // safe DOM selectors
    const $ = id => document.getElementById(id);
    if($('year')) $('year').textContent = new Date().getFullYear();

    // utility RNG
    function mulberry32(seed) {
      return function() {
        var t = seed += 0x6D2B79F5;
        t = Math.imul(t ^ t >>> 15, t | 1);
        t ^= t + Math.imul(t ^ t >>> 7, t | 61);
        return ((t ^ t >>> 14) >>> 0) / 4294967296;
      }
    }

    // Chart init with gradient fills
    const chartCanvas = $('equityChart');
    const ctx = chartCanvas.getContext('2d');
    function makeGradient(ctx, color, toTransparent=true){
      const g = ctx.createLinearGradient(0, 0, 0, chartCanvas.height);
      g.addColorStop(0, color + (toTransparent? 'cc' : 'ff'));
      g.addColorStop(1, color + '00');
      return g;
    }
    const eqChart = new Chart(ctx, {
      type: 'line',
      data: { labels: [], datasets: [
        { label: 'Equity (unit)', data: [], borderColor: '#06b6d4', backgroundColor: makeGradient(ctx,'#06b6d4'), tension: 0.28, borderWidth: 2, fill:true },
        { label: 'Cumulative Gross', data: [], borderColor: '#8b5cf6', backgroundColor: makeGradient(ctx,'#8b5cf6'), tension: 0.28, borderWidth: 2, fill:true }
      ]},
      options: {
        plugins: { legend:{ labels:{ color:'#cfe8f5' } } },
        scales: { x:{ ticks:{ color:'#cfe8f5' }, grid:{ color:'rgba(255,255,255,0.06)' } }, y:{ ticks:{ color:'#cfe8f5' }, grid:{ color:'rgba(255,255,255,0.06)' } } },
        animation: { duration: 300 },
        maintainAspectRatio: false,
      }
    });

    // 3D Background (Three.js starfield)
    let threeEnabled = !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) && window.innerWidth >= 768;
    const bgCanvas = $('bg3d');
    const renderer = new THREE.WebGLRenderer({ canvas: bgCanvas, antialias: true, alpha: true });
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 42;
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(window.innerWidth, window.innerHeight);

    const starGeo = new THREE.BufferGeometry();
    const starCount = 800;
    const positions = new Float32Array(starCount * 3);
    for(let i=0;i<starCount;i++){
      const r = 120 * Math.cbrt(Math.random());
      const theta = Math.random() * Math.PI * 2;
      const phi = Math.acos(2*Math.random()-1);
      positions[i*3] = r * Math.sin(phi) * Math.cos(theta);
      positions[i*3+1] = r * Math.sin(phi) * Math.sin(theta);
      positions[i*3+2] = r * Math.cos(phi);
    }
    starGeo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    const starMat = new THREE.PointsMaterial({ color: 0x86eafc, size: 0.6, sizeAttenuation: true, transparent: true, opacity: 0.6 });
    const stars = new THREE.Points(starGeo, starMat);
    scene.add(stars);

    let mouseX = 0, mouseY = 0;
    window.addEventListener('mousemove', (e)=>{
      mouseX = (e.clientX / window.innerWidth) * 2 - 1;
      mouseY = (e.clientY / window.innerHeight) * 2 - 1;
    });

    function animate3D(){
      if(!threeEnabled) return;
      stars.rotation.x += 0.0007;
      stars.rotation.y += 0.0009;
      camera.position.x += (mouseX * 5 - camera.position.x) * 0.02;
      camera.position.y += (-mouseY * 5 - camera.position.y) * 0.02;
      camera.lookAt(0,0,0);
      renderer.render(scene, camera);
      requestAnimationFrame(animate3D);
    }
    if(threeEnabled){ animate3D(); } else { bgCanvas.style.display = 'none'; if(document.getElementById('toggle3D')) document.getElementById('toggle3D').textContent = '3D: Off'; }

    function onResize(){
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    }
    window.addEventListener('resize', onResize);

    if($('toggle3D')) $('toggle3D').addEventListener('click', ()=>{
      threeEnabled = !threeEnabled;
      bgCanvas.style.display = threeEnabled ? 'block' : 'none';
      $('toggle3D').textContent = '3D: ' + (threeEnabled ? 'On' : 'Off');
      if(threeEnabled) animate3D();
    });

    // Tilt effects
    try {
      VanillaTilt.init(document.querySelectorAll('.card, .kpi'), { max: 6, speed: 400, glare: true, 'max-glare': 0.15, perspective: 800, scale: 1.01 });
    } catch(e) { /* no-op */ }

    // format helper
    const fmt = (n)=> (n>=0?'+':'') + new Intl.NumberFormat('id-ID',{maximumFractionDigits:2}).format(n);

    // core simulation (lot-aware)
    function runSimulation({months=3, winRate=0.55, rr=2, dailyRiskPct=0.8, tradesPerDay=4, feePct=20, lotRiskUnit=1, seed=202509}) {
      winRate = isFinite(winRate) ? Math.max(0.2, Math.min(0.85, winRate)) : 0.55;
      rr = isFinite(rr) ? Math.max(1, Math.min(6, rr)) : 2;
      dailyRiskPct = isFinite(dailyRiskPct) ? Math.max(0.05, Math.min(5, dailyRiskPct)) : 0.8;
      tradesPerDay = isFinite(tradesPerDay) ? Math.max(1, Math.min(20, Math.round(tradesPerDay))) : 4;
      lotRiskUnit = isFinite(lotRiskUnit) ? Math.max(0.01, lotRiskUnit) : 1;
      feePct = isFinite(feePct) ? Math.max(0, Math.min(100, feePct)) : 20;

      const days = Math.max(1, Math.round(months * 30));
      const rand = mulberry32(Number(seed) || 202509);

      let pool = 1000;
      const daily = [];
      let totalLots = 0, totalWins = 0, totalTrades = 0;

      for(let d=0; d<days; d++){
        const riskPerDay = pool * (dailyRiskPct/100);
        const effectiveRiskPerDay = Math.max(0.0001, riskPerDay);
        const riskPerTrade = effectiveRiskPerDay / tradesPerDay;
        let dayGross = 0;
        let dayLots = 0;

        for(let t=0; t<tradesPerDay; t++){
          const lots = Math.max(1, Math.round(riskPerTrade / lotRiskUnit));
          totalLots += lots;
          dayLots += lots;
          totalTrades++;
          const isWin = rand() < winRate;
          if(isWin) totalWins++;

          const basePL = isWin ? (lots * lotRiskUnit * rr) : -(lots * lotRiskUnit);
          const jitter = 1 + (rand()*0.2 - 0.1);
          let tradePL = basePL * jitter;

          if(dayGross + tradePL < -effectiveRiskPerDay) {
            tradePL = -effectiveRiskPerDay - dayGross;
          }
          dayGross += tradePL;

          if(dayGross <= -effectiveRiskPerDay + 1e-9) break;
        }

        const dayFee = dayGross > 0 ? dayGross * (feePct/100) : 0;
        const dayNet = dayGross - dayFee;
        pool += dayNet;

        daily.push({ day: d+1, gross:+dayGross, fee:+dayFee, net:+dayNet, pool:+pool, lots: dayLots });
      }

      const weekly = [];
      for(let i=0;i<daily.length;i+=7){
        const chunk = daily.slice(i, i+7);
        const gross = chunk.reduce((s,x)=>s+x.gross,0);
        const fee = chunk.reduce((s,x)=>s+x.fee,0);
        const net = chunk.reduce((s,x)=>s+x.net,0);
        const lots = chunk.reduce((s,x)=>s+x.lots,0);
        weekly.push({ startDay: chunk[0]?.day || i+1, gross:+gross, fee:+fee, net:+net, lots });
      }

      const grossTotal = daily.reduce((s,x)=>s+x.gross,0);
      const feeTotal = daily.reduce((s,x)=>s+x.fee,0);
      const netTotal = daily.reduce((s,x)=>s+x.net,0);
      const winRateObserved = totalTrades ? (totalWins/totalTrades) : 0;

      return { daily, weekly, finalPool: pool, totalLots, grossTotal, feeTotal, netTotal, winRateObserved };
    }

    // render helpers
    function renderPeriodTable(rows, isWeekly=false){
      const tbody = $('periodTable');
      if(!tbody) return;
      tbody.innerHTML = '';
      rows.forEach((r, idx)=>{
        const label = isWeekly ? `Minggu ${idx+1} (hari ${r.startDay})` : `Hari ${r.day}`;
        const tr = document.createElement('tr');
        tr.innerHTML = `<td class="p-2">${label}</td>
                        <td class="p-2">${fmt(r.gross)}</td>
                        <td class="p-2">${fmt(r.fee)}</td>
                        <td class="p-2">${fmt(r.net)}</td>
                        <td class="p-2">${r.lots || 0}</td>`;
        tbody.appendChild(tr);
      });
    }

    // update dashboard
    let currentSim = null;
    let currentMode = 'weekly';
    function updateDashboard(params){
      try {
        currentSim = runSimulation(params);
        renderView(currentMode);

        if($('gross')) $('gross').textContent = fmt(currentSim.grossTotal);
        if($('fee')) $('fee').textContent = fmt(currentSim.feeTotal);
        if($('net')) $('net').textContent = fmt(currentSim.netTotal);
        if($('final')) $('final').textContent = currentSim.finalPool.toFixed(2);
        if($('lots')) $('lots').textContent = currentSim.totalLots;
        if($('wr')) $('wr').textContent = (currentSim.winRateObserved*100).toFixed(1) + '%';
        const avgReturnPerLot = currentSim.totalLots? (currentSim.grossTotal / currentSim.totalLots) : 0;
        if($('art')) $('art').textContent = (avgReturnPerLot>=0?'+':'') + avgReturnPerLot.toFixed(4);

        setupPlayback();
      } catch (e) {
        console.error('updateDashboard error', e);
        alert('Terjadi error saat menjalankan simulasi — cek console (F12) untuk detail.');
      }
    }

    function renderView(mode='weekly'){
      currentMode = mode;
      if(!currentSim) return;
      if(mode === 'daily'){
        const labels = currentSim.daily.map(d=> 'D'+d.day);
        const equity = currentSim.daily.map(d=> d.pool);
        const cumGross = currentSim.daily.map((_,i)=> currentSim.daily.slice(0,i+1).reduce((s,x)=>s+x.gross,0));
        eqChart.data.labels = labels;
        eqChart.data.datasets[0].data = equity;
        eqChart.data.datasets[1].data = cumGross;
        eqChart.update();
        renderPeriodTable(currentSim.daily, false);
      } else {
        const labels = currentSim.weekly.map((w,i)=> 'W'+(i+1));
        let accum = 1000;
        const equity = [];
        const cumGross = [];
        let cg = 0;
        currentSim.weekly.forEach(w=>{
          accum += w.net;
          cg += w.gross;
          equity.push(+accum.toFixed(2));
          cumGross.push(+cg.toFixed(2));
        });
        eqChart.data.labels = labels;
        eqChart.data.datasets[0].data = equity;
        eqChart.data.datasets[1].data = cumGross;
        eqChart.update();
        renderPeriodTable(currentSim.weekly, true);
      }
      updatePlayUIMax();
    }

    // UI binding
    if($('btnDaily')) $('btnDaily').addEventListener('click', ()=> renderView('daily'));
    if($('btnWeekly')) $('btnWeekly').addEventListener('click', ()=> renderView('weekly'));

    if($('runDefault')) {
      $('runDefault').addEventListener('click', ()=>{
        const months = Number($('uiMonths').value) || 3;
        const winRate = (Number($('uiWin').value) || 55) / 100;
        const rr = Number($('uiRR').value) || 2;
        const dailyRisk = Number($('uiDailyRisk').value) || 0.8;
        const tradesPerDay = Number($('uiTrades').value) || 4;
        const fee = Number($('uiFee').value) || 20;
        const lotUnit = Number($('uiLotUnitInput').value) || 1;
        const seed = Number($('uiSeed').value) || 202509;
        updateDashboard({ months, winRate, rr, dailyRiskPct: dailyRisk, tradesPerDay, feePct: fee, lotRiskUnit: lotUnit, seed });
      });
    }

    if($('showDetails')) $('showDetails').addEventListener('click', ()=>{
      if(!currentSim){ alert('Jalankan simulasi dulu.'); return; }
      alert('Simulasi selesai. Lihat grafik & tabel. Mapping ke IDR ada di panel Kalkulator.');
    });

    if($('openRisk')) $('openRisk').addEventListener('click', ()=> { if($('riskModal')) { $('riskModal').classList.remove('hidden'); $('riskModal').classList.add('flex'); } });
    if($('closeRisk')) $('closeRisk').addEventListener('click', ()=> { if($('riskModal')) $('riskModal').classList.add('hidden'); });

    if($('mapCalc')) $('mapCalc').addEventListener('click', ()=>{
      if(!currentSim){ alert('Jalankan simulasi dulu.'); return; }
      const capital = Number($('realCapital').value) || 0;
      const unitToIDR = Number($('unitToIDR').value) || 100000;
      const sharePct = (Number($('sharePct').value) || 30) / 100;
      const grossIDR = currentSim.grossTotal * unitToIDR;
      const netIDR = currentSim.netTotal * unitToIDR;
      const investorNet = netIDR * sharePct;
      const finalCapital = capital + investorNet;
      if($('mappedGross')) $('mappedGross').textContent = new Intl.NumberFormat('id-ID').format(grossIDR);
      if($('mappedInvestor')) $('mappedInvestor').textContent = new Intl.NumberFormat('id-ID').format(investorNet);
      if($('mappedFinal')) $('mappedFinal').textContent = new Intl.NumberFormat('id-ID').format(finalCapital);
      if($('mapOut')) $('mapOut').classList.remove('hidden');
    });

    if($('dlProfile')) $('dlProfile').addEventListener('click', ()=>{
      const txt = `Rich Flow Capital — Company Profile (Mock)\n\nModel: Private Investment Management\nDisclaimer: bukan tawaran publik.`;
      const b = new Blob([txt],{type:'text/plain'}); const u = URL.createObjectURL(b);
      const a = document.createElement('a'); a.href=u; a.download='RichFlow_Profile.txt'; a.click(); URL.revokeObjectURL(u);
    });
    if($('dlRAB')) $('dlRAB').addEventListener('click', ()=>{
      const csv = 'Item,EstCost\nModal Trading,50000000\nDP Ruko,50000000\nDekorasi Office,67000000\nTotal,167000000';
      const b = new Blob([csv],{type:'text/csv'}); const u = URL.createObjectURL(b);
      const a = document.createElement('a'); a.href=u; a.download='RAB_Termin1.csv'; a.click(); URL.revokeObjectURL(u);
    });

    // Playback controls (time as 4th dimension)
    let playTimer = null;
    function updatePlayUIMax(){
      const max = currentMode === 'daily' ? (currentSim?.daily?.length || 0) : (currentSim?.weekly?.length || 0);
      if($('playRange')){ $('playRange').max = String(Math.max(0, max-1)); $('playRange').value = String(Math.max(0, Math.min(Number($('playRange').value||0), max-1))); }
      if($('playLabel')) $('playLabel').textContent = `${(Number($('playRange')?.value)||0)+1}/${max}`;
    }

    function stepTo(idx){
      if(!currentSim) return;
      if(currentMode==='daily'){
        const labels = currentSim.daily.slice(0, idx+1).map(d=> 'D'+d.day);
        const equity = currentSim.daily.slice(0, idx+1).map(d=> d.pool);
        const cumGross = currentSim.daily.slice(0, idx+1).map((_,i)=> currentSim.daily.slice(0,i+1).reduce((s,x)=>s+x.gross,0));
        eqChart.data.labels = labels;
        eqChart.data.datasets[0].data = equity;
        eqChart.data.datasets[1].data = cumGross;
        eqChart.update('none');
        renderPeriodTable(currentSim.daily.slice(0, idx+1), false);
      } else {
        const labels = currentSim.weekly.slice(0, idx+1).map((w,i)=> 'W'+(i+1));
        let accum = 1000; let cg = 0;
        const equity = []; const cumGross = [];
        currentSim.weekly.slice(0, idx+1).forEach(w=>{ accum += w.net; cg += w.gross; equity.push(+accum.toFixed(2)); cumGross.push(+cg.toFixed(2)); });
        eqChart.data.labels = labels;
        eqChart.data.datasets[0].data = equity;
        eqChart.data.datasets[1].data = cumGross;
        eqChart.update('none');
        renderPeriodTable(currentSim.weekly.slice(0, idx+1), true);
      }
      if($('playRange')) $('playRange').value = String(idx);
      if($('playLabel')) {
        const max = Number($('playRange').max)||0; $('playLabel').textContent = `${idx+1}/${max+1}`;
      }
    }

    function setupPlayback(){
      updatePlayUIMax();
      stepTo( (Number($('playRange')?.value)||0) );
    }

    if($('playRange')) $('playRange').addEventListener('input', (e)=>{ stepTo(Number(e.target.value||0)); });

    if($('playBtn')) $('playBtn').addEventListener('click', ()=>{
      if(!currentSim) return;
      const speed = Number($('speedSel')?.value||1);
      const max = Number($('playRange').max)||0;
      if(playTimer){
        clearInterval(playTimer); playTimer = null; $('playBtn').textContent = '▶ Play';
        return;
      }
      $('playBtn').textContent = '⏸ Pause';
      playTimer = setInterval(()=>{
        let idx = Number($('playRange').value||0);
        if(idx >= max){ clearInterval(playTimer); playTimer = null; $('playBtn').textContent = '▶ Play'; return; }
        stepTo(idx+1);
      }, Math.max(120, 400 / speed));
    });

    // initial run to populate UI
    try { if($('runDefault')) $('runDefault').click(); } catch(e){ console.error('Initial run error', e); }
  });
  </script>
</body>
</html>

