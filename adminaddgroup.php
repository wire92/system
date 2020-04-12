<!--this will allow the admin add in the group -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Group in Admin</title>
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
        <h2>Add Group </h2>
        <?php
        include_once 'database.php';
        include_once 'utils.php';
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        if ($method == "POST") {
            $name = filterParameter(INPUT_POST, "name");
            $desc = filterParameter(INPUT_POST, "desc");
            $mentee_grade_req = filterParameter(INPUT_POST, "mentee_grade_req");
            $mentor_grade_req = filterParameter(INPUT_POST, "mentor_grade_req");
            if (add_group($name, $desc, $mentee_grade_req, $mentor_grade_req)) {
                echo 'Group is added successfully';
            } else {
                echo 'Failed to add group';
            }
        }
        ?>

        <form method="post"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table>
                <tr>
                    <td>
                        <b>Name:</b>
                    </td>
                    <td>
                        <input type="text" name="name">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Description:</b>
                    </td>
                    <td>
                        <input type="text" name="desc">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Mentee Grade Required:</b>
                    </td>
                    <td>
                        <select name="mentee_grade_req">
                            <option value="0">No</option>
                            <option value="6">Sixth</option>
                            <option value="7">Seventh</option>
                            <option value="8">Eighth</option>
                            <option value="9">Nineth</option>
                            <option value="10">Tenth</option>
                            <option value="11">Eleventh</option>
                            <option value="12">Twelth</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Mentor Grade Required:</b>
                    </td>
                    <td>
                        <select name="mentor_grade_req">
                            <option value="0">No</option>
                            <option value="6">Sixth</option>
                            <option value="7">Seventh</option>
                            <option value="8">Eighth</option>
                            <option value="9">Nineth</option>
                            <option value="10">Tenth</option>
                            <option value="11">Eleventh</option>
                            <option value="12">Twelth</option>
                        </select>
                    </td>
                </tr>
                <tr><td> <input type="submit" value="Submit"></td></tr>
            </table>
        </form>
        <table>
            <tr>
                <th>Group Id</th>
                <th>Group Name</th>
                <th>Group Description</th>
                <th>Mentor Grade Required</th>
                <th>Mentee Grade Required</th>
            </tr>
            <?php
            $groups = get_all_groups();
            if (NULL != $groups) {
                foreach ($groups as $group) {
                    ?>
                    <tr>
                        <td><?php echo $group['group_id']; ?></td>
                        <td><?php echo $group['name']; ?></td>
                        <td><?php echo $group['description']; ?></td>
                        <td><?php echo $group['mentor_grade_req']; ?></td>
                        <td><?php echo $group['mentee_grade_req']; ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </body>
</html>
