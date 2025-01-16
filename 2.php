<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // Check if files were uploaded
    if (!isset($_FILES['image1']) || !isset($_FILES['image2'])) {
        throw new Exception('Please upload both images');
    }

    // Validate uploaded files
    $image1 = $_FILES['image1'];
    $image2 = $_FILES['image2'];
    
    if ($image1['error'] !== UPLOAD_ERR_OK || $image2['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error uploading files');
    }

    // Process the images here
    // ... your image processing code ...
    
    $ssim_score = 0.85; // Example value
    $change_percentage = 25.5;
    $progress_estimation = 30.0;
    $llava_description = "Construction progress visible with new structures";
    $construction_level = "Phase 2";
    $construction_stage = "Foundation Complete";
    
    // Return the results
    $response = array(
        'status' => 'success',
        'ssim_score' => $ssim_score,
        'change_percentage' => $change_percentage,
        'progress_estimation' => $progress_estimation,
        'llava_description' => $llava_description,
        'construction_level' => $construction_level,
        'construction_stage' => $construction_stage,
        'image' => base64_encode($processed_image) // Your processed image
    );
    
    echo json_encode($response);
    exit();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        'status' => 'error',
        'message' => $e->getMessage()
    ));
    exit();
}
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

    <!-- Custom CSS -->
    <style>
      body {
        font-family: "Roboto", sans-serif;
        margin: 0;
        padding: 20px 0;
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

      .main {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
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
        object-fit: contain;
      }

      #resultImage {
        max-width: 100%;
        margin-top: 20px;
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

      .error-message {
        color: #dc3545;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #dc3545;
        border-radius: 5px;
        background-color: #f8d7da;
      }
    </style>
  </head>

  <body>
    <div class="container">
      <main class="main">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <form
              id="uploadForm"
              action="/upload"
              method="POST"
              enctype="multipart/form-data"
              class="mb-5"
            >
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="imageUpload1" class="form-label"
                      >Upload First Construction Site Image</label
                    >
                    <input
                      type="file"
                      class="form-control"
                      id="imageUpload1"
                      name="image1"
                      accept="image/*"
                      required
                    />
                    <img
                      id="preview1"
                      class="image-preview"
                      src="#"
                      alt="Image preview"
                      style="display: none"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="imageUpload2" class="form-label"
                      >Upload Second Construction Site Image</label
                    >
                    <input
                      type="file"
                      class="form-control"
                      id="imageUpload2"
                      name="image2"
                      accept="image/*"
                      required
                    />
                    <img
                      id="preview2"
                      class="image-preview"
                      src="#"
                      alt="Image preview"
                      style="display: none"
                    />
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary w-100">
                Detect Progress
              </button>
            </form>

            <div id="results" class="text-center" style="display: none">
              <h3>Detection Results</h3>
              <div id="resultContent"></div>
              <img id="resultImage" src="#" alt="Processed Image" />
              <div id="technicalDetails">
                <h4>Technical Details</h4>
                <p>
                  <strong>SSIM Score:</strong> <span id="ssimScore">-</span>
                </p>
                <p>
                  <strong>Change Percentage:</strong>
                  <span id="changePercentage">-</span>%
                </p>
                <p>
                  <strong>Estimated Progress:</strong>
                  <span id="progressEstimation">-</span>%
                </p>
                <h4>AI Analysis & Detection</h4>
                <p>
                  <strong>Progress Description:</strong>
                  <span id="progressDescription">-</span>
                </p>
                <p>
                  <strong>Construction Level:</strong>
                  <span id="constructionLevel">-</span>
                </p>
                <p>
                  <strong>Construction Stage:</strong>
                  <span id="constructionStage">-</span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for image preview and form submission -->
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const uploadForm = document.getElementById("uploadForm");
        const resultsDiv = document.getElementById("results");
        const resultContent = document.getElementById("resultContent");
        const imageUpload1 = document.getElementById("imageUpload1");
        const imageUpload2 = document.getElementById("imageUpload2");
        const preview1 = document.getElementById("preview1");
        const preview2 = document.getElementById("preview2");
        const resultImage = document.getElementById("resultImage");

        // Preview function
        function previewImage(input, preview) {
          const file = input.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
              preview.src = e.target.result;
              preview.style.display = "block";
            };
            reader.readAsDataURL(file);
          }
        }

        imageUpload1.addEventListener("change", function () {
          previewImage(this, preview1);
        });

        imageUpload2.addEventListener("change", function () {
          previewImage(this, preview2);
        });

        // Handle form submission
        uploadForm.addEventListener("submit", function (event) {
          event.preventDefault();
          const formData = new FormData(uploadForm);

          // Show loading state
          resultContent.innerHTML = '<p class="text-info">Processing images...</p>';
          resultsDiv.style.display = "block";

          fetch(uploadForm.action, {
            method: "POST",
            body: formData,
          })
            .then((response) => {
              if (!response.ok) {
                return response.json().then((error) => {
                  throw new Error(error.error || "Unknown error occurred");
                });
              }
              return response.json();
            })
            .then((data) => {
              // Clear any previous error messages
              resultContent.innerHTML = "";
              
              // Update the results
              resultImage.src = "data:image/jpeg;base64," + data.image;
              resultImage.style.display = "block";
              
              document.getElementById("ssimScore").textContent =
                data.ssim_score.toFixed(4);
              document.getElementById("changePercentage").textContent =
                data.change_percentage.toFixed(2);
              document.getElementById("progressEstimation").textContent =
                data.progress_estimation.toFixed(2);
              document.getElementById("progressDescription").textContent =
                data.llava_description || "-";
              document.getElementById("constructionLevel").textContent =
                data.construction_level || "-";
              document.getElementById("constructionStage").textContent =
                data.construction_stage || "-";
            })
            .catch((error) => {
              console.error("Error:", error);
              resultContent.innerHTML = `
                <div class="error-message">
                  Error: ${error.message}
                </div>`;
            });
        });
      });

      fetch(uploadForm.action, {
    method: "POST",
    body: formData,
})
.then((response) => {
    // First check the content type
    const contentType = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
        throw new Error('Server returned invalid content type. Expected JSON.');
    }
    return response.json();
})
.catch((error) => {
    console.error("Error:", error);
    resultContent.innerHTML = `
        <div class="alert alert-danger">
            Server Error: ${error.message}. Please try again or contact support if the issue persists.
        </div>
    `;
});

uploadForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(uploadForm);
    
    // Log the form data
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    fetch(uploadForm.action, {
        method: "POST",
        body: formData,
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        // ... rest of your code ...
    })
    .catch(error => {
        console.error('Error:', error);
        resultContent.innerHTML = `
            <div class="alert alert-danger">
                ${error.message}
            </div>
        `;
    });
});
uploadForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(uploadForm);
    
    // Debug logging
    console.log('Image 1:', imageUpload1.files[0]);
    console.log('Image 2:', imageUpload2.files[0]);
    
    // Validate files before submission
    if (!imageUpload1.files[0] || !imageUpload2.files[0]) {
        resultContent.innerHTML = `
            <div class="alert alert-danger">
                Please select both images before submitting.
            </div>
        `;
        return;
    }

    // Add files to FormData
    formData.append('image1', imageUpload1.files[0]);
    formData.append('image2', imageUpload2.files[0]);

    // Show loading state
    resultContent.innerHTML = '<p class="text-info">Processing images...</p>';
    resultsDiv.style.display = "block";

    fetch(uploadForm.action, {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'error') {
            throw new Error(data.message);
        }
        // Update the results as before
        resultContent.innerHTML = "";
        // ... rest of your success handling code ...
    })
    .catch(error => {
        console.error('Error:', error);
        resultContent.innerHTML = `
            <div class="alert alert-danger">
                ${error.message}
            </div>
        `;
    });
});
    </script>
  </body>
</html>