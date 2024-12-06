<?php

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileup"]["name"]);

if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$uploadOk = 1;

if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileup"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

if ($_FILES["fileup"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["fileup"]["tmp_name"], $target_file)) {
        $conn = new mysqli("localhost", "root", "", "BookCollection");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $title = $_POST["title"];
        $author = $_POST["author"];
        $year = $_POST["publicationYear"];
        $genre = $_POST["genre"];
        $isRead = $_POST["isRead"];
        $image = $target_file;

        $sql = "INSERT INTO books (title, author, publicationYear, genre, isRead, image) 
                VALUES ('$title', '$author', '$year', '$genre', '$isRead', '$image')";

        if ($conn->query($sql) === TRUE) {
            echo "New book added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
