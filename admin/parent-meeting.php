<?php
include('../includes/config.php');

// Check if the user is logged in, otherwise redirect
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user's ID

// Handle sending a message
if (isset($_POST['submit'])) {
    $receiver_id = mysqli_real_escape_string($db_conn, $_POST['receiver_id']);
    $message = mysqli_real_escape_string($db_conn, $_POST['message']);

    // Insert message into the database
    $query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$user_id', '$receiver_id', '$message')";
    if (mysqli_query($db_conn, $query)) {
        $_SESSION['success_msg'] = 'Message sent successfully!';
        header('Location: inbox.php'); // Redirect to inbox page
        exit();
    } else {
        $_SESSION['error_msg'] = 'Failed to send message. Please try again.';
        header('Location: parent-meeting.php');
        exit();
    }
}

// Fetch messages for the user (both sent and received)
if (!isset($_GET['id'])) {
    $query = "SELECT m.id, m.message, m.timestamp, u.name AS sender_name 
              FROM messages m
              JOIN accounts u ON m.sender_id = u.id
              WHERE m.receiver_id = '$user_id' 
              ORDER BY m.timestamp DESC";
    $result = mysqli_query($db_conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Display success or error messages -->
<?php if (isset($_SESSION['success_msg'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success_msg']; ?></div>
    <?php unset($_SESSION['success_msg']); ?>
<?php elseif (isset($_SESSION['error_msg'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error_msg']; ?></div>
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<!-- Message sending form -->
<?php if (!isset($_GET['id'])): ?>
    <h3>Send a Message</h3>
    <form action="send_message.php" method="post">
        <div class="form-group">
            <label for="receiver">Receiver</label>
            <select name="receiver_id" id="receiver" class="form-control" required>
                <option value="">Select Receiver</option>
                <?php
                // Fetch users from the accounts table
                $result_users = mysqli_query($db_conn, "SELECT id, name FROM accounts WHERE type != 'admin'");
                while ($row = mysqli_fetch_assoc($result_users)) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Send Message</button>
    </form>

    <h3>Your Inbox</h3>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <ul>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <li>
                    <strong>From:</strong> <?= $row['sender_name'] ?>
                    <p><strong>Message:</strong> <?= $row['message'] ?></p>
                    <p><small>Sent on: <?= $row['timestamp'] ?></small></p>
                    <a href="send_message.php?id=<?= $row['id'] ?>">View Message</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>

<?php else: ?>
    <!-- Message detail view -->
    <?php
    // Show a message when clicked
    $message_id = intval($_GET['id']);
    $query = "SELECT m.message, m.timestamp, u.name AS sender_name 
              FROM messages m
              JOIN accounts u ON m.sender_id = u.id
              WHERE m.id = '$message_id'";
    $result = mysqli_query($db_conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        echo "<h3>Message from: {$row['sender_name']}</h3>";
        echo "<p>{$row['message']}</p>";
        echo "<p><small>Sent on: {$row['timestamp']}</small></p>";
    } else {
        echo "Message not found.";
    }
    ?>
<?php endif; ?>

</body>
</html>
