<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- This is where the admin will be able to set the time by picking day of the week
and do start and end time -->
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Set TimeSlot </title>
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
        <h2>Time Slot </h2>
        <?php
        include_once 'database.php';
        include_once 'utils.php';
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        if ($method == "POST") {
            $day_of_week = filterParameter(INPUT_POST, "day_of_week");
            $start_time = filterParameter(INPUT_POST, "start_time");
            $end_time = filterParameter(INPUT_POST, "end_time");
            if (add_timeslot($day_of_week, $start_time, $end_time)) {
                echo 'Time slot is added successfully';
            } else {
                echo 'Unable to add time slot';
            }
        }
        ?>

        <form method="post"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table>
                <tr>
                    <td>
                        Day of Week
                    </td>
                    <td>
                        <select name="day_of_week">
                            <option value="S">Sunday</option>
                            <option value="M">Monday</option>
                            <option value="T">Tuesday</option>
                            <option value="W">Wednesday</option>
                            <option value="Th">Thusday</option>
                            <option value="F">Friday</option>
                            <option value="Sa">Saturday</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Start Time
                    </td>
                    <td>
                        <input type="time" name="start_time">
                    </td>
                </tr>
                <tr>
                    <td>
                        End Time
                    </td>
                    <td>
                        <input type="time" name="end_time">
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
                <th>Time Slot Id</th>
                <th>Day Of Week</th>
                <th>Start time</th>
                <th>End Time</th>
            </tr>
            <?php
            $time_slots = get_all_timeslots();
            if (NULL != $time_slots) {
                foreach ($time_slots as $time_slot) {
                    ?>
                    <tr>
                        <td><?php echo $time_slot['time_slot_id']; ?></td>
                        <td><?php echo $time_slot['day_of_week_full']; ?></td>
                        <td><?php echo $time_slot['start_time']; ?></td>
                        <td><?php echo $time_slot['end_time']; ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </body>
</html>
