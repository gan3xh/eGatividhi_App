<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = "localhost";
$username = "shantanu";
$password = "11qqaazz";
$dbname = "eGatividhi";
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve project ID from URL or handle form submission
$project_id = isset($_GET['project_id']) ? $_GET['project_id'] : (isset($_POST['project_id']) ? $_POST['project_id'] : null);

// Initialize upload feedback array
$upload_feedback = [];

// Serve image for display
if (isset($_GET['image_id'])) {
    $image_id = (int)$_GET['image_id'];
    $stmt = $conn->prepare("SELECT image_type, image_data FROM project_image WHERE id = ?");
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($image_type, $image_data);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        // Dynamic filename logic
        $file_extension = explode('/', $image_type)[1]; // Extracts extension
        $dynamic_filename = 'project_day_' . $image_id . '.' . $file_extension;

        header("Content-Type: $image_type");
        header('Content-Disposition: attachment; filename="' . $dynamic_filename . '"');
        echo $image_data;
    } else {
        http_response_code(404);
        echo "Image not found.";
    }
    $stmt->close();
    exit;
}

// Handle POST request for image upload
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $day_number = $_POST['day_number'];
//     $upload_date = $_POST['upload_date'];
//     $image = $_FILES['image'];

//     // Validate file size and type
//     $valid_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
//     $file_mime_type = mime_content_type($image['tmp_name']);

//     if (!in_array($file_mime_type, $valid_mime_types)) {
//         $upload_feedback[$day_number] = "Invalid file type for Day $day_number. Only JPEG, PNG, GIF, WEBP allowed.";
//     } elseif ($image['size'] > 50 * 1024 * 1024) {
//         $upload_feedback[$day_number] = "File size exceeds 50MB for Day $day_number.";
//     } else {
//         // Read image data
//         $image_data = file_get_contents($image['tmp_name']);
//         $image_path = ''; // Placeholder value or compute the path if necessary
//         $stmt = $conn->prepare("INSERT INTO project_images (project_id, day_number, upload_date, image_path, image_type, image_data)
//                         VALUES (?, ?, ?, ?, ?, ?)");
//         $stmt->bind_param("iissss", $project_id, $day_number, $upload_date, $image_path, $file_mime_type, $image_data);

//         if ($stmt->execute()) {
//             $upload_feedback[$day_number] = "Image uploaded successfully for Day $day_number.";
//         } else {
//             $upload_feedback[$day_number] = "Database error for Day $day_number: " . $stmt->error;
//         }
//         $stmt->close();
//     }
// }

