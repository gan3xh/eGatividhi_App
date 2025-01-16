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

// Start session
session_start();

// Initialize variables for messages
$message = "Invalid login credentials.";
$redirectLink = "login.html";
$imageSrc = "info.png"; // Default image for the form page

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);
    $table = $_POST['table'];

    // Whitelist table names for safety
    $allowedTables = ['Project_Manager', 'Ministry'];
    if (!in_array($table, $allowedTables)) {
        $message = "Invalid table selected.";
        $imageSrc = "error.png";
    } elseif ($email === false || empty($password)) {
        $message = "Invalid email or password.";
        $imageSrc = "error.png";
    } else {
        // Determine the column to use for the first name based on the table
        $firstNameColumn = $table === 'Ministry' ? 'Contact_Person_First_Name' : 'First_Name';

        // Query to fetch user data
        $stmt = $conn->prepare("SELECT ID, Password, $firstNameColumn AS FirstName FROM $table WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $row['Password'])) {
                // Store user details in session
                $_SESSION['user_id'] = $row['ID'];
                $_SESSION['first_name'] = $row['FirstName'];
                $_SESSION['role'] = $table;

                $message = "Login successful. Redirecting...";
                $redirectLink = ($table === 'Project_Manager') ? "Project_Manager.php" : "Ministry.php";
                $imageSrc = "worker.png";
            } else {
                $message = "Incorrect password.";
                $imageSrc = "error.png";
            }
        } else {
            $message = "No account found with this email.";
            $imageSrc = "error.png";
        }

        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Status</title>
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
        .status-heading {
            color: #fff;
            font-weight: bold;
            margin-bottom: 40px;
        }
        .status-message {
            color: #fff;
            font-size: 1rem;
            margin-bottom: 20px;
        }
        .btnRedirect {
            width: 100%;
            border: none;
            border-radius: 1.5rem;
            height: 50px;
            background: #b63a29;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            line-height: 50px;
        }
        .btnRedirect:hover {
            background: #a72f23;
        }
    </style>
</head>
<body>
<div class="status-container">
<img src="<?php echo htmlspecialchars($imageSrc); ?>" alt="Status Icon" />
    <h2 class="status-heading"><?php echo $message; ?></h2>
    <a href="<?php echo htmlspecialchars($redirectLink); ?>" class="btnRedirect">Continue</a>
</div>
</body>
</html>
