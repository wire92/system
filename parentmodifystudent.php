<!--Parents can modify student data -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Child Profile </title>
    </head>
    <body>
        <?php
        $user = $_SESSION['user'];
        if (NULL == $user) {
            header("Location: index.php");
            exit;
        }
        ?>
        <a href="index.php">Student Management System</a>
        <h2>Child profile Change By Parent</h2>
        <?php
        include_once 'database.php';
        include_once 'utils.php';
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

        if ($method == "POST") {
            $email = filterParameter(INPUT_POST, "email");
            $password = filterParameter(INPUT_POST, "password");
            $phone = filterParameter(INPUT_POST, "phone");
            $name = filterParameter(INPUT_POST, "name");
            $cpassword = filterParameter(INPUT_POST, "cpassword");

            $user = get_user_by_email($email);
            if (NULL != $user) {
                if (update_user($password, $name, $phone, $user['id'])) {
                    $_SESSION["user"] = get_user_by_email($email);
                    echo 'Sucess: Profile Successfully updated';
                } else {
                    echo 'unable to Update the profile';
                }
            } else {
                echo 'Student does not exist';
            }
        } if ($method == "GET") {
            $user = null;
            $id = filterParameter(INPUT_GET, "id");
            if (NULL != $id) {
                $user = get_user_by_id($id);
                if (NULL == $user) {
                    header("Location: parentviewstudent.php");
                    exit;
                }
            } else {
                header("Location: parentviewstudent.php");
                exit;
            }
        }
        ?>
        <form method="post"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="email" value="<?php echo $user['email'] ?>">
            <table>
                <tr>
                    <td>
                        <b>Name:</b>
                    </td>
                    <td>
                        <input type="text" name="name" value="<?php echo $_SESSION['user']['name'] ?>">
                    </td>
                </tr>

                <tr>
                    <td>
                        <b>Pass:</b>
                    </td>
                    <td>
                        <input type="password" name="password">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Confirm:</b>
                    </td>
                    <td>
                        <input type="password" name="cpassword">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Phone:</b>
                    </td>
                    <td>
                        <input type="text" name="phone" value="<?php echo $_SESSION['user']['phone'] ?>">
                    </td>
                </tr>
                <tr>

                    <td colspan="2">
                        <input type="submit" value="Submit">
                    </td>
                </tr>

            </table>
        </form>
    </body>
</html>
