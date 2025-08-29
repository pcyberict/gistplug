# Overview

This is a PHP-based live streaming platform called "PCYBER TV" that allows users to view sports streams and participate in live chat. The platform features a public interface for viewing streams, an admin dashboard for managing content, and a real-time chat system. The application supports both YouTube and Facebook video embeds, with category-based organization and pagination for better user experience.

**Status**: Successfully imported and configured in Replit environment. Pure PHP implementation with modern admin dashboard styling running on port 5000. All routes, templates, and database functionality working correctly. Deployment configured for autoscale target.

# User Preferences

Preferred communication style: Simple, everyday language.

# System Architecture

## Frontend Architecture
The application uses server-side templating with PHP includes and Bootstrap 5 for responsive design. Template structure follows a consistent header/footer include pattern with proper variable passing. It implements a dual-theme system (light/dark mode) with CSS custom properties for consistent styling across all pages. The interface includes:

- Public stream viewing pages with embedded video players (YouTube/Facebook)
- Admin dashboard with stream management capabilities  
- User authentication forms with modern glass-morphism design
- Real-time chat interface with profanity filtering and rate limiting
- Responsive navigation with sticky header
- Category-based stream filtering and pagination
- AJAX-powered "Load More" functionality for streams

## Backend Architecture
Built with PHP and a modular structure separating concerns:

- **Models**: PDO-based User and Database classes for authentication
- **Authentication**: PHP password_hash/password_verify for secure password handling
- **Data Storage**: Hybrid approach using SQLite for users and JSON files for streams/chat
- **Rate Limiting**: Session-based rate limiting for chat messages
- **Content Filtering**: Basic profanity filter with configurable word list

## Data Storage Solutions
The application uses a mixed data storage approach:

- **SQLite Database**: Stores user accounts with encrypted passwords
- **JSON Files**: Stores streams metadata (title, platform, video_id, category) and chat messages
- **File-based Configuration**: Uses environment variables for database connections with fallback to hardcoded values

## Authentication and Authorization
Implements a simple two-tier authentication system:

- **Admin Access**: Hardcoded credentials for administrative functions
- **User Registration**: SQLAlchemy User model with bcrypt password hashing
- **Session Management**: Flask sessions for maintaining login state
- **Route Protection**: Decorator-based access control for admin routes

## External Dependencies

- **Bootstrap 5**: Frontend UI framework for responsive design
- **Font Awesome**: Icon library for enhanced user interface
- **YouTube API**: Embedded video players for YouTube streams
- **Facebook Videos**: Direct iframe embedding for Facebook video content
- **PHP**: Native PDO for database operations, password hashing functions
- **Database Options**: SQLite for user management with JSON files for streams/chat data

## Replit Environment Setup

The application has been successfully converted to pure PHP:
- **Primary Backend**: PHP application with index.php as the main router
- **Development Server**: PHP built-in server running on 0.0.0.0:5000
- **Database**: SQLite database in instance/users.db directory managed by PHP PDO
- **Styling**: Bootstrap 5 with custom CSS embedded in templates for glass-morphism design
- **Deployment**: Configured for autoscale deployment target with PHP server

## Admin Access
- **Username**: admin
- **Password**: Pcyber50@
- **Admin Panel**: /admin-login