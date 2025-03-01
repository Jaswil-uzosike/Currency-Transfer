<?php
// The code below is to implement the exchange rate using the currency information gotten from the other page.
// The code also displays the effect of this exchange rate on the amount sent.
session_start();
include ('navbar.php');

$userId = $_SESSION['user_id'];
$ams = $_SESSION['amount_sent'];
$currf = $_SESSION['currency_from'];
$currfs = $_SESSION['currency_from_sign'];
$currtemp = $_SESSION['currency_to'];
$currt;
$currts;


if ($currtemp == 1) {
    $currt = "GBP";
    $currts = "£";
}
if ($currtemp == 2) {
    $currt = "USD";
    $currts = "$";
}
if ($currtemp == 3) {
    $currt = "EUR";
    $currts = "€";
}

if ($currf == 1) {
    $currf = "GBP";
}
if ($currf == 2) {
    $currf = "USD";
}
if ($currf == 3) {
    $currf = "EUR";
}


$exr = 0;
$conv;
$tfr;
$tfp;

include ("db_conn2.php");
$sql = "SELECT  exchange_value FROM exchange_rates";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row1 = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);


$GBPUSD = $row1[0];
$GBPEUR = $row1[1];
$EURUSD = $row1[2];
$USDGBP = $row1[4];
$EURGBP = $row1[3];
$USDEUR = $row1[5];

if ($currf != $currt) {
    $tfr = 0.05;
    $tfp = "(5%) = ";
} else {
    $tfr = 0.01;
    $tfp = "(1%) = ";
}

$tf = round($ams * $tfr, 2);
$rams = round($ams - $tf, 2);

if ($currf == $currt) {
    $exr = 1;

    if ($currf == "EUR") {
        $tf = round($tf * $EURGBP, 2);
    }
    if ($currf == "USD") {
        $tf = round($tf * $USDGBP, 2);
    }
}
if ($currf == "GBP" && $currt == "EUR") {
    $exr = $GBPEUR;
}
if ($currf == "GBP" && $currt == "USD") {
    $exr = $GBPUSD;
}
if ($currf == "USD" && $currt == "EUR") {
    $exr = $USDEUR;
    $tf = round($tf * $USDGBP, 2);
}
if ($currf == "EUR" && $currt == "GBP") {
    $exr = $EURGBP;
    $tf = round($tf * $EURGBP, 2);
}
if ($currf == "EUR" && $currt == "USD") {
    $exr = $EURUSD;
    $tf = round($tf * $EURGBP, 2);
}
if ($currf == "USD" && $currt == "GBP") {
    $exr = $USDGBP;
    $tf = round($tf * $USDGBP, 2);
}

$_SESSION['profit'] = $tf;

$conv = round($rams * $exr, 2);
$_SESSION['amount_received'] = $conv;

if (isset ($_POST['submit'])) {
    header('Location: sendpage3.php');
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

<form class="form" method="post">
    <fieldset>
        <legend>Transfer Review</legend>
        <div class="form-item">
            <main>
                <h6 style="font-family: laila;">
                    <?php echo "Amount Transferring = " . $currfs . $ams; ?><br>
                </h6>
            </main>
        </div>
        <div class="form-item">
            <main>
                <h6 style="font-family: laila;">
                    <?php echo "Transfer Fee" . $tfp . $currfs . $tf; ?><br>
                </h6>
            </main>
        </div>
        <div class="form-item">
            <main>
                <h6 style="font-family: laila;">
                    <?php echo "Exchange Rate to Apply = " . $exr; ?><br>
                </h6>
            </main>
        </div>
        <div class="form-item">
            <main>
                <h6 style="font-family: laila;">
                    <?php echo "Receiver Gets: " . $currts . $conv; ?><br>
                </h6>
            </main>
        </div>

        <div>
            <input class="form-button" type="submit" value="Next" name="submit">
        </div>

        <br>
        <div>
            <a href="sendpage1.php" class="form-button">Back</a>
        </div>
    </fieldset>
</form>