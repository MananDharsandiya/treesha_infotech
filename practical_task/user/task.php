<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$update_past_due = "UPDATE tasks 
                     SET status = 'Past Due' 
                     WHERE user_id = '$user_id' AND due_date < CURDATE() AND status != 'Completed' AND status != 'Past Due'";
mysqli_query($conn, $update_past_due);
$query = "SELECT * FROM tasks WHERE user_id = '$user_id' ORDER BY due_date ASC";
$result = mysqli_query($conn, $query);
if (isset($_POST['mark_completed'])) {
    $task_id = $_POST['task_id'];
    $update_query = "UPDATE tasks SET status = 'Completed' WHERE id = '$task_id' AND user_id = '$user_id'";
    mysqli_query($conn, $update_query);
    header("Location: task.php");
    exit();
}
$userName = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background: #007bff;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .navbar-brand:hover, .nav-link:hover {
            text-decoration: underline;
        }

        .task-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .task-card:hover {
            transform: translateY(-5px);
        }

        .task-status {
            font-weight: bold;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Practical</a>
        <div>
            <a class="navbar-brand" href="task.php">Task</a>
        </div>
        <div class="ms-auto">
            <span class="navbar-text me-3">Welcome, <strong><?php echo $userName; ?></strong></span>
            <a href="../auth/logout.php" class="btn logout-btn">Logout</a>
        </div>
    </div>
</nav>
<div class="container mt-5">
<h2> Tasks List</h2>
    <div class="d-flex justify-content-end mb-2">
        <a href="create_task.php" class="btn btn-success">Create New Task</a>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="list-group">
            <?php while ($task = mysqli_fetch_assoc($result)) : ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h5><?php echo htmlspecialchars($task['title']); ?></h5>
                        <p>Status: <?php echo htmlspecialchars($task['status']); ?></p>
                        <p>Due Date: <?php echo htmlspecialchars($task['due_date']); ?></p>
                        <p>Priority: <?php echo htmlspecialchars($task['priority']); ?></p>
                        <p>Description: <?php echo htmlspecialchars($task['description']); ?></p>
                    </div>
                    <div>
                        <?php if ($task['status'] != 'Completed'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <button type="submit" name="mark_completed" class="btn btn-success btn-sm">Mark as Completed</button>
                            </form>
                        <?php endif; ?>
                        <a href="update_task.php?id=<?php echo $task['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">You have no tasks assigned.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
