<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$host = "localhost";
$username = "shantanu";
$password = "11qqaazz";
$dbname = "eGatividhi";

// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize default variables
$message = "Please fill out the registration form.";
$imageSrc = "info.png"; // Default image for the form page
$heading = "Registration Unsuccessful!";
$buttonText = "Back to Register";
$buttonLink = "Register.html";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate input data
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $phoneNumber = preg_match('/^\d{10}$/', $_POST['phone']) ? $_POST['phone'] : null;
    $securityQuestion = trim($_POST['security_question']);
    $securityAnswer = trim($_POST['security_answer']);
    $table = $_POST['table'];

    // Whitelist table names for safety
    $allowedTables = ['Project_Manager', 'Ministry'];
    if (!in_array($table, $allowedTables)) {
        $message = "Invalid table selected.";
        $imageSrc = "error.png";
    } elseif ($email === false) {
        $message = "Invalid email address.";
        $imageSrc = "error.png";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
        $imageSrc = "error.png";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL query to check for email existence
        $stmt = $conn->prepare("SELECT Email FROM $table WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Error: The email address '$email' is already registered. Please use a different email.";
            $imageSrc = "error.png";
        } else {
            // Prepare the SQL query for insertion
            if ($table === "Project_Manager") {
                $stmt = $conn->prepare("INSERT INTO Project_Manager (Email, Password, First_Name, Last_Name, Phone_Number, Security_Question, Security_Question_Answer) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssss", $email, $hashedPassword, $_POST['first_name'], $_POST['last_name'], $phoneNumber, $securityQuestion, $securityAnswer);
            } elseif ($table === "Ministry") {
                $stmt = $conn->prepare("INSERT INTO Ministry (Email, Password, Contact_Person_First_Name, Contact_Person_Last_Name, Phone_Number, Security_Question, Security_Question_Answer) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssss", $email, $hashedPassword, $_POST['first_name'], $_POST['last_name'], $phoneNumber, $securityQuestion, $securityAnswer);
            }

            // Execute the query
            if ($stmt->execute()) {
                $message = "Thank you for registering. You can now log in to your account.";
                $imageSrc = "worker.png"; // Image for success
                $heading = "Registration Successful!";
                $buttonText = "Go";
                if ($table === "Project_Manager") {
                    $buttonLink = "Project_Manager.php";
                } elseif ($table === "Ministry") {
                    $buttonLink = "Ministry.php";
                }            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
                $imageSrc = "error.png"; // Image for failure
            }
        }

        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Status</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('http://localhost/eGatividhi/assets/img/hero-carousel/hero-carousel-2.jpg') no-repeat center center fixed; /* Replace 'background-image.jpg' with your image path */
        background-size: cover; 
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .status-container {
            width: 400px;
            background: linear-gradient(to right, rgba(216, 129, 15, 0.85), rgba(204, 56, 11, 0.85));
            border-radius: 1.5rem;
            padding: 3%;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .status-container img {
            margin-top: 5%;
            margin-bottom: 5%;
            width: 25%;
            animation: mover 1s infinite alternate;
        }
        @keyframes mover {
            0% { transform: translateY(0); }
            100% { transform: translateY(-10px); }
        }
        .status-heading {
            color: #fff;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .status-message {
            color: #fff;
            font-size: 1rem;
            margin-bottom: 20px;
        }
        .btnHome {
            width: 100%;
            border: none;
            border-radius: 1.5rem;
            height: 50px;
            background: #b63a29;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 10px;
            text-decoration: none;
            line-height: 50px;
            display: inline-block;
        }
        .btnHome:hover {
            background: #b63a29;
        }
    </style>
</head>
<body>
<div class="status-container">
    <img src="<?php echo htmlspecialchars($imageSrc); ?>" alt="Status Icon" />
    <h2 class="status-heading"><?php echo htmlspecialchars($heading); ?></h2>
    <p class="status-message"><?php echo htmlspecialchars($message); ?></p>
    <a href="<?php echo htmlspecialchars($buttonLink); ?>" class="btnHome"><?php echo htmlspecialchars($buttonText); ?></a>
</div>
</body>
</html>
