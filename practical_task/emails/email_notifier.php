<?php
include('../config/db.php');
$today = date('Y-m-d');
$query = "SELECT u.email, t.title, t.due_date
          FROM tasks t
          JOIN users u ON t.user_id = u.id
          WHERE (t.due_date = '$today' OR t.status = 'Past Due') AND t.status != 'Completed'";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $to = $row['email'];
    $subject = "Task Reminder: {$row['title']}";
    $message = "Hello,\n\nThis is a reminder that your task '{$row['title']}' is due today or has passed due.\n\nDue Date: {$row['due_date']}\n\nPlease check your tasks for more details.\n\nBest regards,\nMansion Point Team";
    $headers = "From:manan26601@gmail.com";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    if (mail($to, $subject, $message, $headers)) {
        echo "Reminder sent to: $to<br>";
    } else {
        echo "Failed to send reminder to: $to<br>";
    }
}
?>
