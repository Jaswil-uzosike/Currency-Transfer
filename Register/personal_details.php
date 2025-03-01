<?php

session_start();


include('db_conn.php');
$errorfn = $errorpn = $errorln = $errorun = $errorem = $errorpw = "";
$allFields = "yes";

if (isset($_POST['submit'])) {

	$query = "SELECT * FROM useracc WHERE email = '" . $mysqli->real_escape_string($_POST['em']) . "'";
	$result = $mysqli->query($query);
	if ($result->num_rows > 0) {
		$errorem = "Email Already Exists";
		$allFields = "no";
	}

	$query2 = "SELECT * FROM useracc WHERE username = '" . $mysqli->real_escape_string($_POST['un']) . "'";
	$result2 = $mysqli->query($query2);
	if ($result2->num_rows > 0) {
		$errorun = "username Already Exists";
		$allFields = "no";
	}

	if ("" === $_POST['fn']) {
		$errorfn = "first name cannot be null";
		$allFields = "no";
	}

	if ("" === $_POST['ln']) {
		$errorln = "Last name cannot be null";
		$allFields = "no";
	}

	if ("" === $_POST['un']) {
		$errorun = "username cannot be null";
		$allFields = "no";
	}

	if ("" === $_POST['pn']) {
		$errorpn = "Phone number cannot be null";
		$allFields = "no";
	}

	if ("" === $_POST['em']) {
		$errorem = "email cannot be null";
		$allFields = "no";
	}

	if ($_POST['pw2'] !== $_POST['pw']) {
		$errorpw = "passwords do not match";
		$allFields = "no";
	}

	if ("" === $_POST['pw']) {
		$errorpw = "password cannot be null";
		$allFields = "no";
	}

	if ($allFields == "yes") {

	include_once('db_conn.php');

	$created = false; //this variable is used to indicate the creation is successfull or not

	// prepare and bind
	$stmt = $mysqli->prepare("INSERT INTO useracc (fname, lname, gender, username, email,phone_number, passwords) VALUES (?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param('sssssis', $fname, $lname, $gender, $username, $email, $phone, $passwords);


	// Hashing User password
	
	// set parameters and execute
	$fname = $_POST['fn'];
	$lname = $_POST['ln'];
	$gender = $_POST['gen'];
	$username = $_POST['un'];
	$email = $_POST['em'];
	$phone = $_POST['pn'];
	$passwords = $_POST["pw"];


	$_SESSION['username'] = $username;

	$stmt->execute();


	if ($stmt) {
		header('Location: account_details.php');
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

<body>
	<form class="form" method="post" style="Display:hidden">
		<fieldset>
			<legend>PERSONAL DETAILS</legend>
			<div class="form-item">
				<input placeholder="First Name" type="text" name="fn">
				<span class="danger">
					<?php echo $errorfn; ?>
				</span>
			</div>
			<div class="form-item">

				<input placeholder="Last Name" type="text" name="ln">
				<span class="danger">
					<?php echo $errorln; ?>
				</span>
			</div>
			<div class="form-item">

				<select name="gen">
					<option value="male">Male</option>
					<option value="female">Female </option>
					<option value="other">Other</option>
				</select>

			</div>
			<div class="form-item">

				<input placeholder="Username" type="text" name="un">
				<span class="danger">
					<?php echo $errorun; ?>
				</span>
			</div>
			<div class="form-item">

				<input placeholder="Phone Number" type="number" name="pn">
				<span class="danger">
					<?php echo $errorpn; ?>
				</span>
			</div>
			<div class="form-item">

				<input placeholder="email" type="email" name="em">
				<span class="danger">
					<?php echo $errorem; ?>
				</span>
			</div>
			<div class="form-item">
				<input placeholder="Password" type="password" name="pw">
				<span class="danger">
					<?php echo $errorpw; ?>
				</span>
			</div>
			<div class="form-item">
				<input placeholder="Repeat Password" type="password" name="pw2">
			</div>
			<div>
				<input class="form-button" type="submit" value="Next" name="submit">
			</div>
			<br>
			<div>
                <a href="../index.php?" class="form-button">Back</a>
            </div>
		</fieldset>
	</form>
</body>

</html>