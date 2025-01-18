
<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect to the l page
if (!isset($_SESSION['admin_username'])) {
    header("Location:index.php");
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Canvas with Editable Text, Images, and Export</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Canvas container with A4 landscape size */
    #canvas-container {
      width: 297mm;
      height: 210mm;
      background-color: white;
      border: 1px solid #000;
      position: relative;
      overflow: hidden;
    }

    /* Basic text element styles */
    .editable-text {
      position: absolute;
      cursor: move;
      user-select: none;
      padding: 5px;
      display: inline-block;
    }

    /* Image element styles */
    .draggable-image {
      position: absolute;
      cursor: move;
    }

    /* Resize handle for images */
    .resize-handle {
      width: 10px;
      height: 10px;
      background-color: #000;
      position: absolute;
      right: -5px;
      bottom: -5px;
      cursor: se-resize;
    }

    /* Font options */
    .text-options {
      margin-top: 20px;
    }

    /* Background options */
    #backgroundUploader {
      margin-top: 20px;
    }
    #thumbnail-sidebar {
      background-color: #f8f9fa;
      padding: 10px;
      border-right: 1px solid #ddd;
      max-height: 100%; /* Set a max height for the sidebar */
      overflow-y: auto; /* Enable vertical scrolling */
    }

    .thumbnail-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .thumbnail {
      width: 100%;
      cursor: pointer;
      border: 1px solid #ddd;
      border-radius: 5px;
      transition: transform 0.2s ease;
    }

    .thumbnail:hover {
      transform: scale(1.05);
    }


  </style>
</head>
<body>

<!-- Slider -->

<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/cultural5.gif" class="d-block w-100" alt="...">
        </div>


    </div>
</div>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar with thumbnails for background images -->
    <div class="col-md-2" id="thumbnail-sidebar">
      <h5>Select Background</h5>
      <div class="thumbnail-list">
        <img src="img/template/1.png" class="thumbnail" alt="Image 1" />
        <img src="img/template/2.png" class="thumbnail" alt="Image 2" />
        <img src="img/template/3.png" class="thumbnail" alt="Image 3" />
          <img src="img/template/4.png" class="thumbnail" alt="Image 4" />
            <img src="img/template/5.png" class="thumbnail" alt="Image 5" />
        <!-- Add more thumbnails as needed -->
      </div>
    </div>

  <div class="container">
    <div class="row">
      <div class="col">
          <div class="container mt-4">
            <h2>Create Digital Certificate</h2>
              </div>
              </div>
            </div>

        <div class="row">
              <div class="col">
            <!-- Text and image options for customization -->
              <a href='adminpage.php' class='btn btn-primary'>Go Back</a>
              <button class="btn btn-info" id="addTextBtn">Add Text</button>
              <button class="btn btn-info" id="addImageBtn">Add Image</button>
              <!-- Hidden File -->
              <input type="file" id="backgroundImage" accept="image/*" class="d-none" />
              <!-- Button to trigger file input -->
          <button class="btn btn-info" id="uploadBackgroundBtn">Upload Background</button>



        <!-- Button to delete the selected text or image -->
        <button class="btn btn-danger" id="deleteElementBtn">Delete Selected Element</button>

        <button class="btn btn-warning" id="deleteBackgroundBtn">Delete Background</button>
        <button class="btn btn-warning" id="resetCanvasBtn">Reset Canvas</button>
        <!-- Export Button -->
        <button class="btn btn-success" id="exportCanvasBtn">Export to PNG</button>
      </div>
      </div>

  <div class="row">
    <div class="col">

    <!-- Font Family dropdown with more options -->


    <p> Font Family</p><select class="form-control w-25" id="fontFamily">
      <option value="Arial">Arial</option>
      <option value="Courier New">Courier New</option>
      <option value="Georgia">Georgia</option>
      <option value="Times New Roman">Times New Roman</option>
      <option value="Verdana">Verdana</option>
      <option value="Tahoma">Tahoma</option>
      <option value="Trebuchet MS">Trebuchet MS</option>
      <option value="Impact">Impact</option>
      <option value="Comic Sans MS">Comic Sans MS</option>
    </select>
  </div>
  <div class="col">
      <p> Font Color</p>
    <!-- Font Color Picker -->
    <input type="color" id="fontColor" value="#000000" />
  </div>
  <div class="col">
      <p> Font Size</p>
    <!-- Font Size input -->
    <input type="number" id="fontSize" value="20" min="1" max="100" />
  </div>
    <div class="col">
      <p> Font Style</p>
    <!-- Font Style dropdown (including Bold) -->
    <select class="form-control w-25" id="fontStyle">
      <option value="normal">Normal</option>
      <option value="italic">Italic</option>
      <option value="bold">Bold</option>
    </select>
  </div>




  </div>



  <!-- Background options -->
  <div id="backgroundUploader">



  </div>



  <!-- The canvas container -->
  <div id="canvas-container"></div>

  <!-- Hidden canvas for exporting -->
  <canvas id="exportCanvas" style="display: none;"></canvas>




