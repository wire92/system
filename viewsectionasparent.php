<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- In this the parent will be able to see the sections which will provide them all the information -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Parent Sections</title>
    </head>
    <body>
        <a href="index.php">Student Management System</a>
        <h2>Section list</h2>
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
            $secions = get_session_data($user['id']);
            if (null != $secions) {
                ?>
                <table>
                    <tr>
                        <th>
                            Course Name
                        </th>
                        <th>
                            Section Name
                        </th>
                        <th>
                            Start Date
                        </th>
                        <th>
                            Time Slot
                        </th>

                        <th>
                            Capacity
                        </th>
                        <th>
                            Mentor Req
                        </th>
                        <th>
                            Mentee Req
                        </th>
                        <th>
                            Enrolled Mentor
                        </th>
                        <th>
                            Enrolled Mentee
                        </th>
                    </tr>
                    <?php foreach ($secions as $secion) {
                        ?>
                        <tr>
                            <td style="padding-right: 20px"><?php echo $secion['name']; ?></td>
                            <td style="padding-right: 20px"><?php echo $secion['meet_name']; ?></td>
                            <td style="padding-right: 20px"><?php echo $secion['date']; ?></td>
                            <td style="padding-right: 20px"><?php echo $secion['time_slot']; ?></td>
                            <td style="padding-right: 20px"><?php echo $secion['capacity']; ?></td>
                            <td style="padding-right: 20px"><?php echo $secion['mentor_grade_req']; ?></td>
                            <td style="padding-right: 20px"><?php echo $secion['mentee_grade_req']; ?></td>
                            <td style="padding-right: 20px"><?php echo $secion['enrolled_mentor']; ?></td>
                            <td style="padding-right: 20px"><?php echo $secion['enrolled_mentee']; ?></td>
                        </tr>
                    <?php }
                    ?>
                </table>
                <?php
            } else {
                echo 'Unable to find sections';
                ?>

                <?php
            }
        }
        ?>

    </body>
</html>
