<?php
// The code below creates user charts using Chart.js and information from the transaction table in the database.
// There were meant to be 2 charts but the chart showing money received is faulty and not enough time to debug hence it was commented out.

$userId = $_SESSION['user_id'];
// Set database connection variables

$current_date = date('Y-m-d');
$current_month = date('m', strtotime($current_date));
$state = "success";
$gbp = 1;
$usd = 2;
$eur = 3;

$host = 'localhost';
$db = 'my_stage2';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Set DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Set options
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Create a new PDO instance
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

// Prepare and execute SQL statement
$sql = "SELECT sum(amount_sent) AS sent_amount, dayofmonth(trans_date) AS day_of_month
FROM transactions
where month(trans_date) = :val1
And senderacc_id = :val2
and trans_state = :val3
and currency_from_id = :val4 
group by trans_date";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':val1', $current_month);
$stmt->bindParam(':val2', $userId);
$stmt->bindParam(':val3', $state);
$stmt->bindParam(':val4', $gbp);
$stmt->execute();


// Fetch data and encode it as JSON
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

$day = array();
$sent = array();

if ($row) {
    // Output data of each row
    for ($i = 0; $i < count($row); $i++):
        $day[] = $row[$i]["day_of_month"];
        $sent[] = $row[$i]["sent_amount"];
    endfor;
} else {
    //echo "0 results";
}



$encodedday = json_encode($day);
$encodedsent = json_encode($sent);

$sql3 = "SELECT sum(amount_sent) AS sent_amount, dayofmonth(trans_date) AS day_of_month
FROM transactions
where month(trans_date) = :val1
And senderacc_id = :val2
and trans_state = :val3
and currency_from_id = :val4
group by trans_date";
$stmt3 = $pdo->prepare($sql3);
$stmt3->bindParam(':val1', $current_month);
$stmt3->bindParam(':val2', $userId);
$stmt3->bindParam(':val3', $state);
$stmt3->bindParam(':val4', $usd);
$stmt3->execute();


// Fetch data and encode it as JSON
$row3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

$dayusd = array();
$sentusd = array();

if ($row3) {
    // Output data of each row
    for ($i = 0; $i < count($row3); $i++):
        $dayusd[] = $row3[$i]["day_of_month"];
        $sentusd[] = $row3[$i]["sent_amount"];
    endfor;
} else {
   // echo "0 results";
}



$encodeddayusd = json_encode($dayusd);
$encodedsentusd = json_encode($sentusd);

$sql4 = "SELECT sum(amount_sent) AS sent_amount, dayofmonth(trans_date) AS day_of_month
FROM transactions
where month(trans_date) = :val1
And senderacc_id = :val2
and trans_state = :val3
and currency_from_id = :val4
group by trans_date";
$stmt4 = $pdo->prepare($sql4);
$stmt4->bindParam(':val1', $current_month);
$stmt4->bindParam(':val2', $userId);
$stmt4->bindParam(':val3', $state);
$stmt4->bindParam(':val4', $eur);
$stmt4->execute();


// Fetch data and encode it as JSON
$row4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

$dayeur = array();
$senteur = array();

if ($row4) {
    // Output data of each row
    for ($i = 0; $i < count($row4); $i++):
        $dayeur[] = $row4[$i]["day_of_month"];
        $senteur[] = $row4[$i]["sent_amount"];
    endfor;
} else {
    //echo "0 results";
}



$encodeddayeur = json_encode($dayeur);
$encodedsenteur = json_encode($senteur);

/*

// Prepare and execute SQL statement
$sql2 = "SELECT sum(amount_received) AS received_amount, dayofmonth(trans_date) AS day_of_month
FROM transactions
where month(trans_date) = :val1
And receiveracc_id = :val2
and trans_state = :val3 
and currency_from_id = :val4
group by trans_date";
$stmt2 = $pdo->prepare($sql2);
$stmt2->bindParam(':val1', $current_month);
$stmt2->bindParam(':val2', $userId);
$stmt2->bindParam(':val3', $state);
$stmt2->bindParam(':val4', $gbp);
$stmt2->execute();


// Fetch data and encode it as JSON
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dayrec = array();
$received = array();

if ($row2) {
    // Output data of each row
    for ($i = 0; $i < count($row2); $i++):
        $day2[] = $row2[$i]["day_of_month"];
        $received[] = $row2[$i]["sent_amount"];
    endfor;
} else {
    //echo "0 results";
}



$encodeddayrec = json_encode($dayrec);
$encodedreceived = json_encode($received);

$sql5 = "SELECT sum(amount_received) AS received_amount, dayofmonth(trans_date) AS day_of_month
FROM transactions
where month(trans_date) = :val1
And receiveracc_id = :val2
and trans_state = :val3 
and currency_from_id = :val4
group by trans_date";
$stmt5 = $pdo->prepare($sql5);
$stmt5->bindParam(':val1', $current_month);
$stmt5->bindParam(':val2', $userId);
$stmt5->bindParam(':val3', $state);
$stmt5->bindParam(':val4', $usd);
$stmt5->execute();


// Fetch data and encode it as JSON
$row5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);

$dayrecusd = array();
$receivedusd = array();

if ($row5) {
    // Output data of each row
    for ($i = 0; $i < count($row5); $i++):
        $dayrecusd[] = $row5[$i]["day_of_month"];
        $receivedusd[] = $row5[$i]["sent_amount"];
    endfor;
} else {
    //echo "0 results";
}



$encodeddayrecusd = json_encode($dayrecusd);
$encodedreceivedusd = json_encode($receivedusd);

$sql6 = "SELECT sum(amount_received) AS received_amount, dayofmonth(trans_date) AS day_of_month
FROM transactions
where month(trans_date) = :val1
And receiveracc_id = :val2
and trans_state = :val3 
and currency_from_id = :val4
group by trans_date";
$stmt6 = $pdo->prepare($sql6);
$stmt6->bindParam(':val1', $current_month);
$stmt6->bindParam(':val2', $userId);
$stmt6->bindParam(':val3', $state);
$stmt6->bindParam(':val4', $eur);
$stmt6->execute();


// Fetch data and encode it as JSON
$row6 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dayreceur = array();
$receivedeur = array();

if ($row6) {
    // Output data of each row
    for ($i = 0; $i < count($row6); $i++):
        $dayreceur[] = $row6[$i]["day_of_month"];
        $receivedeur[] = $row6[$i]["sent_amount"];
    endfor;
} else {
    //echo "0 results";
}



$encodeddayreceur = json_encode($dayreceur);
$encodedreceivedeur = json_encode($receivedeur);

*/

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart with Chart.js</title>
    <link rel="stylesheet" type="text/css" href="../CSS/style2.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<center>
    <h2 style="font-family: 'Work Sans', sans-serif">
        <?php echo "MONTH'S TRANSFER CHARTS"; ?>
    </h2>
