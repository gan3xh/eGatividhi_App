from flask import Flask, request, render_template, send_file, jsonify
from werkzeug.utils import secure_filename
import cv2
import numpy as np
import os
from io import BytesIO
from PIL import Image
from skimage.metrics import structural_similarity as ssim
import base64
import requests

app = Flask(__name__)

# Configurations
UPLOAD_FOLDER = 'uploads/'
RESULT_FOLDER = 'results/'
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg'}
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
app.config['RESULT_FOLDER'] = RESULT_FOLDER

# Ensure upload and result folders exist
os.makedirs(UPLOAD_FOLDER, exist_ok=True)
os.makedirs(RESULT_FOLDER, exist_ok=True)

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def ResizeWithAspectRatio(image, width=None, height=None, inter=cv2.INTER_AREA):
    dim = None
    (h, w) = image.shape[:2]
    if width is None and height is None:
        return image
    if width is None:
        r = height / float(h)
        dim = (int(w * r), height)
    else:
        r = width / float(w)
        dim = (width, int(h * r))
    return cv2.resize(image, dim, interpolation=inter)

def stitchImages(img1, img2, H):
    rows1, cols1 = img1.shape[:2]
    rows2, cols2 = img2.shape[:2]

    list_of_points_1 = np.float32([[0,0], [0, rows1],[cols1, rows1], [cols1, 0]]).reshape(-1, 1, 2)
    temp_points = np.float32([[0,0], [0,rows2], [cols2,rows2], [cols2,0]]).reshape(-1,1,2)

    list_of_points_2 = cv2.perspectiveTransform(temp_points, H)

    list_of_points = np.concatenate((list_of_points_1,list_of_points_2), axis=0)

    [x_min, y_min] = np.int32(list_of_points.min(axis=0).ravel() - 0.5)
    [x_max, y_max] = np.int32(list_of_points.max(axis=0).ravel() + 0.5)

    translation_dist = [-x_min,-y_min]

    H_translation = np.array([[1, 0, translation_dist[0]], [0, 1, translation_dist[1]], [0, 0, 1]])

    stitched_img = cv2.warpPerspective(img2, H_translation.dot(H), (x_max-x_min, y_max-y_min))

    img2only = stitched_img.copy()
    stitched_img[translation_dist[1]:rows1+translation_dist[1], translation_dist[0]:cols1+translation_dist[0]] = img1

    img2_binary = np.ones(img2.shape[:2])
    img2_binary = cv2.warpPerspective(img2_binary, H_translation.dot(H), (x_max-x_min, y_max-y_min))

    img1only = np.zeros((y_max-y_min, x_max-x_min, 3), dtype=np.uint8)
    img1only[translation_dist[1]:rows1+translation_dist[1], translation_dist[0]:cols1+translation_dist[0]] = img1

    img1_binary = np.zeros((y_max-y_min, x_max-x_min))
    img1_binary[translation_dist[1]:rows1+translation_dist[1], translation_dist[0]:cols1+translation_dist[0]] = 1

    return stitched_img, img1_binary, img1only, img2_binary, img2only

def imageAlignment(img1, img2, nfeatures, goodMatchPercent):
    img1Gray = cv2.cvtColor(img1, cv2.COLOR_RGB2GRAY)
    img2Gray = cv2.cvtColor(img2, cv2.COLOR_RGB2GRAY)

    orb = cv2.ORB_create(nfeatures)

    reference_keypoints, reference_descriptor = orb.detectAndCompute(img1Gray, None)
    toAlign_keypoints, toAlign_descriptor = orb.detectAndCompute(img2Gray, None)

    bf = cv2.BFMatcher(cv2.NORM_HAMMING, crossCheck=True)

    matches = bf.match(toAlign_descriptor, reference_descriptor)
    matches = sorted(matches, key=lambda x: x.distance)

    numGoodMatches = int(len(matches) * goodMatchPercent)
    matches = matches[:numGoodMatches]

    reference_points = np.zeros((len(matches), 2), dtype=np.float32)
    toAlign_points = np.zeros((len(matches), 2), dtype=np.float32)

    for (i, match) in enumerate(matches):
        reference_points[i] = reference_keypoints[match.trainIdx].pt
        toAlign_points[i] = toAlign_keypoints[match.queryIdx].pt

    homography, _ = cv2.findHomography(toAlign_points, reference_points, cv2.RANSAC)

    height, width, _ = img1.shape
    alignedImg = cv2.warpPerspective(img2, homography, (width, height))

    stitched_img, img1_binary, img1only, alignedImg_binary, alignedImgOnly = stitchImages(img1, img2, homography)

    return img1only, alignedImgOnly

