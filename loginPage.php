<?php
session_start();
include 'databaseConn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function createOtp() {
    return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);
}

function dispatchOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'prasannasunuwar03@gmail.com';
        $mail->Password = 'qnsz peby oylh vvlq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('TechNepal03@gmail.com', 'TechNepal');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'OTP Code';
        $mail->Body = "Your OTP code is: <b>$otp</b><br>This OTP is valid for 2 minutes only.";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}

$error = '';
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
$fromOtpPage = isset($_GET['fromOtpPage']) ? true : false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $emailExists = $databaseConnection->query("SELECT 1 FROM users WHERE email='$email'")->num_rows > 0;

    if ($emailExists) {
        $otp = createOtp();
        $result = dispatchOtpEmail($email, $otp);
        
        if ($result === true) {
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_timestamp'] = time();
            $_SESSION['email'] = $email;
            header("Location: otpPage.php");
            exit();
        } else {
            $error = "Failed to send OTP: $result. Please try again.";
        }
    } else {
        $error = 'Email does not exist. Please sign up.';
    }

    $databaseConnection->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #00c6ff, #0072ff);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background-color: #f8f9fa;
            border-radius: 0.75rem;
            box-shadow: 0 0 1.5rem rgba(0, 0, 0, 0.2);
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
            text-align: left;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header .card-title {
            color: #0072ff;
            font-size: 2.5rem;
            margin: 0;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
            font-size: 1.125rem;
            padding: 0.75rem 1.25rem;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
        a {
            color: #0072ff;
            text-decoration: none;
        }
        a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        .text-danger {
            color: #dc3545;
        }
        .form-control {
            font-size: 1rem;
            padding: 0.75rem;
        }
        .form-control::placeholder {
            font-size: 1rem;
        }
        .header-text {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        .header-text .special-link {
            color: #ffffff;
            position: absolute;
            left: 50%;
            padding-left:600px;
            top: 0;
            transform: translateX(-50%);
        }
        .header-text a.disabled {
            color:#f8f9fa;
            pointer-events: none;
        }
        .header-text a.disabled:hover {
            
            color: #d3d3d3;
            text-decoration: none;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group .text-danger {
            display: inline-block;
            margin-left: 1rem;
        }
    </style>
</head>
<body>
    <div class="card shadow-sm">
        <div class="header-text">
            <a href="otpPage.php?email=<?php echo urlencode($email); ?>" <?php if (!$fromOtpPage) echo 'class="disabled"'; ?> class="special-link">OTP</a>
        </div>
        <div class="login-header">
            <h1 class="card-title">Login</h1>
        </div>
        <form method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
                <?php if ($error): ?>
                    <div class="text-danger"><?php echo $error; ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>
        </form>
        <p class="text-center mt-3">Don’t have an account? <a href="registrationPage.php">Sign up</a></p>
    </div>
</body>
</html>

