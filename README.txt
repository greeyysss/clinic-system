# Clinic System

A web-based clinic management system with patient registration via QR code and admin dashboard for monitoring blood pressure records.

## Features

- QR Code-based patient registration
- Blood pressure recording and categorization
- Admin dashboard with monthly summaries
- Secure admin login system
- Thank you page confirmation for patients

## System Requirements

- WAMP Server (Windows, Apache, MySQL, PHP)
- Web browser with internet access
- Network connectivity between devices for QR code scanning

## Directory Structure

```
├── admin/
│   ├── dashboard.php    # Admin dashboard with monthly summaries
│   └── login.php        # Admin authentication
├── assets/
│   └── README.txt       # This documentation file
├── database/
│   └── clinic_system.sql    # Database schema and initial data
├── includes/
│   └── db.php          # Database connection configuration
├── patient/
│   ├── register.php    # Main patient registration form
│   ├── test_register.php    # Backup registration form
│   └── thankyou.php    # Success confirmation page
├── admin_login.php     # Admin login entry point
└── index.php          # Main landing page with QR code
```

## Setup Instructions

1. Install WAMP Server on your Windows machine
2. Copy all files to the www directory (typically c:\wamp64\www\clinic system)
3. Import the database schema:
   - Open phpMyAdmin
   - Create a new database named 'clinic_system'
   - Import the SQL file from database/clinic_system.sql

## Usage

### For Patients
1. Scan the QR code displayed at the clinic
2. Fill out the registration form with blood pressure readings
3. Submit the form to record the data
4. View the confirmation page

### For Administrators
1. Access the admin login page
2. Login with your admin credentials
3. View the dashboard for:
   - Monthly summaries
   - Blood pressure categories
   - Patient registration statistics

## Blood Pressure Categories

The system automatically categorizes blood pressure readings as:
- High
- Normal
- Low

Based on standard medical guidelines.

## Security

- Admin access is protected by login authentication
- Database credentials are centralized in includes/db.php
- Form submissions include basic validation

## Support

For technical support or questions, please contact your system administrator.

## Last Updated

November 10, 2025
