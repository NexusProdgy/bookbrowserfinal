<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Create a connection to the MySQL database
$servername = "localhost";
$username = "root";  // Username
$password = "";  // Empty password
$dbname = "BookCollection";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the index parameter from the URL
if (isset($_GET['index'])) {
    $index = (int) $_GET['index'];

    // Query to get the book at the given index
    $sql = "SELECT * FROM books LIMIT $index, 1";  // Assuming index is 0-based
    $result = $conn->query($sql);

    // If a book is found, output it as JSON
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        echo json_encode($book);  // Output the book as JSON
    } else {
        echo json_encode(['error' => 'Book not found']);  // Return an error message if no book found
    }
} else {
    echo json_encode(['error' => 'Invalid index parameter']);  // Return error if no index is provided
}

$conn->close();
?>
