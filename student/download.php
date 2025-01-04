<?php
include('../includes/config.php'); 

// Check if material_id is passed
if (isset($_GET['material_id'])) {
    $material_id = $_GET['material_id'];
    
    // Fetch the file details from the database
    $query = "SELECT file_name, file_path FROM study_materials WHERE id = ?";
    $stmt = $db_conn->prepare($query);
    $stmt->bind_param("i", $material_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // Fetch file details
        $stmt->bind_result($material_name, $file_path);
        $stmt->fetch();

        // Convert relative file path to absolute file path
        $full_file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path;

        // Check if the file exists
        if (file_exists($full_file_path)) {
            // Set headers to force download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($full_file_path) . '"');
            header('Content-Length: ' . filesize($full_file_path));

            // Clear output buffer and read file
            ob_clean();
            flush();
            readfile($full_file_path);
            exit;
        } else {
            echo "File does not exist.";
        }
    } else {
        echo "Study material not found.";
    }

    $stmt->close();
} else {
    echo "No material selected.";
}
?>