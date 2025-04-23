<?php
session_start();
include '../config/db.php';

// Ensure only admins can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Handle deletion if requested
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM contact_messages WHERE id = $delete_id");
    header("Location: messages.php"); // Refresh after deletion
    exit;
}

// Fetch contact messages
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Messages</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2 class="mb-4">Contact Messages</h2>

  <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

  <?php if ($messages && $messages->num_rows > 0): ?>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Message</th>
          <th>Sent At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $messages->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
            <td><?= isset($row['created_at']) ? $row['created_at'] : 'N/A' ?></td>
            <td>
              <a href="reply_message.php?email=<?= urlencode($row['email']) ?>&name=<?= urlencode($row['name']) ?>" class="btn btn-sm btn-primary mb-1">Reply</a>
              <a href="messages.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No messages yet.</p>
  <?php endif; ?>
</body>
</html>
