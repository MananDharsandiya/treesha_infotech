<?php
    session_start(); 
    include('../../config/db.php'); 
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: auth/login.php');
        exit();
    }

    $adminName = $_SESSION['name'];
    $tasks = mysqli_query($conn, "SELECT tasks.*, users.name AS user_name FROM tasks JOIN users ON tasks.user_id = users.id");
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Tasks - Admin</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
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
    <body>
        <div class="d-flex">
        <!-- Sidebar -->
            <div class="sidebar p-3">
                <h4>Admin Panel</h4>
                <hr class="bg-light">
                <p>Welcome, <strong><?php echo $adminName; ?></strong></p>
                <a href="../dashboard.php" class="d-block mt-3">Dashboard</a>
                <a href="manage_tasks.php" class="d-block mt-3">Manage Tasks</a>
                <a href="../../auth/logout.php" class="d-block mt-2 logout-btn p-2 text-center">Logout</a>
            </div>

            <!-- Main Content -->
            <div class="container mt-5">
                <h2>Manage Tasks</h2>
                <div class="d-flex justify-content-end mb-2">
                    <a href="create_task.php" class="btn btn-success">Create New Task</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Assigned To</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($task = mysqli_fetch_assoc($tasks)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                <td><?php echo htmlspecialchars($task['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                                <td><?php echo htmlspecialchars($task['priority']); ?></td>
                                <td><?php echo htmlspecialchars($task['status']); ?></td>
                                <td>
                                    <a href="update_task.php?id=<?php echo $task['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete.php?id=<?php echo $task['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
