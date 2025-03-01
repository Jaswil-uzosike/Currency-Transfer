<?php
session_start();
include ("navbar2.php");



$userId;
$fname;
$lname;
$username;
$email;
$phone;

include ("db_conn2.php");

$sql3 = "SELECT user_id FROM useracc WHERE username = :usn";
$stmt3 = $pdo->prepare($sql3);
$stmt3->bindParam(':usn', $_GET['usn'], SQLITE3_TEXT);
$stmt3->execute();
$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);

if ($row3) {
    $userId = $row3["user_id"];
    $_SESSION["admin_update_user_id"] = $userId;
}


$sql = "SELECT fname, lname, username, email, phone_number FROM useracc WHERE user_id = $userId";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row1 = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row1) {
    $fname = $row1["fname"];
    $lname = $row1["lname"];
    $username = $row1["username"];
    $email = $row1['email'];
    $phone = $row1['phone_number'];
    $_SESSION['usern'] = $username;
}


$sql2 = "SELECT account_no, acc_name, bank_name, currency_id FROM bank WHERE user_id = $userId";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

if ($row2) {
    $bankname = $row2["bank_name"];
    $accountno = $row2["account_no"];
    $accountname = $row2["acc_name"];
    if ($row2['currency_id'] == 1) {
        $curr = "GBP";
    }
    if ($row2['currency_id'] == 2) {
        $curr = "USD";
    }
    if ($row2['currency_id'] == 3) {
        $curr = "EUR";
    }

}

$sql4 = "SELECT account_id, balance, max_income, type_id FROM currencyacc WHERE user_id = $userId and currency_id = 1";
$stmt4 = $pdo->prepare($sql4);
$stmt4->execute();
$row4 = $stmt4->fetch(PDO::FETCH_ASSOC);

if ($row4) {
    $GBPaccountid = $row4["account_id"];
    $GBPstate = "Available";
    $GBPbalance = $row4["balance"];
    $GBPmax_income = $row4["max_income"];
    if ($row4['type_id'] == 1) {
        $GBPaccounttype = "Personal";
    }
    if ($row4['type_id'] == 2) {
        $GBPaccounttype = "Business";
    }
    if ($row4['type_id'] == 3) {
        $GBPaccounttype = "Student";
    }
    $userCanClickButton = true;

} else {
    $GBPstate = "Unavailable";
    $GBPbalance = "null";
    $GBPmax_income = "null";
    $GBPaccounttype = "null";
    $userCanClickButton = false;
}


$sql5 = "SELECT account_id, balance, max_income, type_id FROM currencyacc WHERE user_id = $userId and currency_id = 2";
$stmt5 = $pdo->prepare($sql5);
$stmt5->execute();
$row5 = $stmt5->fetch(PDO::FETCH_ASSOC);

if ($row5) {
    $USDaccountid = $row5["account_id"];
    $USDstate = "Available";
    $USDbalance = $row5["balance"];
    $USDmax_income = $row5["max_income"];
    if ($row5['type_id'] == 1) {
        $USDaccounttype = "Personal";
    }
    if ($row5['type_id'] == 2) {
        $USDaccounttype = "Business";
    }
    if ($row5['type_id'] == 3) {
        $USDaccounttype = "Student";
    }
    $userCanClickButton2 = true;

} else {
    $USDstate = "Unavailable";
    $USDbalance = "null";
    $USDmax_income = "null";
    $USDaccounttype = "null";
    $userCanClickButton2 = false;
}


$sql6 = "SELECT account_id, balance, max_income, type_id FROM currencyacc WHERE user_id = $userId and currency_id = 3";
$stmt6 = $pdo->prepare($sql6);
$stmt6->execute();
$row6 = $stmt6->fetch(PDO::FETCH_ASSOC);

if ($row6) {
    $EURaccountid = $row6["account_id"];
    $EURstate = "Available";
    $EURbalance = $row6["balance"];
    $EURmax_income = $row6["max_income"];
    if ($row6['type_id'] == 1) {
        $EURaccounttype = "Personal";
    }
    if ($row6['type_id'] == 2) {
        $EURaccounttype = "Business";
    }
    if ($row6['type_id'] == 3) {
        $EURaccounttype = "Student";
    }
    $userCanClickButton3 = true;

} else {
    $EURstate = "Unavailable";
    $EURbalance = "null";
    $EURmax_income = "null";
    $EURaccounttype = "null";
    $userCanClickButton3 = false;
}

