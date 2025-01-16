# README.md

## Project Title: eGatividhi

### Demo Video

[eGatividhi_Demo.webm](https://github.com/user-attachments/assets/6aec8d7d-90ca-40a3-841b-ddf418826434)


### Image Results Example

Result Image 1 
![Screenshot 2025-01-16 012918](https://github.com/user-attachments/assets/8ae93124-6aba-4977-aa78-fdebf4ca2cf6)

Result Image 2
![Screenshot 2025-01-16 013142](https://github.com/user-attachments/assets/a3b4d5a9-c29e-4891-9d26-75e3e10366d7)


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

#### Backend Setup
1. Clone the repository:
```bash
git clone <repository-url>
```
2. Navigate to the project directory:
```bash
cd eGatividhi
```
3. Set up the database:
- Create a MySQL database named `eGatividhi`
- Import the necessary SQL schema:
```bash
mysql -u your_username -p eGatividhi < schema.sql
```
4. Update database connection settings in `1.php`:
```php
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "eGatividhi";
```

#### Python Environment Setup
1. Create and activate a virtual environment:
```bash
python -m venv venv
source venv/bin/activate # On Windows: venv\Scripts\activate
```
2. Install required Python packages:
```bash
pip install -r requirements.txt
```

#### Frontend Setup
1. Install Node.js dependencies:
```bash
npm install
```
2. Build the React application:
```bash
npm run build
```

### Running the Application

#### Start the Python Backend
1. Activate the virtual environment if not already activated:
```bash
source venv/bin/activate # On Windows: venv\Scripts\activate
```
2. Run the Python application:
```bash
python app.py
```
The server will start on `http://localhost:5000`

#### Start the Web Server
1. Configure your web server (Apache/Nginx) to serve the application
2. For development, you can use PHP's built-in server:
```bash
php -S localhost:8000
```

### Configuration
- Update `config.py` with your specific settings:
```python
DEBUG = False
SECRET_KEY = 'your-secret-key'
UPLOAD_FOLDER = 'path/to/uploads'
```
- Configure environment variables in `.env`:
```
DB_HOST=localhost
DB_USER=your_username
DB_PASS=your_password
DB_NAME=eGatividhi
```

### File Structure
```
eGatividhi/
│
├── .gitignore
├── .htaccess
├── .env
├── requirements.txt
├── config.py
├── 1.php
├── 2.php
├── about.html
├── app.py
├── assets/
│ ├── css/
│ │ └── main.css
│ └── js/
│ └── main.js
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
├── schema.sql
└── templates/
└── index.html
```

### API Documentation
The application provides the following API endpoints:

- `POST /api/upload` - Upload construction images
- `GET /api/projects` - List all projects
- `GET /api/projects/{id}` - Get specific project details
- `POST /api/compare` - Compare two images for progress analysis

### Testing
Run the test suite:
```bash
python -m pytest tests/
```

### Contributing
1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit changes: `git commit -am 'Add feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a Pull Request
