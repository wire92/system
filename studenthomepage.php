<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- This is student home page in which it will give them the options to change profile, view sections, view mentor or mentee  -->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Student DashBoard</title>
    </head>
    <body>
        <?php
        $user = $_SESSION['user'];
        if (NULL == $user) {
            header("Location: index.php");
            exit;
        }
        ?>
        <a href="index.php">Return to main Student screen</a> <a href="logout.php">Logout</a> <br/><br/>
        Your Grade level is <?php echo $_SESSION['user']['grade'] ?>!<br/><br/>
        <table>
            <tr>
                <th>
                    User Type
                </th>
                <th>

                </th>
                <th>
                    Action
                </th>
            </tr>

            <tr>
                <th>
                    User
                </th>
                <th>
                    Profile
                </th>
                <th>
                    <a href="modifyadmin.php">Change Your Profile</a>
                </th>
            </tr>
            <tr>
                <th>
                    Student
                </th>
                <th>
                    Section
                </th>
                <th>
                    <a href="selectstudent.php">View Sections</a>

                </th>
            </tr>
            <tr>
                <th>
                    View
                </th>
                <th>
                    Mentor
                </th>
                <th>
                    <a href="mentorlistinstudent.php">View Mentor</a>
                </th>
            </tr>
            <tr>
                <th>
                    View
                </th>
                <th>
                    Mentee
                </th>
                <th>
                    <a href="menteelistinstudent.php">View Mentee</a>
                </th>
            </tr>

        </table>
    </body>
</html>
