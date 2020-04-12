<!--This will be a place in which the parent will be able to change their profile, child profile, or view section -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Parent Homepage</title>
    </head>
    <body>
        <?php
        $user = $_SESSION['user'];
        if (NULL == $user) {
            header("Location: index.php");
            exit;
        }
        ?>
        <a href="index.php">Return to main Parent screen</a> <a href="logout.php">Logout</a> <br/><br/>
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
                    Child
                </th>
                <th>
                    Profile
                </th>
                <th>
                    <a href="parentviewstudent.php">Change Your child Profile</a>
                </th>
            </tr>
            <tr>
                <th>
                    Parent
                </th>
                <th>
                    Section
                </th>
                <th>
                    <a href="viewsectionasparent.php">View Sections</a>
                </th>
            </tr>
        </table>
    </body>
</html>
