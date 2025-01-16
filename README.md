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

[Previous sections remain the same until Technologies Used]

### Technologies Used
- **Frontend**: HTML, CSS (Bootstrap), JavaScript (React)
- **Backend**: PHP, Python
- **Database**: MySQL
- **AI/ML**: PyTorch, OpenCV, eGatividhi_ML: Construction-Progress-Monitoring Model
- **Version Control**: Git

### ML Model Integration
This web application implements the ML model from my repository: [eGatividhi_ML: Construction-Progress-Monitoring] (https://github.com/gan3xh/eGatividhi_ML.git). The model is designed for:
- Construction progress detection through image analysis
- Structural completion estimation
- Quality assessment of construction work
- Automated progress calculation

#### Model Features
- Built with PyTorch and OpenCV
- Uses transfer learning on ResNet architecture
- Trained on construction site image datasets
- Achieves 95% accuracy in progress detection
- Supports multiple construction types and stages

#### Integration Details
1. **Model Implementation**
   - Model weights are stored in `/models/weights/`
   - Inference scripts located in `/src/ml/inference.py`
   - Image preprocessing utilities in `/src/ml/preprocessing/`
   - Progress calculation algorithms in `/src/ml/progress_calc/`

2. **API Integration**
   ```python
   from src.ml.inference import ProgressDetector
   
   # Initialize model
   detector = ProgressDetector(weights_path='models/weights/best.pth')
   
   # Process images
   progress = detector.calculate_progress(image_path)
   ```

3. **Performance Optimizations**
   - GPU acceleration support
   - Batch processing for multiple images
   - Caching of intermediate results
   - Async processing for better user experience

#### Model Requirements
- CUDA-compatible GPU (recommended)
- PyTorch 1.8+
- OpenCV 4.5+
- CUDA Toolkit 11.0+ (for GPU support)

[Rest of the README remains the same]
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

#### Workflow
1. Ministry officials create a new project in the system
2. Project parameters and requirements are set
3. Project Manager is assigned
4. Project Manager begins documentation:
   - Regular image uploads
   - Progress updates
   - Milestone tracking
5. System processes uploaded images:
   - AI analysis of construction progress
   - Comparison with previous stages
   - Quality assessment
6. Ministry officials review:
   - AI-generated progress reports
   - Construction quality analysis
   - Timeline adherence
   - Budget utilization
7. Documentation is automatically generated and stored

### Use Cases

#### Ministry Portal Login
1. **Project Initialization**
   - Create new construction projects
   - Set project parameters:
     - Project deadline
     - Budget allocation
     - Construction milestones
     - Expected deliverables
   - Assign Project Managers to specific projects
   - Upload initial project documentation and blueprints

2. **Project Monitoring**
   - Access real-time progress reports
   - View AI-powered construction progress analysis
   - Compare actual progress with planned timeline
   - Monitor budget utilization
   - Generate automated progress reports for documentation
   - Verify construction quality through image analysis
   - Track milestone completion status

3. **Project Management**
   - Update project parameters as needed
   - Review and approve change requests
   - Access historical project data
   - Generate compliance reports
   - Export progress documentation

#### Project Manager Portal Login
1. **Project Documentation**
   - Access assigned projects
   - View project details and requirements
   - Upload construction site images at various stages
   - Document daily/weekly progress
   - Report construction challenges or delays

2. **Progress Tracking**
   - Upload progress images with timestamps
   - Add progress descriptions and notes
   - Tag images with specific milestone information
   - Report completion percentage
   - Document material usage and workforce deployment

3. **Communication**
   - Receive notifications about project updates
   - Submit queries to ministry officials
   - Access feedback on uploaded documentation
   - Request timeline extensions if needed

#### AI-Powered Features
- **Image Analysis**
  - Automatic detection of construction progress
  - Comparison between different stages of construction
  - Quality assessment of construction work
  - Identification of potential construction issues
  - Generation of progress percentage based on visual data

- **Report Generation**
  - Automated progress reports
  - Comparison charts and graphs
  - Timeline tracking
  - Budget utilization analysis
  - Milestone completion status
  - Construction quality metrics


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
