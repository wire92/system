<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<!--
This will allow the students to view the material posted by the admin
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
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
        <h2> View Meeting Material</h2>
        <?php
        include_once 'database.php';
        include_once 'utils.php';
        $meet_id = filterParameter(INPUT_GET, "meet_id");
        if (NULL == $meet_id) {
            $meet_id = filterParameter(INPUT_POST, "meet_id");
        }
        if (NULL == $meet_id) {
            header("Location: student_section_management.php");
            exit;
        }
        ?>
        <h2> Posted Meeting Material</h2>
        <table>
            <table>
                <tr>
                    <th>
                        Session Name
                    </th>
                    <th>
                        Session Date
                    </th>
                    <th>
                        Announcement
                    </th>
                    <th>
                        Material Title
                    </th>

                    <th>
                        Author
                    </th>
                    <th>
                        Type
                    </th>
                    <th>
                        Url
                    </th>
                    </th>
                    <th>
                        Notes
                    </th>
                    <th>
                        Assigned Date

                </tr>
                <?php
                $assigned_materials = get_all_material($meet_id);

                if (NULL != $assigned_materials) {
                    foreach ($assigned_materials as $assigned_material) {
                        ?>
                        <tr>
                            <td style="padding-right: 20px"><?php echo $assigned_material['meet_name']; ?></td>
                            <td style="padding-right: 20px"><?php echo $assigned_material['date']; ?></td>
                            <td style="padding-right: 20px"><?php echo $assigned_material['announcement']; ?></td>
                            <td style="padding-right: 20px"><?php echo $assigned_material['title']; ?></td>
                            <td style="padding-right: 20px"><?php echo $assigned_material['author']; ?></td>
                            <td style="padding-right: 20px"><?php echo $assigned_material['type']; ?></td>
                            <td style="padding-right: 20px"><?php echo $assigned_material['url']; ?></td>
                            <td style="padding-right: 20px"><?php echo $assigned_material['notes']; ?></td>
                            <td style="padding-right: 20px"><?php echo $assigned_material['assigned_date']; ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
    </body>
</html>
