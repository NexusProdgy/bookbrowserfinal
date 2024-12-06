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

// Check if the file was uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageTmpPath = $_FILES['image']['tmp_name'];
    $imageName = basename($_FILES['image']['name']);
    $uploadDir = 'uploads/';

    // Create a unique name for the image to avoid overwriting existing files
    $imagePath = $uploadDir . uniqid() . '_' . $imageName;

    // Move the uploaded file to the "uploads" directory
    if (move_uploaded_file($imageTmpPath, $imagePath)) {
        // Insert into the database, including the image path
        $sql = "INSERT INTO books (title, author, publicationYear, genre, isRead, image) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisis", $title, $author, $publicationYear, $genre, $isRead, $imagePath);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Book added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add book']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image upload failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image uploaded or upload error']);
}


$data = json_decode(file_get_contents("php://input"), true);

$id = (int)$data['id'];
$title = $data['title'];
$author = $data['author'];
$publicationYear = (int)$data['publicationYear'];
$genre = $data['genre'];
$isRead = (int)$data['isRead'];
$image = $data['image'];

$sql = "UPDATE books SET title = ?, author = ?, publicationYear = ?, genre = ?, isRead = ?, image = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisisi", $title, $author, $publicationYear, $genre, $isRead, $image, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Book updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update book']);
}

$stmt->close();
$conn->close();
?>