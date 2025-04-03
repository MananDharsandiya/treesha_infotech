<?php
include('../config/db.php');

$query = "UPDATE tasks SET status='Past Due' 
          WHERE due_date < CURDATE() AND status != 'Completed'";

mysqli_query($conn, $query);
?>
