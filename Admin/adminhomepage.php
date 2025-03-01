<?php
session_start();
include ("navbar2.php");
$adminID = $_SESSION['admin_id'];

$name = "";
$bal = 0;
$transactionnum = 0;
$current_date = date('Y-m-d');
$current_month = date('m', strtotime($current_date));
$current_year = date('Y', strtotime($current_date));
include ("db_conn2.php");


$sql = 'SELECT SUM(trans_profit_GBP) AS total_sum, COUNT(profit_id) AS total_count FROM profit';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($user) {
	$bal = $user["total_sum"];
	$transactionnum = $user["total_count"];
} else {
	$bal = 0;
	$transactionnum = 0;
}
$pdo = null;

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die ("Connection failed: " . $conn->connect_error);
}


$sql2 = 'SELECT sum(trans_profit_GBP) AS profit, dayofmonth(trans_date) AS month_day
FROM profit where month(trans_date) = 4 group by trans_date';
$result = $conn->query($sql2);

$day = array();
$profitmade = array();

if ($result->num_rows > 0) {
	// Output data of each row
	while ($row = $result->fetch_assoc()) {
		$day[] = $row["month_day"];
		$profitmade[] = $row["profit"];
	}
} else {
	echo "0 results";
	echo $current_month;
}



$encodedday = json_encode($day);
$encodedprofitmade = json_encode($profitmade);

$sql3 = 'SELECT sum(trans_profit_GBP) AS profit, dayofyear(trans_date) AS year_day
FROM profit where year(trans_date) = 2024 group by trans_date';
$result2 = $conn->query($sql3);

$day2 = array();
$profitmade2 = array();

if ($result2->num_rows > 0) {
	// Output data of each row
	while ($row2 = $result2->fetch_assoc()) {
		$day2[] = $row2["year_day"];
		$profitmade2[] = $row2["profit"];
	}
} else {
	//echo "0 results";
}



$encodedday2 = json_encode($day2);
$encodedprofitmade2 = json_encode($profitmade2);
?>

<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="../CSS/style2.css">
	<title>Admin Homepage</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
		</script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<div>
	<main>
		<center>
			<h2 style="font-family: 'Work Sans', sans-serif;">
				<?php echo "Welcome Admin " ?>
			</h2>
		</center>
		<center>
			<h2 style="font-family: 'Work Sans', sans-serif;">
				<?php
				echo "Total Number of Transactions: " . $transactionnum;
				?>
			</h2>
		</center>
		<center>
			<h2 style="font-family: 'Work Sans', sans-serif;">
				<?php
				echo "Total Profit: £ " . $bal;
				?>
			</h2>
		</center>

	</main>

	<body>

		<canvas class="chart" id="salesChart" width="100px" height="25px"></canvas>

		<script>
			// Parse the PHP-encoded JSON data to JavaScript variables
			var day = JSON.parse('<?php echo $encodedday; ?>');
			var profit = JSON.parse('<?php echo $encodedprofitmade; ?>');
			var day2 = JSON.parse('<?php echo $encodedday2; ?>');
			var profit2 = JSON.parse('<?php echo $encodedprofitmade2; ?>');

			var ctx = document.getElementById('salesChart').getContext('2d');
			var salesChart = new Chart(ctx, {
				type: 'bar', // Change this to 'line', 'pie', etc. depending on what type of chart you want
				data: {
					labels: day,
					datasets: [{
						label: 'Profit',
						data: profit,
						backgroundColor: [
							// Add colors for each bar here
							'rgba(255, 0, 0, 1)',
							// ...more colors as needed
						],
						borderColor: [
							// Border color for each bar
							'rgba(255, 0, 0, 1)',
							// ...more colors as needed
						],
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						y: {
							beginAtZero: true,
							title: {
								display: true,
								text: 'Profit'
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
	<br>
	<br>

	<body>

		<canvas class="chart" id="salesChart2" width="100px" height="25px"></canvas>

		<script>
			// Parse the PHP-encoded JSON data to JavaScript variables
			var day2 = JSON.parse('<?php echo $encodedday2; ?>');
			var profit2 = JSON.parse('<?php echo $encodedprofitmade2; ?>');

			var ctx = document.getElementById('salesChart2').getContext('2d');
			var salesChart = new Chart(ctx, {
				type: 'line', // Change this to 'line', 'pie', etc. depending on what type of chart you want
				data: {
					labels: day2,
					datasets: [{
						label: 'Profit',
						data: profit2,
						backgroundColor: [
							// Add colors for each bar here
							'rgba(255, 0, 0, 1)',
							// ...more colors as needed
						],
						borderColor: [
							// Border color for each bar
							'rgba(255, 0, 0, 1)',
							// ...more colors as needed
						],
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						y: {
							beginAtZero: true,
							title: {
								display: true,
								text: 'Profit'
							}
						},
						x: {
							title: {
								display: true,
								text: 'Day of the Year'
							}
						}

					}
				}
			});
		</script>

	</body>
</div>
<br>
<br>
<br>
<br>
<?php include ("../Resources/footer.php"); ?>