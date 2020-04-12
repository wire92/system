<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- this will be where the admin will be able to asign the mentors -->
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title> Assign Mentor</title>
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
        <h2> Assign Mentor</h2>
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
            $mentor_id = filterParameter(INPUT_POST, "mentor_id");
          $count = get_enroll_as_mentor_count($mentor_id, $meet_id);
            if ($count == 0) {
                if (enroll_mentor($meet_id, $mentor_id)) {
                    echo 'Assigned mentor successfully';
                } else {
                    echo 'Unable to assign mentor';
                }
            } else {
                echo 'Already enrolled as mentor';
            }
        }
        ?>
        <?php
        $mentees = get_meeting_mentors($meet_id);
        ?>
        <form method="post"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="meet_id" value="<?php echo $meet_id ?>">
            <table>
                <tr>
                    <td>  </td>
                </tr>
                <tr>
                    <td>
                        <b>Mentors:</b>
                        <?php
                        if (NULL != $mentees) {
                            ?>
                            <select name="mentor_id">
                                <?php
                                foreach ($mentees as $mentee) {
                                    ?>
                                    <option value="<?php echo $mentee['id'] ?>"><?php echo $mentee['name'] ?></option>
                                <?php }
                                ?>
                            </select><br/>
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
