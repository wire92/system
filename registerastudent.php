<!-- this will be student registration page-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Student Registration</title>
    </head>
    <body>
        <a href="index.php">Student Management System</a>
        <h2> Register as Student</h2>
        <?php
        include_once 'database.php';
        include_once 'utils.php';
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

        if ($method == "POST") {
            $email = filterParameter(INPUT_POST, "email");
            $pemail = filterParameter(INPUT_POST, "pemail");
            $password = filterParameter(INPUT_POST, "password");
            $user = get_user_by_email_and_password($email, $password);
            $parent = get_user_by_email($pemail);
            if (NULL == $user) {
                $grade = filterParameter(INPUT_POST, "grade");
                $phone = filterParameter(INPUT_POST, "phone");
                $role = filterParameter(INPUT_POST, "role");
                $name = filterParameter(INPUT_POST, "name");
                $cpassword = filterParameter(INPUT_POST, "cpassword");
                if (register_student($email, $parent, $password, $name, $role, $phone, $grade)) {
                    echo ' Sucess:New student successfully created';
                } else {
                    echo 'unable to create new Student user';
                }
            } else {
                echo 'There is a present student with the given email id';
            }
        }
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <table>
                <tr>
                    <td>
                        <label>Email: </label>
                        <input type="text" name="email" placeholder="Enter your email">
                    </td>
                    <td>
                        <label>Parent Email: </label>
                        <input type="text" name="pemail" placeholder="Enter your parent email">

                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Name: </label>
                        <input type="text" name="name" placeholder="Enter your name">

                    </td>
                    <td>
                        <label>Password: </label>
                        <input type="password" name="password" placeholder="Enter Password">

                    </td>

                </tr>
                <tr>
                    <td>
                        <label>Re-Enter: </label>
                        <input type="password" name="cpassword" placeholder="Confirm Password">
                    </td>
                    <td>
                        <b>Role</b>
                        <select name='role'>
                            <option value="1">No</option>
                            <option value="2">Mentee</option>
                            <option value="3">Mentor</option>
                            <option value="4">Both</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <b>Grade</b>
                        <select name="grade">
                            <option value="6">Sixth</option>
                            <option value="7">Seventh</option>
                            <option value="8">Eighth</option>
                            <option value="9">Nineth</option>
                            <option value="10">Tenth</option>
                            <option value="11">Eleventh</option>
                            <option value="12">Twelth</option>
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