// Modified upload handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $week_number = $_POST['week_number'];
    $image_number = $_POST['image_number'];
    $upload_date = $_POST['upload_date'];
    $image = $_FILES['image'];

    // Define validation variables
    $valid_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file_mime_type = mime_content_type($image['tmp_name']);

    if (!in_array($file_mime_type, $valid_mime_types)) {
        $upload_feedback["week{$week_number}_img{$image_number}"] = "Invalid file type for Week $week_number, Image $image_number.";
    } elseif ($image['size'] > 50 * 1024 * 1024) {
        $upload_feedback["week{$week_number}_img{$image_number}"] = "File size exceeds 50MB.";
    } else {
        $image_data = file_get_contents($image['tmp_name']);
        $image_path = '';
        $stmt = $conn->prepare("INSERT INTO project_image (project_id, week_number, image_number, upload_date, image_path, image_type, image_data) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiissss", $project_id, $week_number, $image_number, $upload_date, $image_path, $file_mime_type, $image_data);

        if ($stmt->execute()) {
            $upload_feedback["week{$week_number}_img{$image_number}"] = "Image uploaded successfully.";
        } else {
            $upload_feedback["week{$week_number}_img{$image_number}"] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch project details
$sql = "SELECT * FROM projects WHERE project_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $project_id);

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $project = $result->fetch_assoc();
    $duration = $project['duration'];
    $start_date = $project['start_date'];
} else {
    die("Project not found.");
}
$stmt->close();

// Calculate dates for each day of the project
// $dates = [];
// $current_date = new DateTime($start_date);
// for ($i = 0; $i < $duration; $i++) {
//     $dates[] = $current_date->format('Y-m-d');
//     $current_date->modify('+1 day');
// }

// Calculate weeks and dates
// $weeks = [];
// $current_date = new DateTime($start_date);
// $week_number = 1;
// $week_dates = [];

// for ($i = 0; $i < $duration; $i++) {
//     $week_dates[] = [
//         'date' => $current_date->format('Y-m-d'),
//         'day_number' => $i + 1
//     ];
    
//     if (count($week_dates) === 3 || $i === $duration - 1) {
//         $weeks[$week_number] = $week_dates;
//         $week_dates = [];
//         $week_number++;
//     }
    
//     $current_date->modify('+1 day');
// }

// // Fetch the number of uploaded images for the project
// $sql_images = "SELECT COUNT(*) AS uploaded_images FROM project_images WHERE project_id = ?";
// $stmt_images = $conn->prepare($sql_images);
// $stmt_images->bind_param("s", $project_id);
// $stmt_images->execute();
// $result_images = $stmt_images->get_result();
// $uploaded_images = $result_images->fetch_assoc()['uploaded_images'];
// $stmt_images->close();


// // Calculate progress percentage
// $progress = $duration > 0 ? ($uploaded_images / $duration) * 100 : 0;

// // Fetch existing images for the project
// $uploaded_images = [];
// $sql = "SELECT id, day_number FROM project_images WHERE project_id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $project_id);
// $stmt->execute();
// $result = $stmt->get_result();
// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         $uploaded_images[$row['day_number']] = $row['id'];
//     }
// }
// $stmt->close();

// Calculate number of weeks instead of days
$start = new DateTime($start_date);
$end = new DateTime($project['completion_date']);
$interval = $start->diff($end);
$total_days = $interval->days;
$total_weeks = ceil($total_days / 7);

// Calculate dates for each week
$weeks = [];
$current_date = new DateTime($start_date);
for ($i = 0; $i < $total_weeks; $i++) {
    $week_start = $current_date->format('Y-m-d');
    $current_date->modify('+6 days');
    $week_end = $current_date->format('Y-m-d');
    $weeks[] = [
        'start' => $week_start,
        'end' => $week_end
    ];
    $current_date->modify('+1 day');
}



// Fetch existing images
$uploaded_images = [];
$sql = "SELECT id, week_number, image_number FROM project_image WHERE project_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $uploaded_images[$row['week_number']][$row['image_number']] = $row['id'];
    }
}
$stmt->close();

// Fetch images grouped by week
$progress_images = [];
$sql = "SELECT id, week_number, image_number, upload_date, image_type 
        FROM project_image 
        WHERE project_id = ? 
        ORDER BY week_number, image_number";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if (!isset($progress_images[$row['week_number']])) {
        $progress_images[$row['week_number']] = [
            'week' => $row['week_number'],
            'date' => $row['upload_date'],
            'images' => []
        ];
    }
    
    $progress_images[$row['week_number']]['images'][] = [
        'id' => $row['id'],
        'url' => "?image_id=" . $row['id'],
        'description' => "Week {$row['week_number']}, Image {$row['image_number']}"
    ];
}
$stmt->close();

// Convert to JavaScript array
$js_progress_images = json_encode(array_values($progress_images));
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>eGatividhi</title>
    <meta
      name="description"
      content="Upload and compare images to detect construction progress"
    />
    <meta
      name="keywords"
      content="construction, progress detection, AI, image comparison"
    />

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
      rel="stylesheet"
    />

   <!-- Update these in the head section -->
<script src="https://unpkg.com/react@17/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
<script>
    const { useState, useEffect } = React;
