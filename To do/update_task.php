<?php
session_start();

function updateTaskStatus($taskId, $newStatus) {
    $conn = new mysqli('localhost', 'root', '1', 'task_tracker');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE tasks SET status = '$newStatus' WHERE id = $taskId";
    $conn->query($sql);

    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $id = $_POST['id'];
    $new_status = $_POST['new_status'];
    
    updateTaskStatus($id, $new_status);

    header("Location: index.php");
}
