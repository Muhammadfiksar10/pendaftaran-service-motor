<?php
include 'config.php';

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "UPDATE booking SET status = '$status' WHERE id = $id";
    mysqli_query($conn, $query);
}

header("Location: admin.php");
exit();
?>