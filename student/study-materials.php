<?php
// Assuming you have included your database connection
include('../includes/config.php');
include('header.php');
include('sidebar.php');

// Check if the student is logged in (session is set)
if (isset($_SESSION['user_id'])) {
    $student_id = $_SESSION['user_id'];

    // Step 1: Get the class details of the student
    $query = "SELECT students.class, posts.title AS class_title FROM students
              JOIN posts ON students.class = posts.title
              WHERE students.account_id = ?";
    $stmt = $db_conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Fetch class details
        $stmt->bind_result($class_id, $class_title);
        $stmt->fetch();
        
        echo "<div class='center-message'><h2>Study Materials for Class: " . htmlspecialchars($class_title) . "</h2></div>";

        // Step 2: Fetch study materials related to this class
        $query_materials = "SELECT study_materials.id, study_materials.file_name, study_materials.description 
                            FROM study_materials 
                            JOIN posts ON study_materials.class_id = posts.id
                            WHERE posts.title = ?";
        $stmt_materials = $db_conn->prepare($query_materials);
        $stmt_materials->bind_param("s", $class_title);
        $stmt_materials->execute();
        $stmt_materials->store_result();

        if ($stmt_materials->num_rows > 0) {
            echo "<table class='materials-table'>";
            echo "<tr>
                    <th>Serial No.</th>
                    <th>File Name</th>
                    <th>Description</th>
                    <th>Download</th>
                  </tr>";
            $stmt_materials->bind_result($material_id, $file_name, $description);
            $serial_no = 1;
            while ($stmt_materials->fetch()) {
                echo "<tr>
                        <td>" . $serial_no++ . "</td>
                        <td>" . htmlspecialchars($file_name) . "</td>
                        <td>" . htmlspecialchars($description) . "</td>
                        <td><a href='download.php?material_id=" . urlencode($material_id) . "'>Download</a></td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No study materials available for your class.</p>";
        }
    } else {
        echo "<p>Student details not found.</p>";
    }

    $stmt->close();
} else {
    echo "<p>You need to log in to view your study materials.</p>";
}
?>

<style>
/* CSS for styling the study materials table */
.materials-table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    text-align: left;
    font-family: Arial, sans-serif;
}

.materials-table th, .materials-table td {
    border: 1px solid #ddd;
    padding: 8px;
}

.materials-table th {
    background-color: #4CAF50;
    font-weight: bold;
}

.materials-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.materials-table tr:hover {
    background-color: #f1f1f1;
}

.materials-table td a {
    color: #4CAF50;
}

.materials-table td a:hover {
    text-decoration: underline;
}

.center-message {
    text-align: center;
    margin: 20px 0;
    font-family: Arial, sans-serif;
    color: #333;
}
</style>

<?php include('footer.php') ?>