</script>

    <!-- Custom CSS -->
    <style>
      body {
        font-family: "Roboto", sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
        align-items: center;
      }

      .header {
        background-color: rgba(255, 255, 255, 0.9);
        border-bottom: 1px solid #ddd;
        padding: 15px;
        position: fixed;
        width: 100%;
        z-index: 1000;
      }
        main {
            padding: 2em;
            margin: 0 auto;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            align-items: center;
        }

        .card {
            width: 600px;
            padding: 20px;
            background: #fff;
            border: 6px solid #000;
            box-shadow: 12px 12px 0 #000;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
            margin-top:30px;
            margin-left:auto;
            margin-right:auto;
        }

        .card:hover {
            transform: translate(-5px, -5px);
            box-shadow: 17px 17px 0 #000;
        }

        .card__title {
            font-size: 30px;
            font-weight: 900;
            color: #000;
            text-transform: uppercase;
            margin-bottom: 15px;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .card__title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 90%;
            height: 3px;
            background-color: #000;
            transform: translateX(-100%);
            transition: transform 0.3s;
        }

        .card:hover .card__title::after {
            transform: translateX(0);
        }

        .card__content {
            font-size: 20px;
            line-height: 1.4;
            color: #000;
            margin-bottom: 20px;
            align-self:center;
        }

        

        @keyframes progress {
            0% { --percentage: 0; }
            100% { --percentage: var(--value); }
        }

        [role="progressbar"] {
            --percentage: var(--value);
            --primary: #333;
            --secondary: rgb(194, 195, 196);
            --size: 150px;
            animation: progress 2s 0.5s forwards;
            width: var(--size);
            aspect-ratio: 1;
            border-radius: 50%;
            position: relative;
            overflow: hidden;
            display: grid;
            place-items: center;
            margin: 20px auto;
        }

        [role="progressbar"]::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: conic-gradient(var(--primary) calc(var(--percentage) * 1%), var(--secondary) 0);
            mask: radial-gradient(white 55%, transparent 0);
            mask-mode: alpha;
            -webkit-mask: radial-gradient(#0000 55%, #000 0);
            -webkit-mask-mode: alpha;
        }

        [role="progressbar"]::after {
            counter-reset: percentage var(--value);
            content: counter(percentage) '%';
            font-family: Helvetica, Arial, sans-serif;
            font-size: calc(var(--size) / 5);
            color: var(--primary);
        }

        h1 {
  position: relative;
  padding: 0;
  margin: 0;
  font-family: "Raleway", sans-serif;
  font-weight: 300;
  font-size: 40px;
  color: #080808;
  -webkit-transition: all 0.4s ease 0s;
  -o-transition: all 0.4s ease 0s;
  transition: all 0.4s ease 0s;
}

h1 span {
  display: block;
  font-size: 0.5em;
  line-height: 1.3;
}
h1 em {
  font-style: normal;
  font-weight: 600;
}

    /* From Uiverse.io by zymantas-katinas */ 
    .seven h1 {
text-align: center;
    font-size:30px; font-weight:300; color:#222; letter-spacing:1px;
    text-transform: uppercase;

    display: grid;
    grid-template-columns: 1fr max-content 1fr;
    grid-template-rows: 27px 0;
    grid-gap: 20px;
    align-items: center;
}

.seven h1:after,.seven h1:before {
    content: " ";
    display: block;
    border-bottom: 1px solid #333;
    border-top: 1px solid #333;
    height: 5px;
  background-color:#f8f8f8;
}

        .details {
            margin-top: 2em;
        }
        .details strong {
            display: inline-block;
            width: 150px;
        }
        .custum-file-upload {
            height: 280px;
            width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            border: 2px dashed #cacaca;
            background-color: rgba(255, 255, 255, 1);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0px 48px 35px -48px rgba(0, 0, 0, 0.1);
            position: relative;
            margin:30px 20px;
        }
        .custum-file-upload input[type="file"] {
            display: none;
        }
        .preview-image {
            max-height: 100%;
            max-width: 100%;
            object-fit: cover;
            border-radius: 10px;
            display: none;
        }
        .feedback {
            margin-top: 10px;
            font-size: 14px;
            color: green;
        }
        .feedback.error {
            color: red;
        }
        .upload-container {
    display: flex;
    flex-direction: column;
    gap: 30px;
    margin: 20px auto;
}

.week-row {
    width: 100%;
    display: flex;
    background: #fff;
    padding: 20px;
    border: 3px solid #000;
    box-shadow: 6px 6px 0 #000;
    border-radius: 12px;
    gap: 40px;
}

.week-header {
    flex: 0 0 150px;
    padding: 20px;
    margin-top:130px;
}

