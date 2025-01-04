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
    <h3 align="center"> Upcoming Events</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Event Date</th>
                <th>Description</th>
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