# Overview

This is a Flask-based live streaming platform called "PCYBER TV" that allows users to view sports streams and participate in live chat. The platform features a public interface for viewing streams, an admin dashboard for managing content, and a real-time chat system. The application supports both YouTube and Facebook video embeds, with category-based organization and pagination for better user experience.

# User Preferences

Preferred communication style: Simple, everyday language.

# System Architecture

## Frontend Architecture
The application uses server-side templating with Jinja2 and Bootstrap 5 for responsive design. It implements a dual-theme system (light/dark mode) with CSS custom properties for consistent styling across all pages. The interface includes:

- Public stream viewing pages with embedded video players
- Admin dashboard with stream management capabilities  
- User authentication forms with modern glass-morphism design
- Real-time chat interface with profanity filtering
- Responsive navigation with sticky header

## Backend Architecture
Built on Flask with a modular structure separating concerns:

- **Models**: SQLAlchemy ORM with User model for authentication
- **Authentication**: Flask-Bcrypt for password hashing with hardcoded admin credentials
- **Data Storage**: Hybrid approach using SQLite for users and JSON files for streams/chat
- **Rate Limiting**: In-memory rate limiting using defaultdict for chat messages
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
- **Flask Ecosystem**: SQLAlchemy for ORM, Bcrypt for password hashing
- **Database Options**: Configured for both SQLite (development) and MySQL (production via environment variables)