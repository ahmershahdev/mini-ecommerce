<?php
require_once 'config/db.php';

$msg = '';

if (isset($_POST['signup'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($name === '' || $email === '' || $password === '') {
        $msg = 'Please fill all fields.';
    } else {
        $nameEsc = mysqli_real_escape_string($conn, $name);
        $emailEsc = mysqli_real_escape_string($conn, $email);
        $algo = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT;

        $checkSql = "SELECT id FROM users WHERE email = '$emailEsc' LIMIT 1";
        $checkRes = mysqli_query($conn, $checkSql);

        if ($checkRes && mysqli_num_rows($checkRes) > 0) {
            $msg = 'Email already exists.';
        } else {
            $hash = password_hash($password, $algo);
            if ($hash === false) {
                $msg = 'Signup failed.';
            } else {
                $passwordEsc = mysqli_real_escape_string($conn, $hash);
                $sql = "INSERT INTO users (name, email, password) VALUES ('$nameEsc', '$emailEsc', '$passwordEsc')";
                if (mysqli_query($conn, $sql)) {
                    $msg = 'Signup successful. Please login now.';
                } else {
                    $msg = 'Signup failed.';
                }
            }
        }
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup | Commerza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="<?php echo $APP_PATH; ?>/assets/css/style.css" rel="stylesheet">
</head>

<body class="auth-body">
    <div class="auth-shell">
        <div class="auth-card">
            <div class="auth-brand">
                <a class="brand-mark" href="<?php echo $APP_PATH; ?>/">Commerza</a>
                <p class="auth-subtitle">Create your account and start shopping instantly.</p>
            </div>

            <?php if ($msg !== '') { ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
            <?php } ?>

            <form method="post" action="<?php echo $APP_PATH; ?>/signup" class="auth-form" id="signupForm">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control auth-input" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control auth-input" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="signupPassword" class="form-control auth-input" required>
                        <button class="btn btn-outline-secondary toggle-pass" type="button" data-target="signupPassword">Show</button>
                    </div>
                    <div class="strength-wrap">
                        <div class="strength-meter">
                            <span id="signupStrengthBar" class="strength-bar"></span>
                        </div>
                        <small id="signupStrengthLabel" class="strength-label">Strength: -</small>
                    </div>
                </div>
                <button class="btn btn-brand w-100 auth-submit" name="signup" type="submit" data-default-text="Signup">Signup</button>
                <div class="auth-links">
                    <a href="<?php echo $APP_PATH; ?>/login">Already have account?</a>
                    <a href="<?php echo $APP_PATH; ?>/">Back to shop</a>
                </div>
            </form>
        </div>
    </div>
    <script src="<?php echo $APP_PATH; ?>/assets/js/auth.js"></script>
</body>

</html>