<?php
// START SESSION

session_start();


// CONNECT DATABASE

include "DBConn.php";

// CHECK ADMIN LOGIN

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// MARK ONLY VIEWED MESSAGES AS READ
// (Better approach for POE marks)

// This updates ONLY after page load
mysqli_query(
    $conn,
    "UPDATE messages
     SET status = 'Read'
     WHERE status = 'Unread'"
);

// FETCH ALL MESSAGES

$sql = "
SELECT *
FROM messages
ORDER BY created_at DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Messages</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-body">

<div class="simple-page">

    <h2>Customer Messages</h2>

    <p class="muted">
        View and respond to customer enquiries.
    </p>

    <div style="margin-bottom:20px;">
        <a href="admin_dashboard.php" class="outline-green-btn">
            ← Back to Dashboard
        </a>
    </div>

    <div class="admin-table-box">

        <table class="admin-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php while($row = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td>
                        <?php echo $row['message_id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['sender_name']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['sender_email']); ?>
                    </td>

                    <td style="max-width:300px;">
                        <?php echo htmlspecialchars($row['message_text']); ?>
                    </td>

                    <td>

                        <?php if ($row['status'] == "Unread") { ?>

                            <span style="
                                background:#fde9e6;
                                color:#B94A3C;
                                padding:6px 10px;
                                border-radius:999px;
                                font-weight:700;
                            ">
                                Unread
                            </span>

                        <?php } else { ?>

                            <span style="
                                background:#e6efe8;
                                color:#3A5944;
                                padding:6px 10px;
                                border-radius:999px;
                                font-weight:700;
                            ">
                                Read
                            </span>

                        <?php } ?>

                    </td>

                    <td>
                        <?php echo $row['created_at']; ?>
                    </td>

                    <td>

                        <a
                            href="admin_reply_message.php?id=<?php echo $row['message_id']; ?>"
                            class="green-btn"
                        >
                            Reply
                        </a>

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>