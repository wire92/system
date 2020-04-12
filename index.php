<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- this will be the main page-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
    </head>
    <body>
        <?php
        $user = NULL;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }
        if (NULL == $user) {
            ?>
            <table>
                <tr><td> <h2>Please Register</h2></td><td> <h2>Please Login</h2></td></tr>
                <tr><td><a href="registerastudent.php">Register as Student</a></td><td><a href="loginastuent.php">Login as Student</a><br/></td></tr>
                <tr><td><a href="registerasparent.php">Register as Parent</a></td><td> <a href="loginasparent.php">Login as Parent</a><br/></td></tr>
                <tr><td></td><td><a href="loginasadmin.php">Login as Admin</a><td></tr>
            </table>

            <?php
        } else {
            ?>

            Welcome <?php echo $_SESSION['user']['email'] ?><br/><br/>
            You are successfully logged in!<br/><br/>
            <?php
            if ($_SESSION['user']['isAdmin']) {
                ?>
                <a  href="adminhomepage.php">Click To admin Homepage</a><br/><br/>
                <a  href="logout.php">Logout?</a>  <?php
            } elseif ($_SESSION['user']['isParent']) {
                ?>
                <a  href="parenthomepage.php">Click To parent Homepage</a><br/><br/>
                <a  href="logout.php">Logout?</a>  <?php
            } elseif ($_SESSION['user']['isStudent']) {
                ?>
                <a  href="studenthomepage.php">Click To student Homepage</a><br/><br/>
                <a  href="logout.php">Logout?</a>  <?php
            }
        }
        ?>
    </body>
</html>
