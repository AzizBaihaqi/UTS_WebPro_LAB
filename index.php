<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "task_tracker";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function loginUser($conn, $email, $password)
{
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT id, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) {
            session_start();
            $_SESSION["user_id"] = $row["id"];
            header("Location: To do\index.php");
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="index.css">
    <title>Login</title>
</head>

<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="" method="post">
                    <h2>Login</h2>
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $email = $_POST["email"];
                        $password = $_POST["password"];
                        loginUser($conn, $email, $password);
                        echo '<div class="error-notification">Wrong Email Or Password</div>';
                    }
                    mysqli_close($conn);
                    ?>

                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" required name="email">
                        <label for="">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" required name="password">
                        <label for="">Password</label>
                    </div>
                    <div class="forget">
                        <label> <a href="forget.php">Forget Password</a> </label>
                    </div>
                    <button id="loginButton" type="submit">Login</button>
                    <div class="register">
                        <p>Don't have an account? <a href="Register.php">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>