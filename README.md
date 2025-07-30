# Event Management System (EMS)
![EMS_Dashboard](https://github.com/user-attachments/assets/964d149c-91dc-4fca-a598-27649297c99e)

## Introduction
It have developed with raw PHP with MySQL that allows users to manage events, register attendees, and download participants lists.
The project uses PHP for backend development, MySQL as the database, and AJAX to enhance the user experience.

## Features
- User Authentication (Login/Registration).
- Event Creation and Management.
- Attendee Registration.
- Real-time event capacity updates.
- View participant lists for each event.
- Implemented Search Functionality
- Downloadable participant list in CSV format.
- Events are paginated, sortable & filterable.

## Tech Stack
- Backend: PHP (Pure PHP No Framework)
- Frontend: HTML, CSS, JavaScript (AJAX)
- UI responsive with Bootstrap
- Database: MySQL

## Prerequisites
Before you begin, ensure you have met the following requirements:
- PHP 7.4 or higher
- MySQL Database
- Web server such as Apache or Nginx
- Composer (optional for dependency management)

## Installation
- Step 1: Clone the Repository
```
git clone https://github.com/amdadussobhan/Event-Management-System.git
```
- Step 2: Set Up the Database
Create a MySQL database for the project.

Create a users Table like this
```
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
```

Create a events Table like this
```
CREATE TABLE IF NOT EXISTS `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `max_capacity` int NOT NULL,
  `description` text NOT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
);
```

Create a attendees Table like this
```
CREATE TABLE IF NOT EXISTS `attendees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_id` int NOT NULL,
  `user_id` int NOT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `user_id` (`user_id`)
);
```

- Step 3: Configure Database Connection
Edit the database connection details in the auth/db_connect.php file.
```
$host = "your_host";
$dbname = "your_dbname";
$username = "your_db_username";
$password = "your_db_password";
```

## Usage
### Event Management
- Admins can create events by navigating to the Event Creation page.
- Events have title, description, date, capacity, and cover photo.
  
### Event Registration
- Users can register for events via the event details page.
- Registration includes name, email, and password verification.

### Download Participant List
- Admins can download a CSV list of participants for any event.

## Contributing
If you'd like to contribute to this project, please follow these guidelines:

### Fork the repository.
- Create a new branch (git checkout -b feature/your-feature).
- Make your changes and commit them (git commit -m 'Add some feature').
- Push the branch (git push origin feature/your-feature).
- Create a Pull Request.
