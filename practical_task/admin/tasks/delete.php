<?php
include('../../config/db.php');
include('../../includes/session.php');

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    $query = "DELETE FROM tasks WHERE id='$task_id'";
    if (mysqli_query($conn, $query)) {
        header('Location: manage_tasks.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
