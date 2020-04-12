<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- This is where the student will be able to select teach as mentor or mentee-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Student Section </title>
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
                        <th>
                            Teach as Mentor
                        </th>
                        <th>
                            Enrolled as Mentee
                        </th>
                        <th>
                            View Material
                        </th>
                        <th>
                          Quit metting
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
                            <td style="padding-right: 20px"><?php
                                if ($secion['enroll_as_Mentor'] == "") {
                                    if ($secion['isMentor']) {
                                        ?>
                                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                            <input type="hidden" name="opr" value="enroll_mentor">
                                            <input type="hidden" name="meet_id" value="<?php echo $secion['meet_id']; ?>">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <input type="submit" value="Teach">
                                        </form>
                                        <?php
                                    } else {
                                        echo 'N/A';
                                    }
                                } else {
                                    echo 'N/A';
                                }
                                ?>

                            </td>
                            <td style="padding-right: 20px">
                                <?php
                                if ($secion['enroll_as_Mentee'] == "") {
                                    if ($secion['isMentee']) {
                                        ?>
                                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                            <input type="hidden" name="opr" value="enroll_mentee">
                                            <input type="hidden" name="meet_id" value="<?php echo $secion['meet_id']; ?>">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <input type="submit" value="Enroll">

                                        </form>
                                        <?php
                                    } else {
                                        echo 'N/A';
                                    }
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                            <td style="padding-right: 20px"><a href="checkmaterial.php?meet_id=<?php echo $secion['meet_id']; ?>">View Material</a></td>
                            <td style="padding-right: 20px">
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input type="hidden" name="opr" value="quit_meeting">
                                    <input type="hidden" name="meet_id" value="<?php echo $secion['meet_id']; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <?php
                                    if ($secion['enroll_as_Mentee'] != "" || $secion['enroll_as_Mentor'] != "")

                                        ?>
                                    <input type="submit" value="Quit">

                                </form>
                            </td>

                        </tr>
                    <?php }
                    ?>
                </table>
                <?php
            } else {
                echo 'Unable to find any sections';
                ?>

                <?php
            }
        } else if ($method == 'POST') {
            $opr = filterParameter(INPUT_POST, "opr");
            $meet_id = filterParameter(INPUT_POST, "meet_id");
            $id = filterParameter(INPUT_POST, "user_id");
            if ($opr == 'enroll_mentee') {
                if (enroll_mentee($meet_id, $id)) {
                    echo 'Success: Mentee assigned  successfully';
                } else {
                    echo 'Unable to assign mentee';
                }
            } else if ($opr == 'enroll_mentor') {
                if (enroll_mentor($meet_id, $id)) {
                    echo 'Success: Mentor assigned successfully';
                } else {
                    echo 'Unable to assign mentor';
                }
            } else if ($opr == 'quit_meeting') {
                if (quit_meeting($meet_id, $id, $user['isMentor'], $user['isMentee'])) {
                    echo 'Success: Student quits the meeting';
                } else {
                    echo 'Unable to quit meeting';
                }
            }
        }
        ?>

    </body>
</html>
