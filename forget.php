<?php
session_start();

$email = $new_password = $password_changed = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $new_password = $_POST["password"];

    $password_changed = changePassword($email, $new_password);
}

function changePassword($email, $new_password)
{
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "task_tracker";

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    $email = mysqli_real_escape_string($conn, $email);

    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row["id"];

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update_query = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";
        if (mysqli_query($conn, $update_query)) {
            mysqli_close($conn);
            return true;
        }
    }

    mysqli_close($conn);
    return false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="index.css">
    <title>Change Password</title>
</head>

<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form method="post" onsubmit="return showNotification()">
                    <h2>Change Password</h2>
                    <?php
                    if ($password_changed) {
                        echo '<div class="success-notification">Password changed successfully.</div>';
                    } elseif (!$password_changed && $_SERVER["REQUEST_METHOD"] == "POST") {
                        echo '<div class="error-notification">Wrong email.</div>';
                    }
                    ?>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" required name="email" id="email" value="<?php echo $email; ?>">
                        <label for="">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" required name="password" id="password" value="<?php echo $new_password; ?>">
                        <label for="">New Password</label>
                    </div>
                    <button id="registerButton" type="submit">Change</button>
                    <div class="register">
                        <p><a href="index.php">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        function showNotification() {
            return true;
        }
    </script>
</body>

</html>