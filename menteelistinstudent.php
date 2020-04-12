<!--this will be where the student will be able to view the mentee list -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Student Mentor List</title>
    </head>
    <body>
        <a href="index.php">Student Management System</a>
        <h2>Mentee list</h2>
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

            $meeting_mentees = get_mentees($user['id']);
            if (null != $meeting_mentees) {
                ?>
                <table>
                    <?php
                    foreach ($meeting_mentees as $key => $value) {
                        ?>
                        <tr><td><?php echo "Course Name: ", $key ?></td></tr>
                        <table>
                            <tr><th>Mentee Id</th><th>Name of Mentee </th></tr>
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
