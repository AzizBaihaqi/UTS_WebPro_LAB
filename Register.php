<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "task_tracker";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function registerUser($conn, $username, $email, $password)
{
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkQuery = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        return false; // User already exists
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

        if (mysqli_query($conn, $sql)) {
            return true; // Registration successful
        } else {
            return false; // Registration failed
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $registrationResult = registerUser($conn, $username, $email, $password);

    if ($registrationResult === true) {
        header("Location: index.php");
        exit();
    } else {
        $errorMessage = "Error: Registration failed.";
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="index.css">
    <title>Daftar</title>
</head>

<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="Register.php" method="post">
                    <h2>Register</h2>
                    <?php
                    if (isset($registrationResult)) {
                        if ($registrationResult === false) {
                            echo '<div class="error-notification">Please change your username or email.</div>';
                        }
                    }
                    ?>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" required name="username" id="username">
                        <label for="">Username</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" required name="email" id="email">
                        <label for="">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" required name="password" id="password">
                        <label for="">Password</label>
                    </div>
                    <button id="registerButton" type="submit" name="registerButton">Register</button>
                    <div class="register">
                        <p>Already have an account? <a href="index.php">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>