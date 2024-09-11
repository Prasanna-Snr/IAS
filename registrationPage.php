<?php
include 'databaseConn.php';

$passwordError = "";
$validationError = "";
$emailError = "";

// Function to validate password
function validatePassword($password) {
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password) || 
        !preg_match('/[!@#$%^&*()_+{}\[\]:;"\'<>,.?~`]/', $password)) {
        return "Password must be at least 8 characters long, include one uppercase letter, one number, and one special character.";
    }
    return "";
}

// Function to validate names
function validateName($name) {
    return empty($name) || preg_match('/^[a-zA-Z]*$/', $name);
}

// Function to validate email
function validateEmail($email) {
    return preg_match('/^[\w\.-]+@gmail\.com$/', $email);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = array_map('htmlspecialchars', $_POST);
    $passwordError = validatePassword($data['psw']);
    $emailError = !validateEmail($data['email']) ? "Email must end with @gmail.com" : "";

    if (!validateName($data['fname']) || !validateName($data['mname']) || !validateName($data['lname'])) {
        $validationError = "Names must contain only alphabetic characters.";
    }

    if (!$passwordError && !$validationError && !$emailError) {
        $emailCheckSql = "SELECT 1 FROM users WHERE email='{$data['email']}'";
        $queryResult = $databaseConnection->query($emailCheckSql);

        if ($queryResult->num_rows > 0) {
            $emailExists = true;
        } else {
            $passwordHash = password_hash($data['psw'], PASSWORD_DEFAULT);
            $mname = empty($data['mname']) ? 'NULL' : "'{$data['mname']}'";
            $insertSql = "INSERT INTO users (fname, mname, lname, dob, gender, email, psw) 
                          VALUES ('{$data['fname']}', $mname, '{$data['lname']}', 
                                  '{$data['dob']}', '{$data['gender']}', '{$data['email']}', '$passwordHash')";

            if ($databaseConnection->query($insertSql)) {
                header("Location: loginPage.php");
                exit();
            } else {
                $databaseErrorMessage = "Error: " . $databaseConnection->error;
            }
        }
        $databaseConnection->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(to right, #00c6ff, #0072ff); }
        .container { background-color: #f8f9fa; border-radius: 0.5rem; box-shadow: 0 0 1rem rgba(0, 0, 0, 0.1); padding: 2rem; max-width: 800px; margin: auto; margin-top: 5rem; }
        .form-control { font-size: 0.875rem; }
        .form-control::placeholder { font-size: 0.875rem; }
        .btn-primary { background-color: #28a745; border: none; }
        .btn-primary:hover { background-color: #218838; }
        a { color: #28a745; }
        a:hover { color: #1e7e34; }
        .text-danger { color: #dc3545; }
        .form-group { position: relative; }
        .form-error { position: absolute; left: 0; bottom: -1.5rem; font-size: 0.875rem; color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Register</h1>
        <form action="registrationPage.php" method="post">
            <div class="form-row">
                <?php foreach (['fname' => 'First Name', 'mname' => 'Middle Name', 'lname' => 'Last Name'] as $key => $label): ?>
                    <div class="form-group col-md-4">
                        <label for="<?= $key ?>"><?= $label ?>:</label>
                        <input type="text" id="<?= $key ?>" name="<?= $key ?>" class="form-control form-control-lg" placeholder="<?= $label ?>" value="<?= isset($data[$key]) ? htmlspecialchars($data[$key]) : '' ?>" <?= $key !== 'mname' ? 'required' : '' ?>>
                        <?php if ($validationError && !validateName($data[$key])) { echo "<p class='form-error'>$validationError</p>"; } ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" class="form-control form-control-lg" value="<?= isset($data['dob']) ? htmlspecialchars($data['dob']) : '' ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Gender:</label><br>
                    <?php foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label): ?>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="<?= $value ?>" name="gender" value="<?= $value ?>" class="form-check-input" <?= (isset($data['gender']) && $data['gender'] == $value) ? 'checked' : '' ?>>
                            <label for="<?= $value ?>" class="form-check-label"><?= $label ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email" value="<?= isset($data['email']) ? htmlspecialchars($data['email']) : '' ?>" required>
                    <p id="emailError" class="form-error"><?= $emailError ?></p>
                </div>
                <div class="form-group col-md-6">
                    <label for="psw">Password:</label>
                    <input type="password" id="psw" name="psw" class="form-control form-control-lg" placeholder="Password" value="<?= isset($data['psw']) ? htmlspecialchars($data['psw']) : '' ?>" required>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="showPassword">
                        <label class="form-check-label" for="showPassword">Show Password</label>
                    </div>
                    <?php if ($passwordError) { echo "<p class='form-error'>$passwordError</p>"; } ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Register</button>
        </form>
        <p class="text-center mt-3">Have an account? <a href="loginPage.php">Login</a></p>
    </div>

    <script>
        function validateAlphabeticInput(event) {
            const regex = /^[a-zA-Z]*$/;
            if (!regex.test(event.target.value)) {
                event.target.value = event.target.value.replace(/[^a-zA-Z]/g, '');
            }
        }

        function validateEmailInput() {
            const emailField = document.getElementById('email');
            const email = emailField.value;
            const emailError = document.getElementById('emailError');

            if (email && !email.endsWith('@gmail.com')) {
                emailError.textContent = 'Email must end with @gmail.com';
            } else {
                emailError.textContent = '';
            }
        }

        document.getElementById('fname').addEventListener('input', validateAlphabeticInput);
        document.getElementById('mname').addEventListener('input', validateAlphabeticInput);
        document.getElementById('lname').addEventListener('input', validateAlphabeticInput);
        document.getElementById('email').addEventListener('blur', validateEmailInput);

        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordField = document.getElementById('psw');
            passwordField.type = this.checked ? 'text' : 'password';
        });
    </script>
</body>
</html>
