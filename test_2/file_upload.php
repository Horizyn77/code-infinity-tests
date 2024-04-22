<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
    <title>Code Infinity Test 2</title>
</head>

<body>

    <?php

    $uploadMsg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
            $uploadDir = "./uploads/";
            $uploadFile = $uploadDir . basename($_FILES["file"]["name"]);


            if ($_FILES["file"]["size"] > 100000000) {
                echo "Sorry, your file is too large.";
            } else {

                if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadFile)) {
                    $uploadMsg = "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been successfully uploaded.";
                } else {
                    $uploadMsg = "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            $uploadMsg = "No file uploaded or an error occurred during file upload.";
        }
    }
    ?>

    <div class="form-container file-upload-page">
        <h1>Code Infinity Test 2</h1>
        <h3>Records successfully generated!</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <label for="file">Import data into database</label><br>
            <input type="file" name="file" id="file" class="form-control">
            <input type="submit" value="Upload File" name="submit" class="btn btn-secondary">
        </form>
        <div class="spinner-container">
            <button onclick="importIntoDB()" class="btn btn-primary">Import into db</button>
            <div class="spinner"></div>
        </div>
        <p><?php echo $uploadMsg ?></p>
    </div>
    <script src="main.js"></script>
</body>

</html>