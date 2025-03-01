<?php
session_start();
include ("navbar2.php");

$userId;

include ("db_conn2.php");

$sql3 = "SELECT user_id FROM useracc WHERE username = :usn";
$stmt3 = $pdo->prepare($sql3);
$stmt3->bindParam(':usn', $_GET['usn'], SQLITE3_TEXT);
$stmt3->execute();
$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);

if ($row3) {
    $userId = $row3["user_id"];
}
$pdo = null;
if (isset($_POST['submit'])) {

    include ("db_conn.php");
    $stmt2 = $mysqli->prepare("UPDATE useracc SET acc_status = ? WHERE user_id = $userId");
    $stmt2->bind_param('s', $status);

    $status = $_POST['status'];
    $stmt2->execute();

    if ($stmt2) {
        echo "<script>alert('Status Change Successful'); window.location.href='admin_view_flagged_accounts.php';</script>";
        exit;
    }
}

?>



<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>Status Update Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</head>

<form class="form" method="post">
    <fieldset>
        <legend>STATUS RESET</legend>
        <div class="form-item">

            <select name="status">
                <option value="active">active</option>
                <option value="suspended">suspended</option>
                <option value="deactivated">deactivated</option>
            </select>

        </div>
        <div>
            <input class="form-button" type="submit" value="Confirm" name="submit">
        </div>
        <br>
        <div>
            <a href="admin_view_flagged_accounts.php" class="form-button">Back</a>
        </div>
    </fieldset>
</form>

</html>