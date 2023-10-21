<!DOCTYPE html>
<html>

<head>
    <title>Confirm Delete</title>
    <style>
        body {
            background-image: url('del.jpg');
            background-blend-mode: multiply;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        body {
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .confirmation-box {
            background-color: #f5e31f;
            border: 2px solid #333;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
        }

        p {
            color: #333;
            margin-bottom: 20px;
        }

        .btn-container {

            justify-content: space-between;
        }

        .btn-yes,
        .btn-no {
            background-color: #d9534f;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            text-transform: uppercase;
        }

        .btn-yes:hover,
        .btn-no:hover {
            background-color: #c9302c;
        }
    </style>
    </style>
</head>

<body>
    <div class="confirmation-box">
        <?php
        $id = $_GET['id'];

        $conn = new mysqli('localhost', 'root', '', 'task_tracker');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM tasks WHERE id = $id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirmDelete'])) {
            $sqlDelete = "DELETE FROM tasks WHERE id = $id";
            if ($conn->query($sqlDelete) === TRUE) {
                echo "<p>Task deleted successfully.</p>";
                // Redirect back to index.php after deletion
                header("Location: index.php");
                exit;
            } else {
                echo "<p>Error deleting task: " . $conn->error . "</p>";
            }
        }
        ?>
        <p>Are you sure you want to delete the task "<?php echo $row['title']; ?>"?</p>
        <div class="btn-container">
            <form action="delete_task.php?id=<?php echo $id; ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <button type="submit" name="confirmDelete" class="btn-yes">Yes</button>
                <a href="index.php" class="btn-no" style="margin-left: 10px;">No</a>
            </form>
        </div>
    </div>
</body>

</html>