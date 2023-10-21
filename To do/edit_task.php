<!DOCTYPE html>
<html>

<head>
    <title>Edit Task</title>
    <link rel="stylesheet" type="text/css" href="edit.css">
</head>

<body>
    <?php
    session_start();
    $user_id = $_SESSION['user_id'];

    $id = $_GET['id'];

    $conn = new mysqli('localhost', 'root', '', 'task_tracker');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM tasks WHERE id = $id AND user_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();
        $title = isset($row['title']) ? $row['title'] : '';
        $description = isset($row['description']) ? $row['description'] : '';
        $due_date = isset($row['due_date']) ? $row['due_date'] : '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") :

            $new_title = $_POST['title'];
            $new_description = $_POST['description'];
            $new_due_date = $_POST['due_date'];

            $update_sql = "UPDATE tasks SET title = '$new_title', description = '$new_description', due_date = '$new_due_date' WHERE id = $id AND user_id = $user_id";
            if ($conn->query($update_sql) === TRUE) :
                header("Location: index.php");
            else :
                echo "Error updating task: " . $conn->error;
            endif;
        endif;
    ?>

        <div class="container">
            <div class="form-container">
                <form action="edit_task.php?id=<?php echo $id; ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" value="<?php echo $title; ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description"><?php echo $description; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="due_date">Due Date:</label>
                        <input type="date" name="due_date" id="due_date" value="<?php echo $due_date; ?>">
                    </div>
                    <input type="submit" value="Save Changes">
                </form>
            </div>
        </div>

    <?php
    else :
        echo "Task not found or you don't have permission to edit it.";
    endif;

    $conn->close();
    ?>
</body>

</html>