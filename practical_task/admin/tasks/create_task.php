<?php
session_start();
include('../../config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../auth/login.php');
    exit();
}
$adminName = $_SESSION['name'];

$users = mysqli_query($conn, "SELECT * FROM users WHERE role = 'user'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $user_id = $_POST['user_id'];

    $insert_query = "INSERT INTO tasks (title, description, due_date, priority, status, user_id)
                     VALUES ('$title', '$description', '$due_date', '$priority', '$status', '$user_id')";

    if (mysqli_query($conn, $insert_query)) {
        echo "<div class='alert alert-success text-center'>Task created successfully!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Task - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }
        .sidebar {
            height: 100vh;
            background: #343a40;
            color: white;
            padding: 20px;
            width: 250px;
            position: fixed;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
        .content {
            margin-left: 270px; /* Sidebar width + padding */
            padding: 30px;
            flex: 1;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="sidebar p-3">
    <h4>Admin Panel</h4>
    <hr class="bg-light">
    <p>Welcome, <strong><?php echo $adminName; ?></strong></p>
    <a href="../../dashboard.php" class="d-block mt-3">Dashboard</a>
    <a href="../tasks/manage_tasks.php" class="d-block mt-3">Manage Tasks</a>
    <a href="../../auth/logout.php" class="d-block mt-2 logout-btn p-2 text-center">Logout</a>
</div>

<div class="content">
    <div class="form-container">
        <h2>Create New Task</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Task Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="due_date" name="due_date" required>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <select class="form-select" id="priority" name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="user_id" class="form-label">Assign To</label>
                <select class="form-select" id="user_id" name="user_id" required>
                    <option value="" disabled selected>Select User</option>
                    <?php while ($user = mysqli_fetch_assoc($users)) : ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Create Task</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
