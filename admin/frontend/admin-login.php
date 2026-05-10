<?php
include 'db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: ' . $APP_PATH . '/admin-panel');
    exit;
}

$msg = '';
if (isset($_POST['admin_login'])) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($username === '' || $password === '') {
        $msg = 'Please fill all fields.';
    } else {
        $uEsc = mysqli_real_escape_string($conn, $username);
        $algo = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT;

        $sql = "SELECT id, username, password FROM admins WHERE username = '$uEsc' LIMIT 1";
        $res = mysqli_query($conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $admin = mysqli_fetch_assoc($res);
            $stored = $admin['password'];
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
                        mysqli_query($conn, "UPDATE admins SET password = '$newHashEsc' WHERE id = " . (int) $admin['id']);
                    }
                }

                $_SESSION['admin_id'] = (int) $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: ' . $APP_PATH . '/admin-panel');
                exit;
            }
        }

        $msg = 'Invalid admin credentials.';
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Mini E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $APP_PATH; ?>/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="form-box">
                    <h3 class="mb-3">Admin Login</h3>
                    <?php if ($msg !== '') { ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($msg); ?></div>
                    <?php } ?>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="admin_login" class="btn btn-brand">Login</button>
                        <a href="<?php echo $APP_PATH; ?>/" class="btn btn-link">Back to Store</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>