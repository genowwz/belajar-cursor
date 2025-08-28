# ComplaintHub - Complaint Management System

A modern, minimalist complaint management website built with Laravel (PHP) and Tailwind CSS, featuring a clean user interface and comprehensive complaint tracking system.

## Features

### 🏠 Homepage
- Clean, minimalist design with hero section
- Easy navigation to submit complaints
- Feature highlights and call-to-action sections
- Responsive design for all devices

### 📝 Complaint Submission
- **Public Access**: Non-logged-in users can submit complaints
- **User Accounts**: Logged-in users can track their complaints
- **File Attachments**: Support for images, PDFs, and documents
- **Categories**: Service, Product, Delivery, Billing, Technical, Other
- **Priority Levels**: Low, Medium, High
- **Detailed Forms**: Comprehensive complaint description fields

### 👤 User System
- **User Titles**: Automatic progression based on complaint count
  - Newcomer: 1-3 complaints
  - Active Contributor: 4-10 complaints
  - Veteran Complainer: 10+ complaints
- **Complaint Dashboard**: Track status, priority, and progress
- **Profile Management**: View statistics and complaint history

### 🔧 Admin Dashboard
- **Comprehensive Overview**: Total complaints, pending, in progress, resolved
- **Complaint Management**: Update status, add notes, assign priorities
- **Advanced Filtering**: Search by status, priority, category, or text
- **Bulk Operations**: Quick status updates and note management

### 🎨 Design Features
- **Tailwind CSS**: Modern, utility-first CSS framework
- **Responsive Design**: Mobile-first approach
- **Color Scheme**: Neutral colors with blue accents
- **Typography**: Inter font family for excellent readability
- **Smooth Animations**: Hover effects and transitions

## Technology Stack

- **Backend**: Laravel 12 (PHP)
- **Database**: SQLite (configurable for MySQL/PostgreSQL)
- **Frontend**: Tailwind CSS, Blade templates
- **Authentication**: Laravel built-in auth system
- **File Storage**: Local storage with public links

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (or MySQL/PostgreSQL)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd complaint-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Storage setup**
   ```bash
   php artisan storage:link
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

### Default Users

The system comes with pre-configured users:

- **Admin User**
  - Email: `admin@complaints.com`
  - Password: `password`
  - Role: Administrator

- **Test User**
  - Email: `test@example.com`
  - Password: `password`
  - Role: Regular User

## Usage

### For Users

1. **Submit a Complaint**
   - Visit the homepage
   - Click "Submit a Complaint"
   - Fill out the form with details
   - Attach relevant files (optional)
   - Submit and receive confirmation

2. **Track Complaints** (Logged-in users)
   - Create an account or log in
   - View dashboard with complaint statistics
   - Track status updates and admin notes
   - Edit or delete complaints as needed

3. **Earn Titles**
   - Submit complaints to progress through user levels
   - View current title on dashboard and navigation

### For Administrators

1. **Access Admin Panel**
   - Log in with admin credentials
   - Navigate to Admin Dashboard

2. **Manage Complaints**
   - View all complaints with filtering options
   - Update complaint status (Pending → In Progress → Resolved)
   - Add admin notes and internal comments
   - Monitor high-priority issues

3. **System Overview**
   - Dashboard statistics and metrics
   - Recent complaint activity
   - Quick action buttons for common tasks

## Database Structure

### Core Tables
- **users**: User accounts with titles and complaint counts
- **complaints**: Main complaint data with status tracking
- **attachments**: File uploads linked to complaints
- **statuses**: Complaint status definitions
- **titles**: User title progression system

### Key Relationships
- Users can have multiple complaints
- Complaints can have multiple attachments
- Status updates trigger user notifications
- Title progression is automatic based on complaint count

## Customization

### Styling
- Modify `resources/css/app.css` for custom CSS
- Update `tailwind.config.js` for theme customization
- Edit Blade templates in `resources/views/`

### Functionality
- Add new complaint categories in migrations
- Modify user title thresholds in seeders
- Extend admin features in `AdminController`
- Add notification systems for status updates

## Security Features

- CSRF protection on all forms
- Input validation and sanitization
- File upload restrictions and validation
- Role-based access control
- Secure password hashing

## Performance Considerations

- Database indexing on frequently queried fields
- Pagination for large complaint lists
- Efficient file storage and retrieval
- Optimized database queries with Eloquent relationships

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions:
- Email: support@complainthub.com
- Documentation: [Project Wiki]
- Issues: [GitHub Issues]

---

**ComplaintHub** - Making complaint management simple, efficient, and transparent.
