<?php
include "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];
    $isRead = $_POST['isRead'];

    $query = "UPDATE books SET title = ?, author = ?, publicationYear = ?, genre = ?, isRead = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssisii", $title, $author, $year, $genre, $isRead, $id);

    if ($stmt->execute()) {
        // Handle image upload if provided
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "uploads/";
            $imageName = basename($_FILES['image']['name']);
            $targetFile = $targetDir . $imageName;
            $imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);

            // Validate image
            if ($_FILES['image']['size'] > 500000 || !in_array($imageFileType, ["jpg", "png", "jpeg"])) {
                echo json_encode(["success" => false, "error" => "Invalid image file"]);
                exit;
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imageQuery = "INSERT INTO book_images (book_id, image_path) VALUES (?, ?)";
                $imageStmt = $conn->prepare($imageQuery);
                $imageStmt->bind_param("is", $id, $targetFile);
                $imageStmt->execute();
            }
        }

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
}
?>
