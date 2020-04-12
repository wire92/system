<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!--This is the location in which the admin will be able to send materials  -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Upload Material</title>
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
        <h2> Post Meeting Material</h2>
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
            $name = filterParameter(INPUT_POST, "name");
            $author = filterParameter(INPUT_POST, "author");
            $type = filterParameter(INPUT_POST, "type");
            $url = filterParameter(INPUT_POST, "url");
            $notes = filterParameter(INPUT_POST, "notes");
            if (add_material($name, $author, $type, $url, $notes, $meet_id)) {
                echo 'Material uploaded successfully';
            } else {
                echo 'Unable to upload material';
            }
        }
        ?>

        <form method="post"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="meet_id" value="<?php echo $meet_id ?>">
            <table>
                <tr>
                    <td>
                        <b>Material Name:</b>
                    </td>
                    <td>
                        <input type="text" name="name">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Author:</b>
                    </td>
                    <td>
                        <input type="text" name="author">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Type:</b>
                    </td>
                    <td>
                        <input type="text" name="type">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Url:</b>
                    </td>
                    <td>
                        <input type="text" name="url" >
                    </td>
                </tr>

                <tr>
                    <td>
                        <b>Notes:</b>
                    </td>
                    <td>
                        <textarea cols="50" rows="10" name="notes"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Submit">
                    </td>
                </tr>
            </table>
        </form>
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
                    <th>
                        Notes
                    </th>
                    <th>
                        Assigned Date
                    </th>
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
