<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

$adminName = $_SESSION['name'];

$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mansion Point</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { height: 100vh; background: #343a40; color: white; padding: 20px; }
        .sidebar a { color: #fff; text-decoration: none; }
        .sidebar a:hover { text-decoration: underline; }
        .content { padding: 30px; }
        .user-card, .task-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); transition: transform 0.2s; }
        .user-card:hover, .task-card:hover { transform: translateY(-5px); }
        .logout-btn { background-color: #dc3545; color: white; border: none; }
        .logout-btn:hover { background-color: #c82333; }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h4>Admin Panel</h4>
        <hr class="bg-light">
        <p>Welcome, <strong><?php echo $adminName; ?></strong></p>
        <a href="dashboard.php" class="d-block mt-3">Dashboard</a>
        <a href="tasks/manage_tasks.php" class="d-block mt-3">Manage Tasks</a>
        <a href="../auth/logout.php" class="d-block mt-2 logout-btn p-2 text-center">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content w-100">
        <h2>All Users</h2>
        <div class="row">
            <?php while ($user = mysqli_fetch_assoc($result)) : ?>
                <div class="col-md-4 mb-4">
                    <div class="user-card">
                        <h5><?php echo htmlspecialchars($user['name']); ?></h5>
                        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                        <p>Role: <strong><?php echo ucfirst($user['role']); ?></strong></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
