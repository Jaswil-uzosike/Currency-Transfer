<?php
session_start();
include ('navbar2.php');
$accountid = $_SESSION['account_id'];
$link = $_SESSION['usern'];


include ("db_conn2.php");



$errormax = '';
$allFields = "yes";

if (isset($_POST['submit'])) {

    if ("" === $_POST['max']) {
        $errormax = "Max income  cannot be null";
        $allFields = "no";
    }

    include ("db_conn2.php");

    $sql = "SELECT type_id FROM currencyacc WHERE account_id = $accountid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {

        if ($row['type_id'] == 1) {
            $max = 10000;
        } elseif ($row['type_id'] == 2) {
            $max = 50000;
        } else {
            $max = 5000;
        }
    }
    $pdo = null;

    if ($_POST['max'] > $max) {
        $errormax = "Max income is over the limit for this account type";
        $allFields = "no";
    }


    if ($allFields == "yes") {

        include_once ('db_conn.php');

        $stmt = $mysqli->prepare("UPDATE currencyacc SET max_income = ? WHERE account_id = $accountid");
        $stmt->bind_param('d', $newmax);
        $newmax = $_POST['max'];
        $stmt->execute();

        if ($stmt) {
            echo "<script>alert('Update Successful'); window.location.href='admin_view_user_details.php?usn=$link';</script>";
            exit;
        } else {
            echo "<script>alert('Update Unsuccessful'); </script>";
        }



    }
}



?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</head>

<form class="form" method="post">
    <fieldset>
        <legend>MAX INCOME LIMIT RESET</legend>
        <div>
            <div class="form-item">
                <input placeholder="New Max Income" type="number" name="max">
                <span class="danger">
                    <?php echo $errormax; ?>
                </span>
            </div>
            <div>
                <input class="form-button" type="submit" value="Confirm" name="submit">
            </div>
            <br>
            <div>
                <a href="admin_view_user_details.php?usn=<?php echo $link; ?>" class="form-button">Back</a>
            </div>
        </div>
    </fieldset>
</form>

</html>