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

    <!-- Custom CSS -->
    <style>

main {
      padding: 2em;
      max-width: 800px;
      margin: 0 auto;
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
button {
      background: #007bff;
      color: white;
      border: none;
      padding: 0.8em 1.5em;
      cursor: pointer;
      font-size: 1em;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background: #0056b3;
    }

    .create-project {
      margin-bottom: 2em;
      text-align: center;
    }
    label {
      display: block;
      margin-bottom: 0.5em;
      font-weight: bold;
      color: #555;
    }
    .card {
  width: 600px; /* Adjust width as needed */
  border: 1px solid #ccc;
  border-radius: 4px;
  box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  margin: 10px auto;
}

.card-header {
  background-color: #333;
  padding: 16px;
  text-align: center;
}

.card-header .text-header {
  margin: 0;
  font-size: 18px;
  color: #fff;
}

.card-body {
  padding: 16px;
}




      body {
        font-family: "Roboto", sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
      }

      .header {
        background-color: rgba(255, 255, 255, 0.9);
        border-bottom: 1px solid #ddd;
        padding: 15px;
        position: fixed;
        width: 100%;
        z-index: 1000;
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
        display: flex;
  align-items: center;
  margin-bottom: 12px;
      }

.form-group {
  display: flex;
  align-items: center;
  margin-bottom: 12px;
}

.form-group label {
  width: 160px; /* Fixed width for consistent alignment */
  font-size: 16px;
  color: #333;
  font-weight: bold;
  margin-right: 10px; /* Spacing between label and input */
  text-align: right;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"],
.form-group input[type="number"],
.form-group input[type="date"] {
  flex: 1; /* Input takes the remaining space */
  padding: 8px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 4px;
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

      .btn {
  padding: 12px 24px;
  margin-top: 10px;
  font-size: 16px;
  border: none;
  border-radius: 4px;
  background-color: #333;
  color: #fff;
  text-transform: uppercase;
  transition: background-color 0.2s ease-in-out;
  cursor: pointer;
  width: 100%;
}

.btn:hover {
  background-color: #ccc;
  color: #333;
}
/* Hidden form */
.hidden {
  display: none;
}
.project-list h2 {
      margin-bottom: 1em;
      text-align: center;
      color: #555;
    }

    #projects {
      list-style: none;
      padding: 0;
    }

    #projects li {
          padding: 16px;
          width: 600px; /* Adjust width as needed */
          border: 1px solid #ccc;
          border-radius: 4px;
          box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
          overflow: hidden;
          margin: 10px auto;
          background-color: #fff; /* Ensure white background */
          text-align: center; /* Center-align text in the list item */
        }

        #projects li:hover {
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #projects strong1 {
          display: block; /* Makes it take the full width */
          background-color: #333; /* Dark background */
          color: #fff; /* White text */
          padding: 10px 16px; /* Adjust padding for spacing */
          text-align: center; /* Center-align text */
          font-size: 20px; /* Slightly larger font size */
          font-weight: bold; /* Bold font */
          border-radius: 4px 4px 0 0; /* Rounded corners at the top */
          margin: 0; /* No margin */
          overflow: hidden; /* Prevent text overflow */
        }

        #projects li strong {
  margin: 8px 0; /* Adds spacing between strong elements */
  font-weight: bold; /* Keeps text bold */
  line-height: 1.6; /* Adjusts line spacing inside the strong tag */
}



    @media (max-width: 600px) {
      main {
        padding: 1em;
      }

      button {
        padding: 0.6em 1em;
        font-size: 0.9em;
      }
    }

    .botton {
  position: relative;
  border: none;
  background: transparent;
  padding: 0;
  outline: none;
  cursor: pointer;
  font-family: sans-serif;
  margin: 20px 20px;
}

/* Shadow layer */
.botton .shadow {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.25);
  border-radius: 8px;
  transform: translateY(2px);
  transition: transform 600ms cubic-bezier(0.3, 0.7, 0.4, 1);
}

/* Edge layer */
.botton .edge {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 8px;
  background: linear-gradient(
    to left,
    hsl(217, 33%, 16%) 0%,
    hsl(217, 33%, 32%) 8%,
    hsl(217, 33%, 32%) 92%,
    hsl(217, 33%, 16%) 100%
  );
}

/* Front layer */
.botton .front {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 12px 28px;
  font-size: 1.25rem;
  color: white;
  background: hsl(217, 33%, 17%);
  border-radius: 8px;
  transform: translateY(-4px);
  transition: transform 600ms cubic-bezier(0.3, 0.7, 0.4, 1);
}

/* Hover and active states */
.botton:hover .shadow {
  transform: translateY(4px);
  transition: transform 250ms cubic-bezier(0.3, 0.7, 0.4, 1.5);
}

.botton:hover .front {
  transform: translateY(-6px);
  transition: transform 250ms cubic-bezier(0.3, 0.7, 0.4, 1.5);
}

.botton:active .shadow {
  transform: translateY(1px);
  transition: transform 34ms;
}

