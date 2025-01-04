<?php
session_start();
include('../includes/config.php');
include('header.php');
include('sidebar.php');

// Connect to the database
$conn = new mysqli("localhost", "root", "", "school_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $event_date = $_POST['event_date'];
    $description = $_POST['description'];

    // Insert event
    $stmt = $conn->prepare("INSERT INTO events (title, event_date, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $event_date, $description);
    $stmt->execute();

    $message = "Event added successfully!";
    $stmt->close();
}


// Fetch events
$sql = "SELECT * FROM events ORDER BY event_date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Campus Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        form {
            background: #fff;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        form label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        form input, form textarea, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        form button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #218838;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .message {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
            display: inline-block;
        }
    </style>
    <script>
        // Hide the success message after 5 seconds
        function hideMessage() {
            const messageElement = document.getElementById('success-message');
            if (messageElement) {
                setTimeout(() => {
                    messageElement.style.display = 'none';
                }, 5000);
            }
        }
        document.addEventListener('DOMContentLoaded', hideMessage);
    </script>
</head>
<body>
    <h2>Campus Events</h2>

    <!-- Success Message -->
    <?php if (!empty($message)) { ?>
        <div class="message" id="success-message">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <!-- Add Event Form -->
    <form method="POST" action="campusfunct.php">
        <h2>Add a New Event</h2>
        <label>Title:</label>
        <input type="text" name="title" required>
        <label>Event Date:</label>
        <input type="date" name="event_date" required>
        <label>Description:</label>
        <textarea name="description" required></textarea>
        <button type="submit">Add Event</button>
    </form>

    <!-- Events Table -->
    <h3 align="center">All Events</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Event Date</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $serial = 1;
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $serial++; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['event_date']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="edit_event.php?id=<?php echo $row['id']; ?>"class="btn btn-danger">Edit</a>
                        <a href="delete_event.php?id=<?php echo $row['id']; ?>"class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
<?php include('footer.php') ?>