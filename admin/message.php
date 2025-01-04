<?php
include('../includes/config.php');
include('header.php');
include('sidebar.php');
if (!isset($_SESSION['user_id'])) {
    die("Access Denied. Please log in as admin.");
}

$feedback = "";
$sender_id = $_SESSION['user_id'];
// Handle form submission for sending a message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $recipient_type = $_POST['recipient_type'];
    $message = trim($_POST['message']);

    if (empty($message)) {
        $feedback = "<div class='error'>Message cannot be empty.</div>";
    } else {
        if ($recipient_type === 'individual') {
            $email = trim($_POST['email']);
            $stmt = $db_conn->prepare("SELECT id FROM accounts WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $receiver = $result->fetch_assoc();
                $receiver_id = $receiver['id'];

                // Check if the message has already been sent to this user
                $check_stmt = $db_conn->prepare("SELECT id FROM messages WHERE sender_id = ? AND receiver_id = ? AND message = ?");
                $check_stmt->bind_param("iis", $sender_id, $receiver_id, $message);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($check_result->num_rows === 0) {
                    $insert_stmt = $db_conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
                    $insert_stmt->bind_param("iis", $sender_id, $receiver_id, $message);

                    if ($insert_stmt->execute()) {
                        $feedback = "<div class='success'>Message sent successfully to the individual!</div>";
                    } else {
                        $feedback = "<div class='error'>Failed to send the message. Please try again.</div>";
                    }
                } else {
                    $feedback = "<div class='error'>Message already sent to this individual.</div>";
                }
            } else {
                $feedback = "<div class='error'>User with this email does not exist.</div>";
            }
        } else {
            // Handle sending to groups (e.g., students, teachers, everyone)
            $group_query = "";
            if ($recipient_type === 'students') {
                $group_query = "SELECT id FROM accounts WHERE type = 'student'";
            } elseif ($recipient_type === 'teachers') {
                $group_query = "SELECT id FROM accounts WHERE type = 'teacher'";
            } elseif ($recipient_type === 'everyone') {
                $group_query = "SELECT id FROM accounts";
            }

            if ($group_query !== "") {
                $result = $db_conn->query($group_query);
                while ($receiver = $result->fetch_assoc()) {
                    $receiver_id = $receiver['id'];

                    // Check if the message has already been sent to this user
                    $check_stmt = $db_conn->prepare("SELECT id FROM messages WHERE sender_id = ? AND receiver_id = ? AND message = ?");
                    $check_stmt->bind_param("iis", $sender_id, $receiver_id, $message);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();

                    if ($check_result->num_rows === 0) {
                        // Insert the message only if it hasn't been sent already
                        $insert_stmt = $db_conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
                        $insert_stmt->bind_param("iis", $sender_id, $receiver_id, $message);
                        $insert_stmt->execute();
                    }
                }
                $feedback = "<div class='success'>Message sent successfully to the group!</div>";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_message'])) {
    $message_id = intval($_POST['message_id']);
    $delete_stmt = $db_conn->prepare("DELETE FROM messages WHERE id = ? AND receiver_id = ?");
    $delete_stmt->bind_param("ii", $message_id, $sender_id);

    if ($delete_stmt->execute()) {
        $feedback = "<div class='success'>Message deleted successfully!</div>";
    } else {
        $feedback = "<div class='error'>Failed to delete the message. Please try again.</div>";
    }
}

// Fetch messages sent to admin
$messages_query = $db_conn->prepare("SELECT m.id, m.sender_id, m.message, a.email AS sender_email FROM messages m JOIN accounts a ON m.sender_id = a.id WHERE m.receiver_id = ? ORDER BY m.id DESC");
$messages_query->bind_param("i", $sender_id);
$messages_query->execute();
$messages = $messages_query->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messaging</title>
     <style>
        /* Enhanced CSS */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(180deg, #f5f7fa, #c3cfe2);
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: left;
            justify-content: flex-start;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 1000px;
        }
        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .feedback {
            margin-bottom: 15px;
            text-align: center;
            animation: fadeout 5s forwards;
        }
        @keyframes fadeout {
            0% { opacity: 1; }
            90% { opacity: 0.1; }
            100% { display: none; }
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        select {
            padding:10px;
            cursor: pointer;
            background: linear-gradient(to right, #4CAF50, #81C784);
            color: white;
            border: none;
        }
        select option {
            background: #ffffff;
            color: #333;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #45a049;
        }
        .message {
            background: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .message .email {
            font-weight: bold;
            color: #555;
        }
        .message .content {
            margin-top: 5px;
        }
        .message-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .reply-button, .delete-button {
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .reply-button {
            background: #007BFF;
            color: #fff;
        }
        .reply-button:hover {
            background: #0056b3;
        }
        .delete-button {
            background: #FF4136;
            color: #fff;
        }
        .delete-button:hover {
            background: #c7001c;
        }
        .reply-form {
            display: none;
            margin-top: 10px;
        }
        
    </style>
</head>
<body>
    <div class="split left">
        <div class="container">
            <h1>Send Message</h1>
            <?php if (!empty($feedback)) echo "<div class='feedback'>{$feedback}</div>"; ?>
            <form method="POST">
                <label for="recipient_type">Recipient Type:</label>
                <select name="recipient_type" id="recipient_type" onchange="toggleIndividualInput(this.value)" required>
                    <option value="students">All Students</option>
                    <option value="teachers">All Teachers</option>
                    <option value="everyone">Everyone</option>
                    <option value="individual">Individual</option>
                </select>
                
                <div id="individual-email" style="display: none;">
                    <label for="email">Recipient Email:</label>
                    <input type="email" name="email" id="email" placeholder="Enter recipient's email">
                </div>

                <label for="message">Message:</label>
                <textarea name="message" id="message" rows="5" placeholder="Type your message here..." required></textarea>

                <button type="submit" name="send_message">Send Message</button>
            </form>
        </div>
    </div>
    <div class="split right">
        <div class="container">
            <h1>Received Messages</h1>
            <?php if ($messages->num_rows > 0): ?>
                <?php while ($msg = $messages->fetch_assoc()): ?>
                    <div class="message">
                        <p><strong>From:</strong> <?php echo htmlspecialchars($msg['sender_email']); ?></p>
                        <p><?php echo htmlspecialchars($msg['message']); ?></p>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                            <button type="submit" name="delete_message" style="background: #FF4136;">Delete</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No messages received yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleIndividualInput(value) {
            document.getElementById('individual-email').style.display = value === 'individual' ? 'block' : 'none';
        }
    </script>
</body>
</html>
<?php include('footer.php'); ?>