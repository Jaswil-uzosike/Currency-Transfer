<?php include("navbar.php"); ?>
<?php
// The following code retrieves and displays the exchange rates in a tabular format.

include("db_conn2.php");
$sql = "SELECT exchange_name, exchange_value FROM exchange_rates";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row1 = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../CSS/style2.css">
    <title>View Exchange Rates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</head>

<div class="container2">
    <main>
        <h2>Exchange Rates</h2><br>
        <table id="table-design">
            <thead>
                <td>EXCHANGE NAME</td>
                <td>VALUE</td>
            </thead>
            <?php
            for ($i = 0; $i < count($row1); $i++):
                ?>
                <tr>
                    <td>
                        <?php
                        echo $row1[$i]['exchange_name'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row1[$i]['exchange_value'];
                        ?>
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
    </main>
</div>