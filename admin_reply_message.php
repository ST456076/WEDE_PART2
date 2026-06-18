<?php
session_start();
include "DBConn.php";

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get message ID
$message_id = (int)$_GET['id'];

// Fetch message
$sql = "SELECT * FROM messages WHERE message_id = $message_id";
$result = mysqli_query($conn, $sql);
$message = mysqli_fetch_assoc($result);

// If reply submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $reply = $_POST['reply_text'];

    // Update message with reply
    $update = "
        UPDATE messages
        SET reply_text = '$reply',
            status = 'Replied',
            replied_at = NOW()
        WHERE message_id = $message_id
    ";

    mysqli_query($conn, $update);

    header("Location: admin_messages.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reply Message</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-body">

<div class="simple-page">

    <h2>Reply to Message</h2>

    <a href="admin_messages.php" class="outline-green-btn">
        ← Back to Messages
    </a>

    <br><br>

    <div class="admin-table-box">

        <p>
            <strong>From:</strong>
            <?php echo $message['sender_name']; ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?php echo $message['sender_email']; ?>
        </p>

        <p>
            <strong>Message:</strong>
        </p>

        <div style="
            background: var(--cream);
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
        ">
            <?php echo $message['message_text']; ?>
        </div>

        <form method="POST">

            <textarea
                name="reply_text"
                placeholder="Type your reply..."
                required
            ></textarea>

            <br><br>

            <button type="submit" class="green-btn">
                Send Reply
            </button>

        </form>

    </div>

</div>

</body>
</html>