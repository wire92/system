<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!--this will allow the admin to assign the mentee  -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Assign Mentee</title>
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
        <h2> Assign Mentee</h2>
        <?php
        include_once 'database.php';
        include_once 'utils.php';
        $meet_id = filterParameter(INPUT_GET, "meet_id");
        if (NULL == $meet_id) {
            $meet_id = filterParameter(INPUT_POST, "meet_id");
        }
        if (NULL == $meet_id) {
            header("Location: setmeetinginadmin.php");
            exit;
        }
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        if ($method == "POST") {
            $meet_id = filterParameter(INPUT_POST, "meet_id");
            $mentee_id = filterParameter(INPUT_POST, "mentee_id");
          $count = get_enroll_as_mentee_count($mentee_id, $meet_id);
            if ($count == 0) {
                if (enroll_mentee($meet_id, $mentee_id)) {
                    echo 'Assigned mentee successfully';
                } else {
                    echo 'Unable to assign mentee';
                }
            } else {
                echo 'Already enrolled as mentee.';
            }
        }
        ?>
        <?php
        $mentees = get_meeting_mentees($meet_id);
        ?>
        <form method="post"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="meet_id" value="<?php echo $meet_id ?>">
            <table>
                <tr>
                    <td>
                        <b>Mentees:</b>
                        <?php
                        if (NULL != $mentees) {
                            ?>
                            <select name="mentee_id">
                                <?php
                                foreach ($mentees as $mentee) {
                                    ?>
                                    <option value="<?php echo $mentee['id'] ?>"><?php echo $mentee['name'] ?></option>
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
                        <input type="submit" value="Submit"></td>
                </tr>
            </table>
        </form>

    </body>
</html>
