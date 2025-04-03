<?php
include('../config/db.php');
include('../includes/session.php');

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    $query = "DELETE FROM tasks WHERE id='$task_id' AND user_id='{$_SESSION['user_id']}'";
    if (mysqli_query($conn, $query)) {
        header("Location: task.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
