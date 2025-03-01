<?php
// The code below displays the page where user is asked if they want to close their account
session_start();
include ('navbar.php');
$userId = $_SESSION['user_id'];


if (isset($_POST['close'])) {

    include ("db_conn2.php");

    $sql = 'DELETE FROM bank WHERE user_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $pdo = null;

    include ("db_conn.php");
    $stmt2 = $mysqli->prepare("UPDATE useracc SET acc_status = ? WHERE user_id = $userId");
    $stmt2->bind_param('s', $newstatus);

    $newstatus = "closed";
    $stmt2->execute();

    if ($stmt && $stmt2) {
        echo "<script>alert('Account Closure Successful'); window.location.href='../index.php';</script>";
    }else{
        echo "<script>alert('Account Closure Unsuccessful'); </script>";
    }

}

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>How Much Do You Want To Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</head>

<div>
    <main class="form">
        <h4 style="color: red;">Are you sure want to close this account?</h4><br>

        <div class="row">
            <form method="post">
                <div>
                    <input type="submit" value="Close" class="form-button-del" name="close">
                </div>
                <br>
                <div>
                    <a href="homepage.php" class="form-button">Back</a>
                </div>
            </form>
        </div>
    </main>
</div>