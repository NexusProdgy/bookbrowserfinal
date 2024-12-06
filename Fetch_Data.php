<?php
$conn = new mysqli("localhost", "root", "", "BookCollection");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default fetch all books (original functionality)
if (!isset($_GET['sortOrder'])) {
    $sql = "SELECT * FROM books ORDER BY id ASC"; // Default: Order of entry
} else {
    // Sorting logic based on sortOrder parameter
    $sortOrder = $_GET['sortOrder'];
    if ($sortOrder === 'alphabetical') {
        $sql = "SELECT * FROM books ORDER BY title ASC";
    } else {
        $sql = "SELECT * FROM books ORDER BY id ASC"; // Default: Order of entry
    }
}

$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

echo json_encode($books);

$conn->close();
?>



