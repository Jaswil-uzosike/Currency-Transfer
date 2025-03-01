<?php

session_start();
include ("navbar2.php");

$rateId;

include ("db_conn2.php");

$sql3 = "SELECT rate_id FROM exchange_rates WHERE exchange_name = :exn";
$stmt3 = $pdo->prepare($sql3);
$stmt3->bindParam(':exn', $_GET['exn'], SQLITE3_TEXT);
$stmt3->execute();
$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);

if ($row3) {
    $rateId = $row3["rate_id"];
}
$pdo = null;

$errorvalue = "";
$allFields = "yes";

if (isset($_POST['submit'])) {

    if ("" === $_POST['value']) {
        $errorvalue = "Field cannot be null";
        $allFields = "no";
    }


    if ($allFields == "yes") {
        include ("db_conn.php");
        $stmt2 = $mysqli->prepare("UPDATE exchange_rates SET exchange_value = ? WHERE rate_id = $rateId");
        $stmt2->bind_param('d', $newvalue);

        $newvalue = $_POST['value'];
        $stmt2->execute();

        if ($stmt2) {
            echo "<script>alert('Exchange Rate Update Successful'); window.location.href='admin_view_exchange_rates.php';</script>";
            exit;
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
        <legend>RATE UPDATE</legend>
        <div>
            <div class="form-item">
                <input placeholder="New exchange rate" type="number" step="0.01" name="value">
                <span class="danger">
                    <?php echo $errorvalue; ?>
                </span>
            </div>
            <div>
                <input class="form-button" type="submit" value="Confirm" name="submit">
            </div>
            <br>
            <div>
                <a href="admin_view_exchange_rates.php" class="form-button">Back</a>
            </div>
        </div>
    </fieldset>
</form>

</html>