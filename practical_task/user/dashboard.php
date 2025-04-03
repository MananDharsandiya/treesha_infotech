<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$userName = $_SESSION['name'];
$userId = $_SESSION['user_id'];

$query = "SELECT * FROM tasks WHERE user_id = '$userId'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Practical</a>
        <div>
            <a class="navbar-brand" href="task.php">Task</a>
        </div>
        <div class="ms-auto">
            <span class="navbar-text me-3">Welcome, <strong><?php echo $userName; ?></strong></span>
            <a href="../auth/logout.php" class="btn logout-btn">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center mb-4">Your Tasks</h2>
    <div class="row">
        <?php while ($task = mysqli_fetch_assoc($result)) : ?>
            <div class="col-md-4 mb-6">
                <div class="task-card">
                    <h5><?php echo htmlspecialchars($task['title']); ?></h5>
                    <p><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
                    <p>Due Date: <strong><?php echo htmlspecialchars($task['due_date']); ?></strong></p>
                    <p class="task-status">Status: 
                        <span>
                            <?php echo ucfirst($task['status']); ?>
                        </span>
                    </p>
                </div>
            </div>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($result) == 0) : ?>
            <p class="text-center">You have no tasks assigned yet.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
