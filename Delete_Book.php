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

// Read and decode JSON input
$data = json_decode(file_get_contents('php://input'), true);
$bookId = $data['id'];

// Delete the book
$sql = "DELETE FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Book deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete the book']);
}

$stmt->close();
$conn->close();
?>