</center>

<body>

    <canvas class="chart" id="salesChart" width="100px" height="25px"></canvas>

    <script>
        // Parse the PHP-encoded JSON data to JavaScript variables
        var day1 = JSON.parse('<?php echo $encodedday; ?>');
        var sent1 = JSON.parse('<?php echo $encodedsent; ?>');
        var day2 = JSON.parse('<?php echo $encodeddayusd; ?>');
        var sent2 = JSON.parse('<?php echo $encodedsentusd; ?>');
        var day3 = JSON.parse('<?php echo $encodeddayeur; ?>');
        var sent3 = JSON.parse('<?php echo $encodedsenteur; ?>');

        // Merge day1 and day2 into a single set of labels for the X-axis
        var allDays = [...new Set([...day1, ...day2, ...day3])].sort(); // Combine and remove duplicates

        // Create datasets where days without transactions are set to zero
        var mergedSent1 = allDays.map(day => day1.includes(day) ? sent1[day1.indexOf(day)] : 0);
        var mergedSent2 = allDays.map(day => day2.includes(day) ? sent2[day2.indexOf(day)] : 0);
        var mergedSent3 = allDays.map(day => day3.includes(day) ? sent3[day3.indexOf(day)] : 0);

        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'bar', // Change this to 'line', 'pie', etc. depending on what type of chart you want
            data: {
                labels: allDays,
                datasets: [{
                    label: 'GBP',
                    data: mergedSent1,
                    backgroundColor: 'rgba(244, 63, 94, 0.7)',
                    borderColor: 'rgba(244, 63, 94, 1)',
                    borderWidth: 1
                },
                {
                    label: 'USD',
                    data: mergedSent2,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                },
                {
                    label: 'EUR',
                    data: mergedSent3,
                    backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1
                }
            ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount Sent'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Day of the Month'
                        }
                    }
                }
            }
        });
    </script>

</body>
<!--
<body>

    <canvas class="chart" id="salesChart2" width="100px" height="25px"></canvas>

    <script>
        // Parse the PHP-encoded JSON data to JavaScript variables
        
        var recday1 = JSON.parse('<?php /*echo $encodeddayrec; ?>');
        var rec1 = JSON.parse('<?php echo $encodedreceived; ?>');
        var recday2 = JSON.parse('<?php echo $encodeddayrecusd; ?>');
        var rec2 = JSON.parse('<?php echo $encodedreceivedusd; ?>');
        var recday3 = JSON.parse('<?php echo $encodeddayreceur; ?>');
        var rec3 = JSON.parse('<?php echo $encodedreceivedeur; */?>');

        // Merge day1 and day2 into a single set of labels for the X-axis
        var allDays2 = [...new Set([...recday1, ...recday2, ...recday3])].sort(); // Combine and remove duplicates

        // Create datasets where days without transactions are set to zero
        var mergedrec1 = allDays2.map(day => recday1.includes(day) ? rec1[recday1.indexOf(day)] : 0);
        var mergedrec2 = allDays2.map(day => recday2.includes(day) ? rec2[recday2.indexOf(day)] : 0);
        var mergedrec3 = allDays2.map(day => recday3.includes(day) ? rec3[recday3.indexOf(day)] : 0);

        var ctx = document.getElementById('salesChart2').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'bar', // Change this to 'line', 'pie', etc. depending on what type of chart you want
            data: {
                labels: allDays2,
                datasets: [{
                    label: 'GBP',
                    data: mergedrec1,
                    backgroundColor: 'rgba(244, 63, 94, 0.7)',
                    borderColor: 'rgba(244, 63, 94, 1)',
                    borderWidth: 1
                },
                {
                    label: 'USD',
                    data: mergedrec2,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                },
                {
                    label: 'EUR',
                    data: mergedrec3,
                    backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1
                }
            ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount Received'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Day of the Month'
                        }
                    }
                }
            }
        });
    </script>

</body>
    -->
</html>