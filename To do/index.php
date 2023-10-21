<!DOCTYPE html>
<html>

<head>
    <title>Task Tracker</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>
    <nav class="navbar">
        <div class="navbar-content">
            <h1>Task Tracker</h1>
            <a href="../index.php" class="logout-btn">Log Out</a>
        </div>
    </nav>

    <div class="adder">
        <button id="showFormButton">Add Task</button>
        <form id="taskForm" action="index.php" method="post">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title"><br>

            <label for="description">Description:</label>
            <textarea name="description" id="description"></textarea><br>

            <label for="due_date">Due Date:</label>
            <input type="date" name="due_date" id="due_date"><br>

            <input type="submit" name="newTask" value="Submit">
        </form>
    </div>

    <ul>
        <?php
        session_start();
        $user_id = $_SESSION['user_id'];

        function updateTaskStatus($task_id, $new_status)
        {
            $conn = new mysqli('localhost', 'root', '', 'task_tracker');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "UPDATE tasks SET status = '$new_status' WHERE id = $task_id";
            $conn->query($sql);
        }

        function deleteTask($task_id, $user_id)
        {
            $conn = new mysqli('localhost', 'root', '', 'task_tracker');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "DELETE FROM tasks WHERE id = $task_id AND user_id = $user_id";
            $conn->query($sql);
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (isset($_POST['newTask'])) {
                $title = $_POST['title'];
                $description = $_POST['description'];
                $due_date = $_POST['due_date'];

                $conn = new mysqli('localhost', 'root', '', 'task_tracker');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "INSERT INTO tasks (title, description, due_date, user_id) VALUES ('$title', '$description', '$due_date', '$user_id')";
                $conn->query($sql);
            }

            if (isset($_POST['updateStatus'])) {
                $task_id = $_POST['task_id'];
                $new_status = $_POST['new_status'];
                updateTaskStatus($task_id, $new_status);
            }
        }

        if (isset($_GET['delete'])) {
            $task_id = $_GET['delete'];
            deleteTask($task_id, $user_id);
        }

        $conn = new mysqli('localhost', 'root', '', 'task_tracker');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM tasks WHERE user_id = '$user_id' ORDER BY status ASC, due_date ASC";
        $result = $conn->query($sql);

        echo '<table>';
        echo '<tr>';
        echo '<th>Title</th>';
        echo '<th>Description</th>';
        echo '<th>Due Date</th>';
        echo '<th>Status</th>';
        echo '<th>Actions</th>';
        echo '</tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['title'] . '</td>';
            echo '<td>' . $row['description'] . '</td>';
            echo '<td>' . $row['due_date'] . '</td>';
            echo '<td>' . $row['status'] . '</td>';
            echo '<td>';
            echo '<form method="post" action="index.php">';
            echo '<input type="hidden" name="task_id" value="' . $row['id'] . '">';
            echo '<select name="new_status">';
            echo '<option value="Not Started">Not Started</option>';
            echo '<option value="In Progress">In Progress</option>';
            echo '<option value="Waiting On">Waiting On</option>';
            echo '<option value="Done">Done</option>';
            echo '</select>';
            echo '<input type="submit" name="updateStatus" value="Update Status">';
            echo '</form>';
            echo '<a href="edit_task.php?id=' . $row['id'] . '">Edit</a> | ';
            echo '<a href="delete_task.php?id=' . $row['id'] . '">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';


        $conn->close();
        ?>
    </ul>
    <script>
        document.getElementById("taskForm").style.display = "none";

        document.getElementById("showFormButton").addEventListener("click", function() {

            var form = document.getElementById("taskForm");
            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        });
    </script>
</body>

</html>