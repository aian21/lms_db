<?php
session_start();
require_once('classes/database.php');
$con = new database();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // Check the user type
    if ($_SESSION['user_type'] === 1) {
        // Admin user, redirect to admin homepage
        header('Location: admin_homepage.php');
        exit();
    } else {
        // Regular user, redirect to the homepage
        header('Location: homepage.php');
        exit();
    }
}

$sweetAlertConfig = ""; // Initialize SweetAlert script variable

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
  
    $user = $con->loginUser($email, $password);

    if ($user) {

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_FN'] = $user['user_FN'];
        $_SESSION['user_type'] = (int)$user['user_type']; // Ensure user_type is cast as integer

        // Check if the user is admin
        if ($_SESSION['user_type'] === 1) {
            $sweetAlertConfig = "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Admin Login Successful',
                    text: 'Welcome, Admin!',
                    confirmButtonText: 'Continue'
                }).then(() => {
                    window.location.href = 'admin_homepage.php';
                });
            </script>";
        } else {
            // Non-admin users
            $sweetAlertConfig = "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful',
                    text: 'Welcome, " . addslashes(htmlspecialchars($user['user_FN'])) . "!',
                    confirmButtonText: 'Continue'
                }).then(() => {
                    window.location.href = 'homepage.php';
                });
            </script>";
        }

    } else {
        $sweetAlertConfig = "<script>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: 'Invalid email or password.'
            });
        </script>";
    }
}

?>




<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="./package/dist/sweetalert2.css">
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <title>Library Management System</title>
</head>
<body>
  <div class="container custom-container rounded-3 shadow p-4 bg-light mt-5">
    <h3 class="text-center mb-4">Login</h3>
    <form method="post" action="" novalidate>
      <div class="form-group mb-3">
        <label for="email">Email:</label>
        <input type="email" class="form-control" name="email" placeholder="Enter email" required>
      </div>
      <div class="form-group mb-3">
        <label for="password">Password:</label>
        <input type="password" class="form-control" name="password" placeholder="Enter password" required>
      </div>

      <button type="submit" name="login" class="btn btn-primary w-100 py-2">Login</button>
      <div class="text-center mt-4">
        <a href="admin_homepage.php" class="text-decoration-none">Access Admin Homepage (For now)</a><br>
        <a href="registration.php" class="text-decoration-none">Don't have an account? Register here</a>
      </div>
    </form>

    <script src="./package/dist/sweetalert2.js"></script>
    <?php echo $sweetAlertConfig; ?>
  </div>

<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>