</div>

</div>
<!-- Include JS libraries -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.0/interact.min.js"></script>

<script>
  let elements = []; // Store all text and images added to the canvas
  let selectedElement = null; // Track the currently selected element

  // Add text to the canvas container
  document.getElementById('addTextBtn').addEventListener('click', function() {
    let text = prompt('Enter text:');
    if (text) {
      const textElement = document.createElement('div');
      textElement.classList.add('editable-text');
      textElement.contentEditable = true;
      textElement.style.fontFamily = 'Arial';
      textElement.style.fontSize = '20px';
      textElement.style.color = '#000000';
      textElement.textContent = text;

      // Position the text element at a random location inside the canvas
      textElement.style.top = '50px'; // Starting position (can be randomized)
      textElement.style.left = '50px'; // Starting position (can be randomized)

      document.getElementById('canvas-container').appendChild(textElement);
      elements.push(textElement);

      // Make the text draggable and add selection event
      interact(textElement).draggable({
        onstart(event) {
          selectedElement = event.target;
        },
        onmove(event) {
          const textElement = event.target;
          textElement.style.left = `${parseFloat(textElement.style.left) + event.dx}px`;
          textElement.style.top = `${parseFloat(textElement.style.top) + event.dy}px`;
        },
        onend(event) {
          selectedElement = null;
        }
      });

      // Handle editing and selecting text
      textElement.addEventListener('focus', function() {
        selectedElement = textElement;
      });

      textElement.addEventListener('click', function() {
        selectedElement = textElement;
      });

      // Update font size, color, and style
      document.getElementById('fontFamily').addEventListener('change', function() {
        if (selectedElement) {
          selectedElement.style.fontFamily = this.value;
        }
      });

      document.getElementById('fontSize').addEventListener('input', function() {
        if (selectedElement) {
          selectedElement.style.fontSize = `${this.value}px`;
        }
      });

      document.getElementById('fontColor').addEventListener('input', function() {
        if (selectedElement) {
          selectedElement.style.color = this.value;
        }
      });

      document.getElementById('fontStyle').addEventListener('change', function() {
        if (selectedElement) {
          selectedElement.style.fontWeight = (this.value === 'bold') ? 'bold' : 'normal';
          selectedElement.style.fontStyle = (this.value === 'italic') ? 'italic' : 'normal';
        }
      });
    }
  });

  // Add image to the canvas container
  document.getElementById('addImageBtn').addEventListener('click', function() {
    let fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.click();

    fileInput.addEventListener('change', function(event) {
      const file = event.target.files[0];
      const reader = new FileReader();

      reader.onload = function(e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.classList.add('draggable-image');
        img.style.width = '100px'; // Set initial width
        img.style.height = 'auto'; // Maintain aspect ratio

        // Position the image at a random location inside the canvas
        img.style.top = '50px';
        img.style.left = '50px';

        document.getElementById('canvas-container').appendChild(img);
        elements.push(img);

        // Make the image draggable and add selection event
        interact(img).draggable({
          onstart(event) {
            selectedElement = event.target;
          },
          onmove(event) {
            const imgElement = event.target;
            imgElement.style.left = `${parseFloat(imgElement.style.left) + event.dx}px`;
            imgElement.style.top = `${parseFloat(imgElement.style.top) + event.dy}px`;
          },
          onend(event) {
            selectedElement = null;
          }
        });

        // Make the image resizable
        interact(img).resizable({
          edges: { bottom: true, right: true },
          onstart(event) {
            selectedElement = event.target;
          },
          onmove(event) {
            const imgElement = event.target;
            imgElement.style.width = `${event.rect.width}px`;
            imgElement.style.height = `${event.rect.height}px`;
          },
          onend(event) {
            selectedElement = null;
          }
        });

        img.addEventListener('click', function() {
          selectedElement = img;
        });
      };
      reader.readAsDataURL(file);
    });
  });

  // Delete selected element
  document.getElementById('deleteElementBtn').addEventListener('click', function() {
    if (selectedElement) {
      selectedElement.remove();
      elements = elements.filter(el => el !== selectedElement);
      selectedElement = null;
    }
  });

  // Delete background image
  document.getElementById('deleteBackgroundBtn').addEventListener('click', function() {
    document.getElementById('canvas-container').style.backgroundImage = '';
  });

  // Reset canvas (clear everything)
  document.getElementById('resetCanvasBtn').addEventListener('click', function() {
    elements.forEach(element => element.remove());
    elements = [];
    document.getElementById('canvas-container').style.backgroundImage = '';
  });

  // Export to PNG
  document.getElementById('exportCanvasBtn').addEventListener('click', function() {
    const exportCanvas = document.getElementById('exportCanvas');
    const ctx = exportCanvas.getContext('2d');
    exportCanvas.width = 297 * 3.7795275591; // A4 width in px (297mm * 3.779)
    exportCanvas.height = 210 * 3.7795275591; // A4 height in px (210mm * 3.779)

    // Clear the canvas
    ctx.clearRect(0, 0, exportCanvas.width, exportCanvas.height);

    // Draw background if any
    const bgImage = document.getElementById('canvas-container').style.backgroundImage;
    if (bgImage) {
      const img = new Image();
      img.onload = function() {
        ctx.drawImage(img, 0, 0, exportCanvas.width, exportCanvas.height);

        // Draw each element (text or image) on the canvas
        elements.forEach(function(element) {
          if (element.tagName === 'DIV') { // Text
            ctx.font = `${element.style.fontSize} ${element.style.fontFamily}`;
            ctx.fillStyle = element.style.color;
            ctx.fillText(element.textContent, parseFloat(element.style.left), parseFloat(element.style.top) + parseFloat(element.style.fontSize));
          } else if (element.tagName === 'IMG') { // Image
            ctx.drawImage(element, parseFloat(element.style.left), parseFloat(element.style.top), element.width, element.height);
          }
        });

        // Export to PNG
        const dataURL = exportCanvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.href = dataURL;
        link.download = 'canvas-export.png';
        link.click();
      };
      img.src = bgImage.replace('url(', '').replace(')', '').replace(/"/g, '');
    } else {
      // Draw each element (text or image) on the canvas
      elements.forEach(function(element) {
        if (element.tagName === 'DIV') { // Text
          ctx.font = `${element.style.fontSize} ${element.style.fontFamily}`;
          ctx.fillStyle = element.style.color;
          ctx.fillText(element.textContent, parseFloat(element.style.left), parseFloat(element.style.top) + parseFloat(element.style.fontSize));
        } else if (element.tagName === 'IMG') { // Image
          ctx.drawImage(element, parseFloat(element.style.left), parseFloat(element.style.top), element.width, element.height);
        }
      });

      // Export to PNG
      const dataURL = exportCanvas.toDataURL('image/png');
      const link = document.createElement('a');
      link.href = dataURL;
      link.download = 'canvas-export.png';
      link.click();
    }
  });
  // Trigger the hidden file input when the button is clicked
  document.getElementById('uploadBackgroundBtn').addEventListener('click', function() {
    document.getElementById('backgroundImage').click();
  });

  // Handle the file selection and set it as the background
  document.getElementById('backgroundImage').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
      document.getElementById('canvas-container').style.backgroundImage = `url(${e.target.result})`;
      document.getElementById('canvas-container').style.backgroundSize = 'cover';
    };
    reader.readAsDataURL(file);
  });

  // Upload background image
  document.getElementById('backgroundImage').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
      document.getElementById('canvas-container').style.backgroundImage = `url(${e.target.result})`;
      document.getElementById('canvas-container').style.backgroundSize = 'cover';
    };
    reader.readAsDataURL(file);
  });

  document.querySelectorAll('.thumbnail').forEach(thumbnail => {
  thumbnail.addEventListener('click', function() {
    const imageUrl = thumbnail.src; // Get the image source URL
    document.getElementById('canvas-container').style.backgroundImage = `url(${imageUrl})`;
    document.getElementById('canvas-container').style.backgroundSize = 'cover';
  });
});

</script>

</body>
</html>
