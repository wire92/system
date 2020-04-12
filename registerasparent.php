<!-- this will be the parent registration page-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Parent Registration</title>
    </head>
    <body>
        <a href="index.php">Student Management System</a>
        <div></div>
        <h2> Register as Parent</h2>
        <?php
        include_once 'database.php';
        include_once 'utils.php';
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

        if ($method == "POST") {
            $email = filterParameter(INPUT_POST, "email");
            $password = filterParameter(INPUT_POST, "password");
            $user = get_user_by_email_and_password($email, $password);
            if (NULL == $user) {
                $phone = filterParameter(INPUT_POST, "phone");

                $role = filterParameter(INPUT_POST, "role");
                $name = filterParameter(INPUT_POST, "name");
                $cpassword = filterParameter(INPUT_POST, "cpassword");
                if (register_parent($email, $password, $name, $role, $phone)) {
                    echo 'Success: New Parent successfully created ';
                } else {
                    echo 'unable to create new parent user';
                }
            } else {
                echo 'There is a parent with the given given email id';
            }
        }
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table>
                <tr>
                    <td>
                        <label>Email: </label>
                        <input type="text" name="email" placeholder="Enter your email">
                    </td>
                    <td>
                        <label>Name: </label>
                        <input type="text" name="name" placeholder="Enter your name">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Password: </label>
                        <input type="password" name="password" placeholder="Enter Password">
                    </td>
                    <td>
                        <label>Re-Enter: </label>
                        <input type="password" name="cpassword" placeholder="Confirm Password">
                    </td>
                </tr>

                <tr>
                    <td>
                        <b>Role</b>
                        <select name="role">
                            <option>No</option>
                            <option>Moderator</option>
                        </select>
                    </td>
                    <td>
                        <label>Phone: </label>
                        <input type="text" name="phone" placeholder="Enter your phone">
                    </td>
                </tr>
                <tr><td colspan="2"><input type="submit" value="Submit"></td></tr>
            </table>
        </form>
    </body>
</html>
