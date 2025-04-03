<?php
 session_start(); 
 include('../../config/db.php'); 
 if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
     print_r($_SESSION); 
     exit;
 }

 $adminName = $_SESSION['name'];

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../auth/login.php');
    exit();
}

$task_id = $_GET['id'] ?? null;

if (!$task_id) {
    echo "Task ID is missing!";
    exit();
}

$task_query = "SELECT * FROM tasks WHERE id = '$task_id'";
$task_result = mysqli_query($conn, $task_query);
$task = mysqli_fetch_assoc($task_result);

if (!$task) {
    echo "Task not found!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    $update_query = "UPDATE tasks 
                     SET title='$title', description='$description', due_date='$due_date', priority='$priority', status='$status' 
                     WHERE id='$task_id'";

    if (mysqli_query($conn, $update_query)) {
        echo "<div class='alert alert-success text-center'>Task updated successfully!</div>";
        header("Location: manage_tasks.php");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Task - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><style>
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
            margin-left: 270px;
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
    <h2>Update Task</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Task Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($task['description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="priority" class="form-label">Priority</label>
            <select class="form-select" id="priority" name="priority" required>
                <option value="Low" <?php echo $task['priority'] == 'Low' ? 'selected' : ''; ?>>Low</option>
                <option value="Medium" <?php echo $task['priority'] == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="High" <?php echo $task['priority'] == 'High' ? 'selected' : ''; ?>>High</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Pending" <?php echo $task['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="In Progress" <?php echo $task['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                <option value="Completed" <?php echo $task['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update Task</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
