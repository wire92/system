<!-- This is where the student will be able to view the mentor list -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Student View Mentor</title>
    </head>
    <body>
        <a href="index.php">Student Management System</a>
        <h2>Mentor list</h2>
        <?php
        $user = $_SESSION['user'];
        if (NULL == $user) {
            header("Location: index.php");
            exit;
        }
        include_once 'database.php';
        include_once 'utils.php';
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        if ($method == 'GET') {

            $meeting_mentors = get_mentors($user['id']);
            if (null != $meeting_mentors) {
                ?>
                <table>
                    <?php
                    foreach ($meeting_mentors as $key => $value) {
                        ?>
                        <br>
                        <tr><td><?php echo "Course Name: ", $key ?></td></tr>
                        <table>
                            <tr><th>Mentor Id</th><th>Name of Mentor</th></tr>
                            <?php
                            foreach ($value as $val) {
                                ?>
                                <tr>
                                    <td><?php echo $val['id'] ?></td>
                                    <td><?php echo $val['name'] ?></td>
                                </tr>

                            <?php }
                            ?>
                        </table>
                        <?php
                    }
                    ?>
                </table>
                <?php
            }
        }
        ?>
    </body>
</html>
