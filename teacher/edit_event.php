<?php
$conn = new mysqli("localhost", "root", "", "school_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $conn->query("SELECT * FROM events WHERE id = $id");
    $event = $query->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $event_date = $_POST['event_date'];
    $description = $_POST['description'];

    // Update event
    $stmt = $conn->prepare("UPDATE events SET title = ?, event_date = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $event_date, $description, $id);
    $stmt->execute();

    header("Location: campusfunct.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
    <style>
        body {
            font-family:Arial, Helvetica, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .form-container input, .form-container textarea, .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1 align="center">Edit Event</h1>
    
    <form method="POST" action="edit_event.php">
        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo $event['title']; ?>" required><br><br>
        <label>Event Date:</label>
        <input type="date" name="event_date" value="<?php echo $event['event_date']; ?>" required><br><br>
        <label>Description:</label>
        <textarea name="description" required><?php echo $event['description']; ?></textarea><br><br>
        <button type="submit">Update Event</button>
    </form>
    </div>
</body>
</html>
