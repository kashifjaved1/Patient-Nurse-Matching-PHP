<?php session_start(); error_reporting(0); $cnic = $_SESSION['cnic']; unset($_SESSION['cnic'])?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Nurse - Document Upload</title>
   <!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
   <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
   <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
   <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>
    <center><h3 style="margin-top: 5%;">Document Upload</h3><br>
    <form action="../backend/func.php" method="post" enctype="multipart/form-data" class="mb-3" style="width: 80%;">
        <div class="custom-file">
            <input type="file" name="fileUpload[]" class="custom-file-input" id="chooseFile" multiple>
            <label class="custom-file-label" for="chooseFile">Select file(s)</label>
        </div>
        <input type="hidden" name="n_cnic" value="<?php echo $cnic ?>">
        <button type="submit" name="doc_upload" class="btn btn-primary btn-block mt-4">
            Upload
        </button>
    </form>
    </center>
    <div class="imgGallery">
        <!-- image preview -->
    </div>
    <!-- Display response messages -->
    <?php 
        if(!empty($response)) {?>
            <div class="alert <?php echo $response["status"]; ?>">
            <?php echo $response["message"]; ?>
            </div>
        <?php }
    ?>

</body>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
  $(function () {
    // Multiple images preview with JavaScript
    var multiImgPreview = function (input, imgPreviewPlaceholder) {

      if (input.files) {
        var filesAmount = input.files.length;

        for (i = 0; i < filesAmount; i++) {
          var reader = new FileReader();

          reader.onload = function (event) {
            $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(imgPreviewPlaceholder);
          }

          reader.readAsDataURL(input.files[i]);
        }
      }

    };

    $('#chooseFile').on('change', function () {
      multiImgPreview(this, 'div.imgGallery');
    });
  });
</script>

</html>