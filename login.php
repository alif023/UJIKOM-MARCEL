<?php
session_start();
include "koneksi.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek user berdasarkan username
    $query = $koneksi->query("SELECT * FROM user WHERE username='$username'");

    if ($query->num_rows == 1) {
        $row = $query->fetch_assoc();

        // Jika password di database masih plain text
        if ($password == $row['password']) {

            // Set session
            $_SESSION['user_id']   = $row['id_user'];
            $_SESSION['username']  = $row['username'];
            $_SESSION['role']      = $row['role'];

            header("Location: index.php");
            exit;

        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "User tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem Parkir</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 50px;
        }
        .login-box {
            width: 350px;
            margin: auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }
        button {
            padding: 10px;
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Sistem Parkir</h2>

    <?php if (isset($error)) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">LOGIN</button>
    </form>
</div>

</body>
</html>
