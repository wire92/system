<!-- this will be the logout page in which it will return to homepage-->
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'];
if (NULL != $user) {
    $_SESSION['user'] = NULL;
    header("Location: index.php");
    exit();
}
?>
