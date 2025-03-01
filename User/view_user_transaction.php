<?php
// The following code displays user's transfers.
session_start();
include("navBar.php");
$userId = $_SESSION['user_id'];


include("db_conn2.php");
$sql = "SELECT amount_sent, amount_received, trans_date, trans_state FROM transactions where senderacc_id = $userId or receiveracc_id = $userId";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row1 = $stmt->fetchAll(PDO::FETCH_ASSOC);



$sql2 = "SELECT useracc.fname, useracc.lname FROM useracc 
    INNER JOIN currencyacc ON currencyacc.user_id = useracc.user_id 
    INNER JOIN transactions ON transactions.senderacc_id = currencyacc.user_id
    where senderacc_id = $userId 
    or receiveracc_id = $userId
    GROUP BY transaction_id";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);



$sql3 = "SELECT useracc.fname, useracc.lname FROM useracc 
    INNER JOIN currencyacc ON currencyacc.user_id = useracc.user_id 
    INNER JOIN transactions ON transactions.receiveracc_id = currencyacc.user_id
    where senderacc_id = $userId
    or receiveracc_id = $userId
    GROUP BY transaction_id";
$stmt3 = $pdo->prepare($sql3);
$stmt3->execute();
$row3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);






$sql4 = "SELECT currency.currency_name
    FROM currency 
    INNER JOIN transactions ON transactions.currency_from_id = currency.currency_id
    where senderacc_id = $userId 
    or receiveracc_id = $userId
    GROUP BY transaction_id";
$stmt4 = $pdo->prepare($sql4);
$stmt4->execute();
$row4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);






$sql5 = "SELECT currency.currency_name
    FROM currency 
    INNER JOIN transactions ON transactions.currency_to_id = currency.currency_id
    where senderacc_id = $userId 
    or receiveracc_id = $userId
    GROUP BY transaction_id";
$stmt5 = $pdo->prepare($sql5);
$stmt5->execute();
$row5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);




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

<div class="container2">
    <main>
        <h2>Transaction History</h2><br>
        <table id="table-design">
            <thead>
                <td>Sender</td>
                <td>Receiver</td>
                <td>Currency From</td>
                <td>Currency To</td>
                <td>Amount Sent</td>
                <td>Amount Received</td>
                <td>Transaction Date</td>
                <td>Transaction State</td>
            </thead>
            <?php
            for ($i = 0; $i < count($row1); $i++):
                ?>
                <tr>
                    <td>
                        <?php
                        echo $row2[$i]['fname'] . " " . $row2[$i]['lname'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row3[$i]['fname'] . " " . $row3[$i]['lname'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row4[$i]['currency_name'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row5[$i]['currency_name'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row1[$i]['amount_sent'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row1[$i]['amount_received'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row1[$i]['trans_date'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row1[$i]['trans_state'];
                        ?>
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
    </main>
</div>