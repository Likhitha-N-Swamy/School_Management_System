<?php
// Start session and output buffering
session_start();
ob_start();
include('../includes/config.php');
include('header.php');
include('sidebar.php');

// Database connection
$conn = new mysqli('localhost', 'root', '', 'school_system');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle event addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $event_title = $_POST['event_title'];
    $event_date = $_POST['event_date'];
    $event_description = $_POST['event_description'];

    $sql = "INSERT INTO events (title, event_date, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $event_title, $event_date, $event_description);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Event added successfully!";
    } else {
        $_SESSION['message'] = "Error adding event: " . $conn->error;
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle event deletion
if (isset($_GET['delete_id'])) {
    $event_id = intval($_GET['delete_id']);

    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Event deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting event: " . $conn->error;
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all events
$sql = "SELECT * FROM events ORDER BY event_date DESC";
$events_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h3 {
            color: #333;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
        }
        .message.success {
            background-color: #4CAF50;
        }
        .message.error {
            background-color: #f44336;
        }
        form {
            margin-bottom: 20px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: auto;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .events-list {
            margin-top: 20px;
        }
        .events-list p {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .event-details {
            flex-grow: 1;
        }
        .delete-button {
            background-color: #f44336;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Add New Event</h3>
        <form action="" method="POST">
            <input type="text" name="event_title" placeholder="Event Title" required>
            <input type="date" name="event_date" required>
            <textarea name="event_description" placeholder="Event Description" rows="4" required></textarea>
            <input type="submit" name="add_event" value="Add Event">
        </form>

        <?php
        // Display success or error message
        if (isset($_SESSION['message'])) {
            $class = (strpos($_SESSION['message'], 'successfully') !== false) ? 'success' : 'error';
            echo "<div class='message $class'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>

        <h3>Upcoming Events</h3>
        <?php if ($events_result->num_rows > 0): ?>
            <div class="events-list">
                <?php while ($event = $events_result->fetch_assoc()): ?>
                    <p>
                        <span class="event-details">
                            <strong><?= htmlspecialchars($event['title']) ?></strong> 
                            (<?= htmlspecialchars($event['event_date']) ?>)
                            <br>
                            <?= htmlspecialchars($event['description']) ?>
                        </span>
                        <form action="" method="GET" style="margin: 0;">
                            <input type="hidden" name="delete_id" value="<?= $event['id'] ?>">
                            <button type="submit" class="delete-button">Delete</button>
                        </form>
                    </p>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No events found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Close the database connection and flush output buffer
$conn->close();
ob_end_flush();
?>
<?php include('footer.php') ?>