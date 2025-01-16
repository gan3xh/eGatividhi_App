# README.md

## Project Title: eGatividhi

### Demo Video

[Attach demo video link here]

### Image Results Example

Result Image 1

Result Image 2

### Description

eGatividhi is a web application designed for monitoring and documenting construction progress through image uploads and comparisons. This application utilizes AI technology to analyze images uploaded at various stages of a construction project, providing detailed insights into the progress over time.

### Features

- **Image Upload**: Users can upload images captured at different stages of construction.
- **Progress Detection**: The system analyzes uploaded images to detect changes and calculate construction progress.
- **User-Friendly Interface**: A responsive web interface built with HTML, CSS, and JavaScript for easy navigation and interaction.
- **Database Integration**: Utilizes MySQL for storing project details and uploaded images.
- **Dynamic Feedback**: Provides real-time feedback on image uploads, including validation messages.

### Technologies Used

- **Frontend**: HTML, CSS (Bootstrap), JavaScript (React)
- **Backend**: PHP
- **Database**: MySQL
- **Version Control**: Git

### Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```bash
   cd eGatividhi
   ```
3. Set up the database:
   - Create a MySQL database named `eGatividhi`.
   - Import the necessary SQL schema from the provided SQL files (if available).
4. Update database connection settings in `1.php`:
   ```php
   $host = "localhost";
   $username = "your_username";
   $password = "your_password";
   $dbname = "eGatividhi";
   ```
5. Start a local server (e.g., XAMPP, MAMP) and place the project folder in the server's root directory.

### Usage

- Navigate to the homepage (`index.html`) to view ongoing projects.
- Select a project to view details and upload images.
- Use the upload form to submit images for analysis.
- View results including similarity scores and construction progress estimates.

### File Structure

```
eGatividhi/
│
├── .gitignore
├── .htaccess
├── 1.php
├── 2.php
├── about.html
├── app.py
├── assets/
│   ├── css/
│   │   └── main.css
│   └── js/
│       └── main.js
├── contact.html
├── index.html
├── info.php
├── login.html
├── login.php
├── Ministry.php
├── Project_Manager.php
├── project.php
├── README.md
├── Register.html
├── register.php
├── services.html
└── templates/
    └── index.html
```
