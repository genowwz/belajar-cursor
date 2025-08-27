# Complaint Management System

A complete Laravel-based complaint management system with clean, minimalistic design.

## Features

- **User Authentication**: Registration, login, and logout
- **Complaint Management**: Submit, view, and track complaints
- **Admin Dashboard**: Manage and update complaint statuses
- **Notification System**: Real-time notifications for status updates
- **Responsive Design**: Clean, minimalistic UI that works on all devices

## Database Structure

### Users Table
- id, name, email, password, is_admin, timestamps

### Complaints Table
- id, user_id, title, description, status (pending/resolved), timestamps

### Notifications Table
- id, user_id, message, status (read/unread), created_at

## Installation & Setup

1. **Dependencies**: PHP 8.4, SQLite (configured), Composer installed
2. **Database**: Already migrated with sample data
3. **Server**: Running on http://localhost:8000

## Test Accounts

### Admin Account
- Email: `admin@example.com`
- Password: `password`
- Access: Can manage all complaints and update statuses

### Regular User Account
- Email: `user@example.com`
- Password: `password`
- Access: Can submit and manage own complaints

## Key Routes

- `/` - Welcome page
- `/login` - User login
- `/register` - User registration
- `/dashboard` - User dashboard
- `/complaints` - User complaints list
- `/complaints/create` - Submit new complaint
- `/notifications` - User notifications
- `/admin/complaints` - Admin complaint management (admin only)

## How to Use

### For Regular Users:
1. Register or login with test account
2. Submit complaints through the "Submit Complaint" form
3. Track complaint status on dashboard
4. Receive notifications when status changes

### For Admins:
1. Login with admin account
2. Access admin complaint management
3. Update complaint statuses (pending/resolved)
4. Status changes automatically notify users

## Technical Implementation

- **Backend**: Laravel 12 with MVC architecture
- **Frontend**: Blade templates with inline CSS
- **Database**: SQLite (easily changeable to MySQL)
- **Authentication**: Laravel's built-in authentication
- **Authorization**: Custom admin middleware
- **Styling**: Clean, responsive CSS without external dependencies

## Security Features

- CSRF protection on all forms
- User authorization (users can only access own complaints)
- Admin middleware for protected routes
- Password hashing
- Input validation

## File Structure

```
app/
├── Http/Controllers/
│   ├── AuthController.php
│   ├── ComplaintController.php
│   └── NotificationController.php
├── Models/
│   ├── User.php
│   ├── Complaint.php
│   └── Notification.php
└── Http/Middleware/
    └── AdminMiddleware.php

resources/views/
├── layouts/app.blade.php
├── welcome.blade.php
├── dashboard.blade.php
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── complaints/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── show.blade.php
├── admin/complaints/
│   └── index.blade.php
└── notifications/
    └── index.blade.php
```

## Customization

The system is built with modularity in mind:
- Easy to add new complaint fields
- Simple to modify status options
- Straightforward to change styling
- Easy to add email notifications
- Simple to integrate with external services

---

**Server Status**: ✅ Running on http://localhost:8000

Access the application at: http://localhost:8000
