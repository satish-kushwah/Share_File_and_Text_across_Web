<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
}
include 'uploads_dir.php';
$showAlert = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $target_dir = "$uploads_dir/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    //check file type
    if ($file_type == 'htm' or $file_type == 'html' or $file_type == 'php' or $file_type == 'asp' or $file_type == 'aspx' or $file_type == 'jsp' or $file_type == 'htaccess') {
        $_SESSION['alert'] = "<div class='py-2 alert alert-danger' role='alert'>
                <strong >Sorry, this file type not allowed, upload it by making zip file.</strong>
            </div>";
    }
    // Check if file already exists
    else if (file_exists($target_file)) {
        $_SESSION['alert'] = "<div class='py-2 alert alert-danger' role='alert'>
                <strong >file of this name already exists, please change file name then try to upload.</strong>
            </div>";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $filename = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
            $_SESSION['alert'] = "<div class='py-2 alert alert-success' role='alert'>
                <strong >The file $filename has been uploaded.</strong>
            </div>";
        } else {
            $_SESSION['alert'] = "<div class='py-2 alert alert-danger' role='alert'>
                <strong >Sorry, there was an error uploading your file.</strong>
            </div>";
        }
    }
}
if (isset($_GET['delete'])) {
    $fileName = $_GET['delete'];
    $file = "$uploads_dir/$fileName";
    if (file_exists($file)) {
        unlink($file);
        $_SESSION['alert'] = "<div class='py-2 alert alert-success' role='alert'>
                <strong >File deleted.</strong>
            </div>";
    }
}
header('Location: share_file.php');
