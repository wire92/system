<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- This is where the admim will be adding the meeting such as capacity, name,
announcement, start date, which class, and or time slot  -->
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Set Meetings</title>
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
        <h2>Set Meetings </h2>
        <?php
        include_once 'database.php';
        include_once 'utils.php';
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        if ($method == "POST") {
            $opr = filterParameter(INPUT_POST, "opr");
            if ($opr == 'add_meeting') {
                $name = filterParameter(INPUT_POST, "name");
                $announcement = filterParameter(INPUT_POST, "announcement");
                $start_date = filterParameter(INPUT_POST, "start_date");
                $capacity = filterParameter(INPUT_POST, "capacity");
                $group = filterParameter(INPUT_POST, "group");
                $time_slot = filterParameter(INPUT_POST, "time_slot");
                if (add_meetings($name, $announcement, $start_date, $capacity, $group, $time_slot)) {
                    echo 'Success: Meeting added successfully';
                } else {
                    echo 'Unable to add meeting';
                }
            } else if ($opr == 'cancel_meeting') {
                $meet_id = filterParameter(INPUT_POST, "meet_id");
                if (cancel_meeting($meet_id)) {
                    echo 'Success: meeting cancelled';
                } else {
                    echo 'Unable to cancel meeting';
                }
            }
        }
        $groups = get_all_groups();
        $time_slots = get_all_timeslots();
        ?>
        <form method="post"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="opr" value="add_meeting">

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
                        <b>Announcement:</b>
                    </td>
                    <td>
                        <input type="text" name="announcement">
                    </td>
                </tr>

                <tr>
                    <td>
                        <b>Start Date:</b>
                    </td>
                    <td>
                        <input type="date" name="start_date">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Capacity:</b>
                    </td>
                    <td>
                        <input type="number" name="capacity" >
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Group:</b>
                    </td>
                    <td>
                        <?php
                        if (NULL != $groups) {
                            ?>
                            <select name="group">
                                <?php
                                foreach ($groups as $group) {
                                    ?>
                                    <option value="<?php echo $group['group_id'] ?>"><?php echo $group['name'] ?></option>
                                <?php }
                                ?>
                            </select>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Time Slot:</b>
                    </td>
                    <td>
                        <?php
                        if (NULL != $time_slots) {
                            ?>
                            <select name="time_slot">
                                <?php
                                foreach ($time_slots as $time_slot) {
                                    ?>
                                    <option value="<?php echo $time_slot['time_slot_id'] ?>"><?php echo $time_slot['day_of_week'] . " " . $time_slot['start_time'] . " - " . $time_slot['end_time']; ?></option>
                                <?php }
                                ?>
                            </select>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Submit">
                    </td>
                </tr>
            </table>
        </form>
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
                    Mentor Grade Required
                </th>
                <th>
                    Mentee Grade Required
                </th>
                <th>
                    Enrolled Mentor
                </th>
                <th>
                    Enrolled Mentee
                </th>
                <th>
                    Upload Material
                </th>
                <th>
                    Assign a Mentee
                </th>
                <th>
                    Assign a Mentor
                </th>
                <th>
                    Cancel Meeting
                </th>
            </tr>
            <?php
            $meetings = get_all_meetings();

            if (NULL != $meetings) {
                foreach ($meetings as $meeting) {
                    ?>
                    <tr>
                        <td style="padding-right: 20px"><?php echo $meeting['name']; ?></td>
                        <td style="padding-right: 20px"><?php echo $meeting['meet_name']; ?></td>
                        <td style="padding-right: 20px"><?php echo $meeting['date']; ?></td>
                        <td style="padding-right: 20px"><?php echo $meeting['time_slot']; ?></td>
                        <td style="padding-right: 20px"><?php echo $meeting['capacity']; ?></td>
                        <td style="padding-right: 20px"><?php echo $meeting['mentor_grade_req']; ?></td>
                        <td style="padding-right: 20px"><?php echo $meeting['mentee_grade_req']; ?></td>
                        <td style="padding-right: 20px"><?php echo $meeting['enrolled_mentor']; ?></td>
                        <td style="padding-right: 20px"><?php echo $meeting['enrolled_mentee']; ?></td>
                        <td style="padding-right: 20px"><a href="sendmaterialasadmin.php?meet_id=<?php echo $meeting['meet_id']; ?>">Upload Material</a></td>
                        <td style="padding-right: 20px"><a href="setadminmentee.php?meet_id=<?php echo $meeting['meet_id']; ?>">Assign Mentee</a></td>
                        <td style="padding-right: 20px"><a href="setadminmentor.php?meet_id=<?php echo $meeting['meet_id']; ?>">Assign Mentor</a></td>

                        <td style="padding-right: 20px">
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="opr" value="cancel_meeting">
                                <input type="hidden" name="meet_id" value="<?php echo $meeting['meet_id']; ?>">
                                <input type="submit" value="Cancel">
                            </form>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </body>
</html>
