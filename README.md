# Flyhub ERP CRM System

A comprehensive Customer Relationship Management (CRM) and Enterprise Resource Planning (ERP) system built with PHP and MySQL.

## Features

### Current Features
- **Dashboard** - Overview of key metrics and recent activity
- **Contact Management** - Add, edit, delete, and search contacts
- **Company Management** - Manage company information and relationships
- **User Authentication** - Secure login system
- **Responsive Design** - Works on desktop and mobile devices
- **Search & Filtering** - Advanced search capabilities
- **Bulk Operations** - Delete multiple records at once
- **Inline Editing** - Quick edit functionality

### Planned Features
- **Deal/Opportunity Management** - Track sales pipeline
- **Task Management** - Assign and track tasks
- **Product Catalog** - Manage products and services
- **Reporting & Analytics** - Generate business reports
- **Email Integration** - Send emails directly from the system
- **Document Management** - Store and organize files
- **Calendar Integration** - Schedule meetings and appointments

## Technology Stack

- **Backend**: PHP 8.0+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Dependencies**: Composer for autoloading

## Installation

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx) or PHP built-in server
- Composer (optional, for dependency management)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd flyhub-erp
   ```

2. **Database Setup**
   - Create a MySQL database named `flyhub_erp`
   - Import the database schema:
     ```bash
     mysql -u root -p flyhub_erp < database/schema.sql
     ```

3. **Configure Database Connection**
   - Update database credentials in `src/config/database.php`
   - Default settings:
     - Host: localhost
     - Username: root
     - Password: (empty)
     - Database: flyhub_erp

4. **Start the Application**
   ```bash
   # Using PHP built-in server
   php -S localhost:8000
   
   # Or using npm script
   npm run dev
   ```

5. **Access the Application**
   - Open your browser and go to `http://localhost:8000`
   - Default login credentials:
     - Username: `admin`
     - Password: `admin123`

## Project Structure

```
flyhub-erp/
├── database/
│   └── schema.sql              # Database schema and sample data
├── public/
│   └── assets/
│       ├── css/
│       │   └── index.css       # Main stylesheet
│       └── js/
│           └── index.js        # Main JavaScript file
├── src/
│   ├── config/
│   │   └── database.php        # Database configuration
│   ├── controllers/
│   │   ├── ContactController.php
│   │   ├── CompanyController.php
│   │   └── DashboardController.php
│   ├── models/
│   │   ├── BaseModel.php       # Base model with common functionality
│   │   ├── Contact.php
│   │   └── Company.php
│   ├── middleware/
│   │   └── auth.php            # Authentication middleware
│   └── views/
│       ├── global/             # Shared view components
│       ├── contacts.php
│       ├── companies.php
│       ├── dashboard.php
│       └── ...
├── vendor/                     # Composer dependencies
├── index.php                   # Main application entry point
├── login.php                   # Login page
├── register.php                # Registration page
└── composer.json               # Composer configuration
```

## Usage

### Managing Contacts
1. Navigate to the Contacts section
2. Use the "Add New Contact" button to create contacts
3. Search and filter contacts using the search bar
4. Edit contacts by clicking the "Edit" button
5. Delete single or multiple contacts using bulk operations

### Managing Companies
1. Go to the Companies section
2. Add new companies with detailed information
3. View company profiles with associated contacts
4. Use inline editing for quick updates
5. Search companies by name, location, or industry

### Dashboard
- View key metrics and statistics
- See recent contacts and companies
- Quick access to main sections

## API Endpoints

The application uses a simple routing system with the following actions:

### Contacts
- `?action=list` - List all contacts
- `?action=create` - Create new contact
- `?action=edit&id=X` - Edit contact
- `?action=update` - Update contact
- `?action=delete&id=X` - Delete contact

### Companies
- `?action=listCompanies` - List all companies
- `?action=createCompany` - Create new company
- `?action=viewCompany&id=X` - View company profile
- `?action=editCompany&id=X` - Edit company
- `?action=updateCompany` - Update company
- `?action=deleteCompany&id=X` - Delete company

## Security Features

- Password hashing using PHP's `password_hash()`
- SQL injection prevention using prepared statements
- Session-based authentication
- Input validation and sanitization
- CSRF protection (planned)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please contact the development team or create an issue in the repository.