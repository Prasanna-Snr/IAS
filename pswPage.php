<?php

session_start();
include 'databaseConn.php'; 

$error_message = ''; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $psw = htmlspecialchars($_POST['psw']);
    $email = $_SESSION['email'];
    $result = $databaseConnection->query("SELECT psw FROM users WHERE email='$email'");

    if ($result && $result->num_rows && password_verify($psw, $result->fetch_assoc()['psw'])) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = 'Incorrect password';
    }

    $databaseConnection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Verification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(to right, #00c6ff, #0072ff); height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .container { background-color: #fff; border-radius: 0.5rem; box-shadow: 0 0 1rem rgba(0,0,0,0.1); padding: 2rem; max-width: 500px; }
        .form-control { font-size: 0.875rem; }
        .btn-primary { background-color: #28a745; border: none; }
        .btn-primary:hover { background-color: #218838; }
        label, h1 { color: #0072ff; }
        .toggle-password { cursor: pointer; position: absolute; right: 10px; top: 40px; }
        .error-message { color: red; font-size: 0.875rem; margin-top: 0.5rem; }
        .forgot-password { font-size: 0.875rem; text-align: right; display: block; margin-top: 0.5rem; }
        .forgot-password a { color: #0072ff; text-decoration: none; }
        .forgot-password a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Password Verification</h1>
        <form method="post">
            <div class="form-group position-relative">
                <label for="psw">Enter Password:</label>
                <input type="password" id="psw" name="psw" class="form-control" placeholder="Password" required>
                <i class="fas fa-eye-slash toggle-password" onclick="togglePassword()"></i>
                <?php if (!empty($error_message)) : ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
            <span class="forgot-password">
                <a href="">Forgot Password?</a>
            </span>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById("psw");
            const toggleIcon = document.querySelector(".toggle-password");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            }
        }
    </script>
</body>
</html>