.image-slots {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}


        .custum-file-upload div {
            pointer-events: none; /* Prevents interference with clicks */
        }

        .upload-box {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            background:#f9f9f9;
            /* background: -webkit-linear-gradient(bottom, #757574, #e2dddb); */
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: auto;
            min-height: 500px;
            overflow: hidden;
            position: relative;
        }
        
        .upload-box img {
            cursor: pointer;
            max-width: 400px;
            max-height: 270px;
            object-fit: cover;
            border-radius: 8px;
            margin: 40px 20px;
        }
        .upload-box label {
            margin-bottom: 10px;
            font-weight: bold;
        }


        
        
        /* button {
            margin-top: 10px;
            z-index: 1;
            position: relative;
        } */

        .button {
            margin-left:auto;
            margin-right:auto;
            margin-top: 40px;
            z-index: 1;
            --main-focus: #2d8cf0;
            --font-color: #323232;
            --bg-color-sub: #dedede;
            --bg-color: #eee;
            --main-color: #323232;
            position: relative;
            width: 140px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            border: 2px solid var(--main-color);
            box-shadow: 4px 4px var(--main-color);
            background-color: var(--bg-color);
            border-radius: 10px;
            overflow: hidden;
        }

        .button, .button__icon, .button__text {
            transition: all 0.3s;
        }

        .button .button__text {
            transform: translateX(22px);
            color: var(--font-color);
            font-weight: 600;
        }

        .button .button__icon {
            position: absolute;
            transform: translateX(101px);
            height: 100%;
            width: 35px;
            background-color: var(--bg-color-sub);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .button .svg {
            width: 20px;
            fill: var(--main-color);
        }

        .button:hover {
            background: var(--bg-color);
        }

        .button:hover .button__text {
            color: transparent;
        }

        .button:hover .button__icon {
            width: 144px;
            transform: translateX(0);
        }

        .button:active {
            transform: translate(3px, 3px);
            box-shadow: 0px 0px var(--main-color);
        }
        /* .download-btn {
            position: absolute;
            bottom: 20px;
            margin-top: 10px;
            display: block;
            text-decoration: none;
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-align: center;
        }
        .download-btn:hover {
            background-color: #218838;
        } */

        .download-btn {


  
margin-left:auto;
margin-right:auto;
margin-top: auto;
margin-bottom:auto;
display: inline-flex;
align-items: center;
font-family: inherit;
font-weight: 500;
font-size: 17px;
padding: 0.8em 1.5em 0.8em 1.2em;
color: black;
background: linear-gradient(0deg, rgb(222, 222, 222) 0%, rgb(238, 238, 238) 100%);
border: 2px solid #9e9790;
border-radius: 20em;
text-decoration: none; /* Ensures no underline */
transition: all 0.3s ease;
}

.download-btn:hover {
border-color: white;
box-shadow: 0 0.5em 1.5em -0.5em #b3aaa3;
}

.download-btn:active {
box-shadow: 0 0.3em 1em -0.5em  #b3aaa3;
}

.download-btn svg {
margin-right: 8px;
}

.download-btn span {
display: inline-block;
}


        .download-btn-container {
            display: flex;
            justify-content: center; /* Center the button horizontally */
            width: 100%; /* Ensure it spans the container */
            margin-top: 10px;
        }
        /* Full-screen overlay styles */
        #fullscreen-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        #fullscreen-overlay img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
        }


      .logo h1 {
        margin: 0;
        font-size: 24px;
        color: #333;
      }

      .logo span {
        color: #ffbc00;
      }

      .navmenu ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
      }

      .navmenu ul li {
        margin-right: 20px;
      }

      .navmenu ul li a {
        text-decoration: none;
        color: #333;
        font-size: 16px;
      }

      .page-title {
        background-image: url("http://localhost/eGatividhi/static/img/page-title-bg.jpg");
        background-size: cover;
        background-position: center;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
      }

      .page-title h1 {
        font-size: 36px;
        margin: 0;
      }

      .section-header h2 {
        font-size: 28px;
        text-align: center;
        margin-bottom: 10px;
      }

      .section-header p {
        text-align: center;
        color: #555;
      }

      .form-group {
        margin-bottom: 20px;
      }

      .form-label {
        font-weight: 500;
        margin-bottom: 5px;
      }

      .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
      }

      .btn-primary {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
      }

      .btn-primary:hover {
        background-color: #0056b3;
      }

      .image-preview {
        max-width: 100%;
        max-height: 300px;
        margin-top: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
      }

      #resultImage {
        max-width: 100%;
        margin-top: 20px;
        display: none;
        border-radius: 5px;
      }

      #technicalDetails {
        margin-top: 20px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 5px;
      }

      #technicalDetails h4 {
        margin-bottom: 10px;
      }

      #technicalDetails p {
        margin-bottom: 5px;
      }

      footer {
        background-color: #333;
        color: white;
        padding: 40px 0;
        text-align: center;
      }

      footer a {
        color: #ffbc00;
        text-decoration: none;
      }

      footer a:hover {
        text-decoration: underline;
      }

      footer ul {
        list-style: none;
        padding: 0;
      }

      footer ul li {
        display: inline-block;
        margin-right: 20px;
      }

      footer ul li a {
        color: #aaa;
      }

      footer ul li a:hover {
        color: #fff;
      }

      

      .scroll-up {
        position: fixed;
        right: 20px;
        bottom: 20px;
        cursor: pointer;
        z-index: 10;
        width: 32px;
        height: 32px;
        border-radius: 4px;
        background-color: rgba(29, 29, 31, 0.7);
        backdrop-filter: saturate(180%) blur(20px);
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        transition: bottom .4s, transform .4s;
      }

      .scroll-up:hover {
        transform: translateY(-.25rem);
      }

      ._show-scroll {
        bottom: 3rem;
      }

      @media (max-width: 1199.98px) {
        .scroll-up {
          right: 1rem;
        }
      }

      .botton {
      margin-top:10px;
      margin-left:49%;
      cursor: pointer;
      position: relative;
      padding: 10px 17px;
      font-size: 18px;
      color: rgb(193, 163, 98);
      text-decoration: none; /* Remove underline */
      border: 2px solid rgb(193, 163, 98);
      border-radius: 34px;
      background-color: transparent;
      font-weight: 600;
      transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
      overflow: hidden;
      display: inline-block; /* Make it behave like a botton */
    }

    .botton::before {
      content: '';
      position: absolute;
      inset: 0;
      margin: auto;
      width: 50px;
      height: 50px;
      border-radius: inherit;
      scale: 0;
      z-index: -1;
      background-color: rgb(193, 163, 98);
      transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
    }

    .botton:hover::before {
      scale: 3;
    }

    .botton:hover {
      color: #212121;
      scale: 1.1;
      box-shadow: 0 0px 20px rgba(193, 163, 98, 0.4);
    }

    .botton:active {
      scale: 1;
    }

    .modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1050;
    transition: opacity 0.3s ease-in-out;
    opacity: 0;
    pointer-events: none;
}

