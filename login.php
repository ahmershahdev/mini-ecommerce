<?php
require_once 'config/db.php';

$msg = '';

if (isset($_POST['login'])) {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email === '' || $password === '') {
        $msg = 'Please fill all fields.';
    } else {
        $emailEsc = mysqli_real_escape_string($conn, $email);
        $algo = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT;

        $sql = "SELECT id, name, password FROM users WHERE email = '$emailEsc' LIMIT 1";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $user = mysqli_fetch_assoc($res);
            $stored = $user['password'];
            $isValid = false;
            $rehash = false;

            if (password_verify($password, $stored)) {
                $isValid = true;
                if (password_needs_rehash($stored, $algo)) {
                    $rehash = true;
                }
            } else {
                if (preg_match('/^[a-f0-9]{32}$/i', $stored) === 1 && md5($password) === $stored) {
                    $isValid = true;
                    $rehash = true;
                }
            }

            if ($isValid) {
                if ($rehash) {
                    $newHash = password_hash($password, $algo);
                    if ($newHash !== false) {
                        $newHashEsc = mysqli_real_escape_string($conn, $newHash);
                        mysqli_query($conn, "UPDATE users SET password = '$newHashEsc' WHERE id = " . (int) $user['id']);
                    }
                }

                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: ' . $APP_PATH . '/');
                exit;
            }
        }

        $msg = 'Invalid email or password.';
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Commerza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="<?php echo $APP_PATH; ?>/assets/css/style.css" rel="stylesheet">
</head>

<body class="auth-body">
    <div class="auth-shell">
        <div class="auth-card">
            <div class="auth-brand">
                <a class="brand-mark" href="<?php echo $APP_PATH; ?>/">Commerza</a>
                <p class="auth-subtitle">Welcome back. Login to continue shopping.</p>
            </div>

            <?php if ($msg !== '') { ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
            <?php } ?>

            <form method="post" action="<?php echo $APP_PATH; ?>/login" class="auth-form" id="loginForm">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control auth-input" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="loginPassword" class="form-control auth-input" required>
                        <button class="btn btn-outline-secondary toggle-pass" type="button" data-target="loginPassword">Show</button>
                    </div>
                    <div class="strength-wrap">
                        <div class="strength-meter">
                            <span id="loginStrengthBar" class="strength-bar"></span>
                        </div>
                        <small id="loginStrengthLabel" class="strength-label">Strength: -</small>
                    </div>
                </div>
                <button class="btn btn-brand w-100 auth-submit" name="login" type="submit" data-default-text="Login">Login</button>
                <div class="auth-links">
                    <a href="<?php echo $APP_PATH; ?>/signup">Create account</a>
                    <a href="<?php echo $APP_PATH; ?>/">Back to shop</a>
                </div>
            </form>
        </div>
    </div>
    <script src="<?php echo $APP_PATH; ?>/assets/js/auth.js"></script>
</body>

</html>