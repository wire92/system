<!--this will be the admin  homepage  -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Homepage</title>
    </head>
    <body>
        <?php
        $user = $_SESSION['user'];
        if (NULL == $user) {
            header("Location: index.php");
            exit;
        }
        ?>
        <a href="index.php">Return to main Admin screen</a> <a href="logout.php">Logout</a> <br/><br/>
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
                    Admin
                </th>
                <th>
                    Group
                </th>
                <th>
                    <a href="adminaddgroup.php">Add Group </a>
                </th>
            </tr>
            <tr>
                <th>
                    Admin
                </th>
                <th>
                    Time Slot
                </th>
                <th>
                    <a href="settimeinadmin.php">Time Slot </a>
                </th>
            </tr>
            <tr>
                <th>
                    Admin
                </th>
                <th>
                    Meetings
                </th>
                <th>
                    <a href="setmeetinginadmin.php">Set Meetings </a>
                </th>
            </tr>


        </table>
    </body>
</html>
