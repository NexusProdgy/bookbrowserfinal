<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BookCollection";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// Get data from POST request
$title = $_POST['title'];
$author = $_POST['author'];
$publicationYear = (int)$_POST['publicationYear'];
$genre = $_POST['genre'];
$isRead = (int)$_POST['isRead'];
$image = $_POST['image'];

// Insert into database
$sql = "INSERT INTO books (title, author, publicationYear, genre, isRead, image) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisis", $title, $author, $publicationYear, $genre, $isRead, $image);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Book added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add book']);
}

$stmt->close();
$conn->close();
?>