if (isset($_POST['submit'])) {
    $_SESSION['account_id'] = $GBPaccountid;
    header('Location: update_max_limit.php');
}

if (isset($_POST['submit2'])) {
    $_SESSION['account_id'] = $USDaccountid;
    header('Location: update_max_limit.php');
}

if (isset($_POST['submit3'])) {
    $_SESSION['account_id'] = $EURaccountid;
    header('Location: update_max_limit.php');
}

$disabled = "";



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../CSS/style2.css">
    <title>View Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</head>

<div class="container">
    <div class="card cart">
        <a href="admin_view_flagged_accounts.php">
            <img border="0" alt="User Icon" src="../Resources/back_arrow.svg" width="30" height="30">
        </a>
        <label class="title"> PROFILE DETAILS</label>
        <div class="steps">
            <div class="step">
                <div>
                    <span>PERSONAL DETAILS</span>
                    <p>Full Name:
                        <?php echo $fname . " " . $lname; ?>
                    </p>
                    <p>Username:
                        <?php echo $username; ?>
                    </p>

                </div>
                <hr>
                <div>
                    <span>CONTACT DETAILS</span>
                    <p>Email:
                        <?php echo $email; ?>
                    </p>
                    <p>Phone Number:
                        <?php echo $phone; ?>
                    </p>
                </div>
                <hr>
                <div class="promo">
                    <span>BANK DETAILS</span>
                    <p>Bank Name:
                        <?php echo $bankname; ?>
                    </p>
                    <p>Acount Name:
                        <?php echo $accountname; ?>
                    </p>
                    <p>Account Number:
                        <?php echo $accountno; ?>
                    </p>
                    <p>Account Currency:
                        <?php echo $curr; ?>
                    </p>
                </div>
                <hr>
                <div class="checkout">
                    <span>CURRENCY ACCOUNT DETAILS</span>
                    <p> </p>
                    <span>GBP ACCOUNT STATUS:
                        <?php echo $GBPstate; ?>
                        </p>
                        <p>Balance:
                            <?php echo $GBPbalance; ?>
                        </p>
                        <p>Account Type:
                            <?php echo $GBPaccounttype; ?>
                        </p>
                        <p>Income Max Limit:
                            <?php echo '£' . $GBPmax_income; ?>
                        <form method="post">
                            <input class="checkout-btn" type="submit" value="Update Limit" name="submit" <?php echo $userCanClickButton ? '' : 'disabled'; ?>>
                        </form>
                        </p>

                        <hr>
                        <span>USD ACCOUNT STATUS:
                            <?php echo $USDstate; ?>
                            </p>
                            <p>Balance:
                                <?php echo $USDbalance; ?>
                            </p>
                            <p>Account Type:
                                <?php echo $USDaccounttype; ?>
                            </p>
                            <p>Income Max Limit:
                                <?php echo '$' . $USDmax_income; ?>
                            <form method="post">
                                <input class="checkout-btn" type="submit" value="Update Limit" name="submit2" <?php echo $userCanClickButton2 ? '' : 'disabled'; ?>>
                            </form>
                            </p>

                            <hr>
                            <span>EUR ACCOUNT STATUS:
                                <?php echo $EURstate; ?>
                                </p>
                                <p>Balance:
                                    <?php echo $EURbalance; ?>
                                </p>
                                <p>Account Type:
                                    <?php echo $EURaccounttype; ?>
                                </p>
                                <p>Income Max Limit:
                                    <?php echo '€' . $EURmax_income; ?>
                                <form method="post">
                                    <input class="checkout-btn" type="submit" value="Update Limit" name="submit3" <?php echo $userCanClickButton3 ? '' : 'disabled'; ?>>
                                </form>
                                </p>
                </div>

            </div>
        </div>
    </div>


</div>