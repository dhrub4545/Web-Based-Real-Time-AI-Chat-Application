
<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "chatuser";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn){
//     echo "success";
// }
// else{
    die("Error". mysqli_connect_error());
}
?>
<?php
$login = false;
$showError = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // include '/attandance/db_connect.php';
    $username = $_POST["id"];
    $password = $_POST["password"]; 
    $pass=password_hash($password,PASSWORD_DEFAULT);
     
    // $sql = "Select * from users where username='$username' AND password='$password'";
    $sql = "SELECT * FROM user WHERE username='$username' " ;
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 1){
        while($row=mysqli_fetch_assoc($result)){
            // if (password_verify($row['password'],$password )){ 
            if (password_verify($row['password'],$pass )){ 
                $login = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header("location: chatapp.php");
            } 
            else{
                $showError = "Invalid Credentials1";
            }
        }
        
    } 
    else{
        $showError = "Invalid Credentials2";
    }
}
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="styleindex.css">
</head>
<body>
<?php
    if($login){
    echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> You are logged in
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> ';
    }
    if($showError){
    echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> '. $showError.'
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> ';
    }
    ?>
 
		<div class="content ">
        <div class="text">
            Login to WebChat
         </div>
			<form action="/chatapp/index.php" method="post">
			   <div class="field">
				  <input type="text" name="id" required>
				  <span class="fas fa-user"></span>
				  <label>Username</label>
			   </div>
			   <div class="field">
				  <input type="password" name="password" required autocomplete="current-password">
				  <span class="fas fa-lock"></span>
				  <label>Password</label>
			   </div>
			   
			   <button type="submit">Log in</button>
			   <div class="sign-up">
				  Not a member?
				  <a href="signup.php">signup now</a>
			   </div>
			</form>
		 </div>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>