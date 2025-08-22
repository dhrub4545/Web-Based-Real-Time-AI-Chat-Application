<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "chatuser";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
	//     echo "success";
// }
// else{
	die("Error" . mysqli_connect_error());
}
?>
<?php
$account = false;
$showError = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// include '/attandance/db_connect.php';
	$username = $_POST["id"];
	$password = $_POST["password"];
	$cpassword = $_POST["cpassword"];
	// $pass=password_hash($password,PASSWORD_DEFAULT);

	// $sql = "Select * from users where username='$username' AND password='$password'";
	$sql = "SELECT * FROM  user";
	$result = mysqli_query($conn, $sql);

	while ($row = mysqli_fetch_assoc($result)) {
		if ($username == $row['username']) {
			$account = true;
		}
	}

	if ($account == true) {
		echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>error!</strong> account already exists please log in 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> ';

	} else {
		if ($password == $cpassword) {
			$sql = "INSERT INTO `user` ( `username`, `password`) VALUES ( '$username', '$password')";
			mysqli_query($conn, $sql);
			$servername_ = "localhost";
			$username_ = "root";
			$password_ = "";
			$conne = new mysqli($servername_, $username_, $password_);
			$sql_ = "CREATE DATABASE $username";
			$conne->query($sql_);
			$conne2 = mysqli_connect($servername_, $username_, $password_, $username);
			$sqle = "CREATE TABLE `$username` (
    			sno INT AUTO_INCREMENT PRIMARY KEY,
    			action INT,
    			message VARCHAR(200)
				)";
			  $sql22 = "CREATE TABLE chat_with_ai (
				sno INT AUTO_INCREMENT PRIMARY KEY,
				action INT,
				message VARCHAR(500000)
				)";
			$conne2->query($sqle);
			$conne2->query($sql22);
			echo ' <div class="alert alert-error alert-dismissible fade show" role="alert">
        <strong>sucess!</strong> please login
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> ';

		} else {
			echo ' <div class="alert alert-error alert-dismissible fade show" role="alert">
        <strong>error!</strong> please enter password and confirm paswword 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> ';
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
		integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="login.css">
</head>

<body>

	<div class="login" id="login">

		<form action="/chatapp/signup.php" method="post">
			<div class="field">
				<input type="text" name="id" required>
				<span class="fas fa-user"></span>
				<label>Email or Phone</label>
			</div>
			<div class="field">
				<input type="password" name="password" required>
				<span class="fas fa-lock"></span>
				<label>Password</label>
			</div>
			<div class="field">
				<input type="password" name="cpassword" required>
				<span class="fas fa-lock"></span>
				<label>Confirm Password</label>
			</div>
			<div class="forgot-pass">
			</div>
			<button>Sign up</button>
			<div class="sign-up">
				already a member?
				<a href="index.php">login </a>
			</div>
		</form>

	</div>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
		integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
		crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
		integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
		crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
		integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
		crossorigin="anonymous"></script>
</body>

</html>