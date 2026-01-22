# Subspace

A modern social media platform built with PHP and MySQL that enables users to create posts, interact with content through likes and comments, join spaces (communities), and connect with other users.

## Description

Subspace is a full-featured social media application that provides users with a familiar social networking experience. Users can share their thoughts, images, and links with the community, engage with other users' content, and participate in themed spaces. The platform includes comprehensive user management, moderation tools, and an administrative dashboard.

## Features

### User Features
- User registration and authentication
- User profiles with customizable avatars and bio
- Create posts with text, images, and links
- Like and comment on posts
- Follow other users
- Join and create themed spaces (communities)
- Search functionality for posts and users
- Privacy controls and user agreements

### Administrative Features
- Admin dashboard with statistics
- User management and moderation
- Content moderation (hide/remove posts)
- User blocking capabilities
- Report management system

## Prerequisites

Before you begin, ensure you have the following installed:
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache or Nginx web server
- Laragon, XAMPP, WAMP, or similar local development environment (recommended)

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/KevinvdWeert/Subspace.git
cd Subspace
```

### 2. Database Setup

Create a new MySQL database:

```sql
CREATE DATABASE subspace CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Import the database schema:

```bash
mysql -u root -p subspace < database/base.sql
```

For a database with sample data, use:

```bash
mysql -u root -p subspace < database/full.sql
```

### 3. Configuration

Copy the configuration file and adjust settings for your environment:

1. Open `includes/config.php`
2. Update the database credentials:
   - `host`: Your database host (default: 127.0.0.1)
   - `name`: Database name (default: subspace)
   - `user`: Database username (default: root)
   - `pass`: Database password
3. Update the `base_path` if running in a subfolder (e.g., /Subspace)

Example configuration:

```php
return [
    'app' => [
        'base_path' => '/Subspace', // Or empty string for domain root
    ],
    'db' => [
        'host' => '127.0.0.1',
        'name' => 'subspace',
        'user' => 'root',
        'pass' => 'your_password',
        'charset' => 'utf8mb4',
    ],
];
```

### 4. Web Server Setup

#### For Laragon:
1. Place the project in your Laragon www directory
2. Access via http://localhost/Subspace

#### For XAMPP/WAMP:
1. Place the project in htdocs directory
2. Access via http://localhost/Subspace

## Usage

### First Time Access

1. Navigate to the registration page: http://localhost/Subspace/register.php
2. Create a new account
3. Log in with your credentials

### Test Accounts

If you imported the full database with sample data, test accounts are available with the password `password123` for all users. Example accounts include:
- Regular users: `alexj`, `mariah_lee`, `coding_wizard`, `travel_bug`, `music_lover`, `bookworm`, etc.
- Admin accounts: `admin_jane`, `admin_mark`

### Main Features

- **Home Feed**: View posts from all users or your followed users
- **Create Post**: Share text, images, or links with the community
- **Spaces**: Browse or create themed communities
- **Profile**: View and edit your profile, see your posts
- **Search**: Find users and posts
- **Admin Panel**: Access at /admin (requires admin role)

## Project Structure

```
Subspace/
├── admin/              # Administrative dashboard and tools
├── assets/             # CSS, JavaScript, and images
├── components/         # Reusable page components (terms, privacy, etc.)
├── database/           # SQL schema and sample data files
├── docs/               # Project documentation and planning
├── includes/           # Core PHP functionality
│   ├── auth.php        # Authentication functions
│   ├── config.php      # Configuration settings
│   ├── db.php          # Database connection
│   ├── helpers.php     # Utility functions
│   ├── header.php      # Common header
│   └── footer.php      # Common footer
├── index.php           # Home feed
├── login.php           # Login page
├── register.php        # Registration page
├── profile.php         # User profile pages
├── post.php            # Individual post view
├── space.php           # Space pages
├── search.php          # Search functionality
└── logout.php          # Logout handler
```

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+ with InnoDB engine
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: Bootstrap (responsive design)
- **Database Design**: MySQL Workbench for ERD

## Security Features

- Password hashing with PHP's built-in functions
- Prepared statements for SQL injection prevention
- CSRF token protection
- Session management
- Input validation and sanitization
- Role-based access control

## Development Team

This project was developed as part of a team collaboration:
- **Luka**: Project setup, structure, documentation, and image upload functionality
- **Kijan**: Front-end development (CSS/styling and UI)
- **Kevin**: Back-end development, file structures, and database design

## License

This project is created for educational purposes.

## Contributing

This is an educational project. If you'd like to contribute:
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## Support

For questions or issues, please create an issue in the GitHub repository.