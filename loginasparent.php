<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!--this will be where they can login as parent -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Parent Login</title>
    </head>
    <body>
        <a href="index.php">Student Management System</a>
        <h2>Login</h2>

        <?php
        include_once 'database.php';
        include_once 'utils.php';
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

        if ($method == "POST") {
            $email = filterParameter(INPUT_POST, "email");
            $password = filterParameter(INPUT_POST, "password");
            $user = get_user_by_email_and_password($email, $password);

            if (NULL != $user) {
                $_SESSION["user"] = $user;
                header("Location: index.php");
                exit;
            } else {
                $_SESSION["message"] = 'Please try again!! Invalid:user does not exist';
            }
        }
        ?>
        <div><?php
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                $_SESSION['message'] = '';
            }
            ?></div>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <label>Email: </label>
               <input type="text" name="email" placeholder="Enter your email">
                <label>Password: </label>
                <input type="password" name="password" placeholder="Enter Password">
            <input type="submit" value="Submit">
            <br>
            <br>
              <a href="registerasparent.php"> Register as parent here</a><br/><br/>
        </form>
    </body>
</html>
