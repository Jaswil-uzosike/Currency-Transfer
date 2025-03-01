<?php
include("navbar2.php");

include("db_conn2.php");
$sql = "SELECT sa_id, sa_description, transaction_id FROM suspicious";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row1 = $stmt->fetchAll(PDO::FETCH_ASSOC);



$sql2 = "SELECT admins.fname, admins.lname FROM admins 
    INNER JOIN suspicious ON suspicious.admin_id = admins.admin_id";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);




?>




<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../CSS/style2.css">
    <title>Suspicious Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</head>

<div class="container2">
    <main>
        <h2>FLAGGED ACTIVITY</h2><br>
        <table id="table-design">
            <thead>
                <td>NUMBER</td>
                <td>DESCRIPTION</td>
                <td>TRANSACTION ID</td>
                <td>ASSIGNED ADMIN</td>
            </thead>
            <?php
            for ($i = 0; $i < count($row1); $i++):
                ?>
                <tr>
                    <td>
                        <?php
                        echo $row1[$i]['sa_id'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row1[$i]['sa_description'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row1[$i]['transaction_id'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row2[$i]['fname'] . " " . $row2[$i]['lname'];
                        ?>
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
    </main>
</div>