.botton:active .front {
  transform: translateY(-2px);
  transition: transform 34ms;
}

/* Disable text selection */
.botton .front span {
  user-select: none;
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

      @keyframes progress {
            0% { --percentage: 0; }
            100% { --percentage: var(--value); }
        }

        @property --percentage {
            syntax: '<number>';
            inherits: true;
            initial-value: 0;
        }

        [role="progressbar"] {
            --percentage: var(--value);
            --primary: #333;
            --secondary: rgb(194, 195, 196);
            --size: 120px; /* Adjust size as needed */
            animation: progress 2s 0.5s forwards;
            width: var(--size);
            aspect-ratio: 1;
            border-radius: 50%;
            position: relative;
            overflow: hidden;
            display: grid;
            place-items: center;
            margin: 10px auto; /* Center align */
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
        <div class="page-title">
        <div class="container">
          <h1>Project Manager</h1>
        </div>
      </div>

      <main>
    <section class="project-list">
    <div class="seven">
    <h1>Existing Projects</h1>
  </div>
      <ul id="projects">
        <?php
          // Database connection
          $host = "localhost";
          $username = "shantanu";
          $password = "11qqaazz";
          $dbname = "eGatividhi";
          $conn = new mysqli($host, $username, $password, $dbname);

          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }

          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $project_id = $_POST["project_id"];
            $project_name = $_POST["project_name"];
            $sector = $_POST["sector"];
            $sub_sector = $_POST["sub_sector"];
            $location = $_POST["location"];
            $start_date = $_POST["start_date"];
            $completion_date = $_POST["completion_date"];
            $project_manager = $_POST["project_manager"];
            $project_contractor = $_POST["project_contractor"];
            $total_cost = $_POST["total_cost"];
            $duration = (strtotime($completion_date) - strtotime($start_date)) / (60 * 60 * 24) + 1;
        
            // Check if Project ID already exists
            $check_sql = "SELECT project_id FROM projects WHERE project_id = ?";
            $stmt = $conn->prepare($check_sql);
            $stmt->bind_param("i", $project_id);
            $stmt->execute();
            $stmt->store_result();
        
            if ($stmt->num_rows > 0) {
                echo "<li style='color: red;'>Error: Project ID $project_id already exists!</li>";
            } else {
                // Insert into the database
                $insert_sql = "INSERT INTO projects (project_id, project_name, sector, sub_sector, location, start_date, completion_date, project_manager, project_contractor, total_cost, duration) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("isssssssssi", $project_id, $project_name, $sector, $sub_sector, $location, $start_date, $completion_date, $project_manager, $project_contractor, $total_cost, $duration);
        
                if ($stmt->execute()) {
                    echo "<li>Project added successfully!</li>";
                } else {
                    echo "<li>Error: " . $conn->error . "</li>";
                }
            }
            $stmt->close();
        }

          // Fetch project details and calculate uploaded images
$sql = "SELECT p.project_id, p.project_name, p.project_manager, p.sector, 
p.location, p.duration, 
COUNT(i.id) AS uploaded_images 
FROM projects p 
LEFT JOIN project_images i ON p.project_id = i.project_id 
GROUP BY p.project_id";
$result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Calculate progress percentage
            $duration = $row['duration'];
            $uploadedImages = $row['uploaded_images'];
            $progress = $duration > 0 ? ($uploadedImages / $duration) * 100 : 0;

            echo "<li>
                    <a href='project.php?project_id=" . $row["project_id"] . "' style='text-decoration: none; color: inherit;'>
                        <strong1>" . $row["project_name"] . "</strong1> <br>
                        <strong>Project ID :</strong> " . $row["project_id"] . "<br>
                        <strong>Project Manager :</strong> " . $row["project_manager"] . "<br>
                        <strong>Sector :</strong> " . $row["sector"] . "<br>
                        <strong>Location :</strong> " . $row["location"] . "<br>
                        <strong>Duration :</strong> " . $row["duration"] . " days<br>
                    </a>
                    <div role='progressbar' aria-valuenow='" . round($progress) . "' aria-valuemin='0' aria-valuemax='100' style='--value: " . round($progress) . "'></div>
                </li>";
        }
    } else {
        echo "<li>No projects found.</li>";
    }
    $conn->close();
        ?>
      </ul>
    </section>
  </main>

     


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

    <script>
    document.addEventListener("DOMContentLoaded", () => {
    const createProjectBtn = document.getElementById("create-project-btn");
    const projectForm = document.getElementById("project-form");
    const projectIdInput = document.getElementById("projectId");
    const projectFormButton = document.querySelector(".submit-btn");

    // Toggle form visibility
    createProjectBtn.addEventListener("click", () => {
      projectForm.classList.toggle("hidden");
    });

    // Validate Project ID (integer check)
    projectIdInput.addEventListener("input", () => {
      const value = projectIdInput.value;
      if (!/^\d*$/.test(value)) {
        projectIdInput.value = value.replace(/\D/g, ""); // Remove non-digit characters
        alert("Project ID must be an integer.");
      }
    });
  });
</script>
  </body>
</html>