.modal.show {
    display: block;
}

.modal-dialog {
    background: white;
    position: relative;
    width: 90%;
    max-width: 1200px;
    margin: 30px auto;
    border-radius: 8px;
    overflow: hidden;
    transform: translateY(-20px);
    transition: transform 0.3s ease-in-out;
}

.modal-content {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: relative;
    padding: 20px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.modal-body {
    padding: 1rem;
}

.modal.show {
    opacity: 1;
    pointer-events: auto;
}

.modal.show .modal-dialog {
    transform: translateY(0);
}

    </style>
  </head>

  <body>
    <header class="header">
      <div class="container d-flex justify-content-between align-items-center">
        <a href="/" class="logo">
          <h1>eGatividhi<span>.</span></h1>
        </a>
        <nav class="navmenu">
          <ul class="d-flex">
            <li><a href="http://127.0.0.1/eGatividhi/index.html">Home</a></li>
            <li><a href="http://127.0.0.1/eGatividhi/about.html">About</a></li>
            <li><a href="http://127.0.0.1/eGatividhi/services.html">Services</a></li>
            <li><a href="http://127.0.0.1/eGatividhi/contact.html">Contact</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <main class="main">
      <div class="page-title">
        <div class="container">
          <h1>Project Details</Details></h1>
        </div>
      </div>

      <!-- Detection Section -->
      <div class="card">
        <span class="card__title"><?php echo $project['project_name']; ?></span>
        <div class="card__content">
            <p><strong>Project ID:</strong> <?php echo $project['project_id']; ?></p>
            <p><strong>Sector:</strong> <?php echo $project['sector']; ?></p>
            <p><strong>Sub-Sector:</strong> <?php echo $project['sub_sector']; ?></p>
            <p><strong>Location:</strong> <?php echo $project['location']; ?></p>
            <p><strong>Start Date:</strong> <?php echo $project['start_date']; ?></p>
            <p><strong>Completion Date:</strong> <?php echo $project['completion_date']; ?></p>
            <p><strong>Duration:</strong> <?php echo $project['duration']; ?> days</p>
            <p><strong>Manager:</strong> <?php echo $project['project_manager']; ?></p>
            <p><strong>Contractor:</strong> <?php echo $project['project_contractor']; ?></p>
            <p><strong>Total Cost:</strong> Rs. <?php echo $project['total_cost']; ?></p>
        </div>
    </div>

        <div class="seven">
    <br><h1>Upload Project Images</h1>
  </div>

  <div class="upload-container">
    <?php foreach ($weeks as $week_index => $week): ?>
    <div class="week-row">
        <div class="week-header">
            <h3>Week <?php echo $week_index + 1; ?></h3><br>
            <p class="text-muted"><?php echo $week['start']; ?> <br>to<br> <?php echo $week['end']; ?></p>
        </div>
        
        <div class="image-slots">
            <?php for ($image_num = 1; $image_num <= 3; $image_num++): ?>
            <div class="upload-box">
                <form action="" method="POST" enctype="multipart/form-data">
                    <label>Image <?php echo $image_num; ?></label>
                    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                    <input type="hidden" name="week_number" value="<?php echo $week_index + 1; ?>">
                    <input type="hidden" name="image_number" value="<?php echo $image_num; ?>">
                    <input type="hidden" name="upload_date" value="<?php echo date('Y-m-d'); ?>">

                    <?php if (isset($uploaded_images[$week_index + 1][$image_num])): ?>
                        <img src="?image_id=<?php echo $uploaded_images[$week_index + 1][$image_num]; ?>" 
                             alt="Week <?php echo $week_index + 1; ?>, Image <?php echo $image_num; ?>" 
                             class="preview-image" 
                             style="display: block; cursor: pointer;" 
                             onclick="openFullscreen(<?php echo $uploaded_images[$week_index + 1][$image_num]; ?>)">

                        <div class="download-btn-container">
                            <a href="?image_id=<?php echo $uploaded_images[$week_index + 1][$image_num]; ?>" 
                               download class="download-btn">Download</a>
                        </div>
                    <?php else: ?>
                        <label class="custum-file-upload">
                            <img class="preview-image" id="preview-<?php echo $week_index; ?>-<?php echo $image_num; ?>" alt="Preview">
                            <div>Click to upload image</div>
                            <input type="file" name="image" 
                                   onchange="previewImage(event, '<?php echo $week_index; ?>-<?php echo $image_num; ?>')" 
                                   required>
                        </label>
                        <button class="button" type="submit">
                            <span class="button__text">Upload</span>
                            <span class="button__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" class="svg">
                                    <path d="M17.5,22.131a1.249,1.249,0,0,1-1.25-1.25V2.187a1.25,1.25,0,0,1,2.5,0V20.881A1.25,1.25,0,0,1,17.5,22.131Z"></path>
                                    <path d="M17.5,22.693a3.189,3.189,0,0,1-2.262-.936L8.487,15.006a1.249,1.249,0,0,1,1.767-1.767l6.751,6.751a.7.7,0,0,0,.99,0l6.751-6.751a1.25,1.25,0,0,1,1.768,1.767l-6.752,6.751A3.191,3.191,0,0,1,17.5,22.693Z"></path>
                                    <path d="M31.436,34.063H3.564A3.318,3.318,0,0,1,.25,30.749V22.011a1.25,1.25,0,0,1,2.5,0v8.738a.815.815,0,0,0,.814.814H31.436a.815.815,0,0,0,.814-.814V22.011a1.25,1.25,0,1,1,2.5,0v8.738A3.318,3.318,0,0,1,31.436,34.063Z"></path>
                                </svg>
                            </span>
                        </button>
                    <?php endif; ?>
                </form>
            </div>
            <?php endfor; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div id="progress-viewer-container" style="display: none;"></div>
<div id="progress-viewer-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Construction Progress</h5>
                <button type="button" class="btn-close" onclick="hideProgressViewer()"></button>
            </div>
            <div class="modal-body">
                <div id="progress-viewer-root"></div>
            </div>
        </div>
    </div>
</div>
<a href="#" class="botton" onclick="showProgressViewer(); return false;">View Progress</a>
<a href="http://127.0.0.1:5000/" class="botton">Get Started</a>

    </main>
    <!-- Fullscreen overlay -->
    <div id="fullscreen-overlay" onclick="closeFullscreen()">
        <img id="fullscreen-image" alt="Full screen preview">
    </div>

    <footer>
      <div class="container">
        <ul class="d-flex justify-content-center mb-4">
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
        <p>
          &copy; 2024 eGatividhi. All Rights Reserved. |
          <a href="#">Privacy Policy</a>
        </p>
        <div>
          <a href="#"><i class="bi bi-facebook me-3"></i></a>
          <a href="#"><i class="bi bi-twitter me-3"></i></a>
          <a href="#"><i class="bi bi-instagram"></i></a>
        </div>
      </div>
    </footer>

    <a id="scroll-up" class="scroll-up" href="#">
    	<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    		<path d="M0 0h24v24H0z" fill="none"></path>
    		<path fill="rgba(255,255,255,1)" d="M11.9997 10.8284L7.04996 15.7782L5.63574 14.364L11.9997 8L18.3637 14.364L16.9495 15.7782L11.9997 10.8284Z">
    		</path>
    	</svg>
    </a>

    <script type="text/babel">
    const progressImages = <?php echo $js_progress_images; ?>;
    
    const BuildingProgressViewer = () => {
        const [currentImageIndex, setCurrentImageIndex] = useState(0);
        const [currentWeekIndex, setCurrentWeekIndex] = useState(0);
        const [isPlaying, setIsPlaying] = useState(false);
        
        const weeklyProgress = progressImages;

        useEffect(() => {
            let interval;
            if (isPlaying) {
                interval = setInterval(() => {
                    const currentWeek = weeklyProgress[currentWeekIndex];
                    if (currentImageIndex === currentWeek?.images?.length - 1) {
                        if (currentWeekIndex === weeklyProgress.length - 1) {
                            setCurrentWeekIndex(0);
                            setCurrentImageIndex(0);
                        } else {
                            setCurrentWeekIndex(prev => prev + 1);
                            setCurrentImageIndex(0);
                        }
                    } else {
                        setCurrentImageIndex(prev => prev + 1);
                    }
                }, 2000);
            }
            return () => clearInterval(interval);
        }, [isPlaying, currentWeekIndex, currentImageIndex, weeklyProgress.length]);

        const handleNext = () => {
            const currentWeek = weeklyProgress[currentWeekIndex];
            if (currentImageIndex === currentWeek?.images?.length - 1) {
                if (currentWeekIndex === weeklyProgress.length - 1) {
                    setCurrentWeekIndex(0);
                    setCurrentImageIndex(0);
                } else {
                    setCurrentWeekIndex(prev => prev + 1);
                    setCurrentImageIndex(0);
                }
            } else {
                setCurrentImageIndex(prev => prev + 1);
            }
        };

        const handlePrevious = () => {
            if (currentImageIndex === 0) {
                if (currentWeekIndex === 0) {
                    setCurrentWeekIndex(weeklyProgress.length - 1);
                    setCurrentImageIndex(weeklyProgress[weeklyProgress.length - 1]?.images?.length - 1 || 0);
                } else {
                    setCurrentWeekIndex(prev => prev - 1);
                    setCurrentImageIndex(weeklyProgress[currentWeekIndex - 1]?.images?.length - 1 || 0);
                }
            } else {
                setCurrentImageIndex(prev => prev - 1);
            }
        };

        const togglePlayPause = () => {
            setIsPlaying(!isPlaying);
        };

        if (!weeklyProgress.length) {
            return <div className="text-center p-4">No progress images available</div>;
        }

        const currentWeek = weeklyProgress[currentWeekIndex];
        const currentImage = currentWeek?.images?.[currentImageIndex];

        if (!currentWeek || !currentImage) {
            return <div className="text-center p-4">No images available for this week</div>;
        }

        return (
            <div style={{ maxWidth: '1000px', margin: '0 auto', padding: '1.5rem' }}>
                <div style={{ position: 'relative' }}>
                    <div style={{ position: 'relative', height: '500px', overflow: 'hidden', borderRadius: '0.5rem', background: '#f3f4f6' }}>
                        <img
                            src={currentImage.url}
                            alt={currentImage.description}
                            style={{
                                position: 'absolute',
                                width: '100%',
                                height: '100%',
                                objectFit: 'cover'
                            }}
                        />
                        
                        <button
                            onClick={handlePrevious}
                            style={{
                                position: 'absolute',
                                left: '1rem',
                                top: '50%',
                                transform: 'translateY(-50%)',
                                background: 'rgba(255, 255, 255, 0.9)',
                                width: '40px',
                                height: '40px',
                                borderRadius: '50%',
                                border: 'none',
                                cursor: 'pointer',
                                fontSize: '20px',
                                zIndex: 1
                            }}
                        >
                            ←
                        </button>
                        
                        <button
                            onClick={handleNext}
                            style={{
                                position: 'absolute',
                                right: '1rem',
                                top: '50%',
                                transform: 'translateY(-50%)',
                                background: 'rgba(255, 255, 255, 0.9)',
                                width: '40px',
                                height: '40px',
                                borderRadius: '50%',
                                border: 'none',
                                cursor: 'pointer',
                                fontSize: '20px',
                                zIndex: 1
                            }}
                        >
                            →
                        </button>
                    </div>

                    <div style={{
                        position: 'absolute',
                        bottom: 0,
                        left: 0,
                        right: 0,
                        background: 'rgba(0, 0, 0, 0.7)',
                        color: 'white',
                        padding: '1rem'
                    }}>
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                            <div>
                                <p style={{ fontSize: '1.125rem', fontWeight: 600, margin: 0 }}>
                                    Week {currentWeek.week} - Image {currentImageIndex + 1}/3
                                </p>
                                <p style={{ fontSize: '0.875rem', margin: '0.5rem 0 0 0' }}>
                                    {currentWeek.date}
                                </p>
                            </div>
                            <button
                                onClick={togglePlayPause}
                                style={{
                                    background: 'rgba(255, 255, 255, 0.2)',
                                    border: 'none',
                                    borderRadius: '50%',
                                    width: '40px',
                                    height: '40px',
                                    cursor: 'pointer',
                                    color: 'white',
                                    fontSize: '1.25rem'
                                }}
                            >
                                {isPlaying ? '⏸' : '▶'}
                            </button>
                        </div>
                        <p style={{ margin: '0.5rem 0 0 0' }}>{currentImage.description}</p>
                    </div>
                </div>

                <div style={{ marginTop: '1.5rem' }}>
                    <div style={{ display: 'flex', justifyContent: 'center', gap: '0.5rem' }}>
                        {weeklyProgress.map((week, index) => (
                            <button
                                key={index}
                                onClick={() => {
                                    setCurrentWeekIndex(index);
                                    setCurrentImageIndex(0);
                                }}
                                style={{
                                    padding: '0.5rem 1rem',
                                    borderRadius: '0.5rem',
                                    border: 'none',
                                    background: currentWeekIndex === index ? '#3b82f6' : '#f3f4f6',
                                    color: currentWeekIndex === index ? 'white' : 'black',
                                    cursor: 'pointer'
                                }}
                            >
                                Week {week.week}
                            </button>
                        ))}
                    </div>
                </div>
            </div>
        );
    };

    function showProgressViewer() {
        const modal = document.getElementById('progress-viewer-modal');
        modal.classList.add('show');
        
        ReactDOM.render(
            <BuildingProgressViewer />,
            document.getElementById('progress-viewer-root')
        );
    }

    function hideProgressViewer() {
        const modal = document.getElementById('progress-viewer-modal');
        modal.classList.remove('show');
    }
</script>
    <script>
    function previewImage(event, index) {
        const input = event.target;
        const preview = document.getElementById(`preview-${index}`);
        const uploadBox = input.closest('.custum-file-upload');
        const textElement = uploadBox.querySelector('div');

        const reader = new FileReader();
        reader.onload = function () {
            preview.src = reader.result;
            preview.style.display = 'block';
            // Hide the "Click to upload image" text
        if (textElement) {
            textElement.style.display = 'none';
        }
        };
        reader.readAsDataURL(input.files[0]);
    }


    // function openFullscreen(imageSrc) {
    //     const overlay = document.getElementById('fullscreen-overlay');
    //     const fullscreenImage = document.getElementById('fullscreen-image');
    //     fullscreenImage.src = imageSrc;
    //     overlay.style.display = 'flex';
    // }

        function openFullscreen(imageId) {
        const overlay = document.getElementById('fullscreen-overlay');
        const fullscreenImage = document.getElementById('fullscreen-image');

        // Use the correct URL to fetch the image
        const imageSrc = `?image_id=${imageId}`;
        fullscreenImage.src = imageSrc;

        overlay.style.display = 'flex';
    }


    function closeFullscreen() {
        const overlay = document.getElementById('fullscreen-overlay');
        overlay.style.display = 'none';
    }
</script>
  </body>

</html>