def SSIMandDiff(img1only_overlapped, alignedImg_overlapped, winSize):
    img1HSV = cv2.cvtColor(img1only_overlapped, cv2.COLOR_RGB2HSV)
    _, _, img1Gray = cv2.split(img1HSV)

    alignedImgHSV = cv2.cvtColor(alignedImg_overlapped, cv2.COLOR_RGB2HSV)
    _, _, alignedImgGray = cv2.split(alignedImgHSV)

    img1Blur = cv2.blur(img1Gray, winSize)
    alignedImgBlur = cv2.blur(alignedImgGray, winSize)

    (ssim_score, SSIMimg) = ssim(img1Blur, alignedImgBlur, full=True)
    SSIMimg = (SSIMimg * 255).astype("uint8")

    return ssim_score, SSIMimg

def estimate_progress(ssim_score):
    progress = (1 - ssim_score) * 100
    return min(max(progress, 0), 100)

def processDifferences(img1only_overlapped, alignedImg_overlapped, imageWidth, SSIMimg, resizeFactor, winSize):
    SSIMimg = cv2.medianBlur(SSIMimg, winSize[0])

    _, diffBinary = cv2.threshold(SSIMimg, 128, 255, cv2.THRESH_BINARY_INV)

    diffContours, _ = cv2.findContours(diffBinary.copy(), cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    mask = np.zeros(alignedImg_overlapped.shape[:2], dtype='uint8')
    blueDifferencesOnImg = alignedImg_overlapped.copy()

    min_area = (imageWidth * imageWidth) / (2000 * resizeFactor)

    for c in diffContours:
        area = cv2.contourArea(c)
        if area > min_area:
            cv2.drawContours(mask, [c], 0, 255, -1)
            cv2.drawContours(blueDifferencesOnImg, [c], 0, (0, 0, 255), 2)

    blueDifferencesOnImg = cv2.addWeighted(blueDifferencesOnImg, 0.7, cv2.cvtColor(mask, cv2.COLOR_GRAY2RGB), 0.3, 0)

    h, w = img1only_overlapped.shape[:2]
    result_resized = np.zeros((h, w, 3), dtype=np.uint8)

    center_x = (w - blueDifferencesOnImg.shape[1]) // 2
    center_y = (h - blueDifferencesOnImg.shape[0]) // 2

    result_resized[center_y:center_y + blueDifferencesOnImg.shape[0], center_x:center_x + blueDifferencesOnImg.shape[1]] = blueDifferencesOnImg

    total_pixels = mask.size
    changed_pixels = cv2.countNonZero(mask)
    change_percentage = (changed_pixels / total_pixels) * 100

    return result_resized, change_percentage

def call_llava_model(image_path):
    # This is a placeholder function. You'll need to implement the actual API call to LLaVA
    # For demonstration, we'll use a mock response
    mock_response = {
        "progress_description": "The image shows significant progress in the construction. The building structure is now visible with completed walls and roof framing.",
        "construction_level": "Mid-stage",
        "construction_stage": "Structural completion"
    }
    return mock_response

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/upload', methods=['POST'])
def upload_images():
    if 'image1' not in request.files or 'image2' not in request.files:
        return jsonify({'error': 'Missing image files'}), 400

    image1 = request.files['image1']
    image2 = request.files['image2']

    if not allowed_file(image1.filename) or (image2.filename and not allowed_file(image2.filename)):
        return jsonify({'error': 'Invalid file format'}), 400

    image1_filename = secure_filename(image1.filename)
    image2_filename = secure_filename(image2.filename)

    image1_path = os.path.join(app.config['UPLOAD_FOLDER'], image1_filename)
    image2_path = os.path.join(app.config['UPLOAD_FOLDER'], image2_filename)

    image1.save(image1_path)
    if image2.filename:
        image2.save(image2_path)

    img1 = cv2.imread(image1_path)
    img1 = ResizeWithAspectRatio(img1, width=1600)
    img2 = cv2.imread(image2_path)

    if img2 is None:
        return jsonify({'error': 'Second image is not available'}), 400

    img2 = ResizeWithAspectRatio(img2, width=1600)

    img1only, alignedImgonly = imageAlignment(img1, img2, nfeatures=500, goodMatchPercent=0.15)

    ssim_score, SSIMimg = SSIMandDiff(img1only, alignedImgonly, (5, 5))

    blueDifferencesOnImg, change_percentage = processDifferences(img1only, alignedImgonly, 1600, SSIMimg, 0.7, (5, 5))

    progress_estimation = estimate_progress(ssim_score)

    # Call LLaVA model for the second image
    llava_results = call_llava_model(image2_path)

    _, img_encoded = cv2.imencode('.jpg', blueDifferencesOnImg)
    
    return jsonify({
        'ssim_score': ssim_score,
        'change_percentage': change_percentage,
        'progress_estimation': progress_estimation,
        'image': base64.b64encode(img_encoded).decode('utf-8'),
        'llava_description': llava_results['progress_description'],
        'construction_level': llava_results['construction_level'],
        'construction_stage': llava_results['construction_stage']
    })

if __name__ == '__main__':
    app.run(debug=True)