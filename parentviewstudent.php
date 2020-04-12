<!--this will be the location where the parent will be able to view their child -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Children List</title>
    </head>
    <body>
        <a href="index.php">Student Management System</a>
        <h2>Your children list</h2>
        <?php
        $user = $_SESSION['user'];
        if (NULL == $user) {
            header("Location: index.php");
            exit;
        }
        include_once 'database.php';
        include_once 'utils.php';
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

        if ($method == "GET") {
            $parentId = $user['id'];
            $children = get_children_by_parent_id($parentId);
            ?>
            <table>
                <tr>
                    <th>User Id</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Change Profile</th>
                </tr>
                <?php foreach ($children as $child) { ?>
                    <tr>
                        <td><?php echo $child['id'] ?></th>
                        <td><?php echo $child['email'] ?></td>
                        <td><?php echo $child['name'] ?></td>
                        <td><?php echo $child['phone'] ?></td>

                        <td><a href='parentmodifystudent.php?id=<?php echo $child['id'] ?>'>Change Profile</a></td>
                    </tr>

                <?php }
                ?>
            </table>
        <?php }
        ?>
    </body>
</html>
