<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Change Password</title>
<link href="login_signup_form.css" rel="stylesheet" type="text/css">
<link href="resetCSS.css" rel="stylesheet" type="text/css">
<link href="/practicePHP/practicePHP/login_form_project/projects.css" rel="stylesheet" type="text/css">
</head>

<body>

<?php 
$newPassword = $confirmPassword = "";
$newPasswordErr = $confirmPasswordErr = "";
$errorInduced = false;
$verificationCode;
$email;
			
if($_SERVER["REQUEST_METHOD"] == "POST") {
		
	if(empty($_POST["newPassword"])) {
		$newPasswordErr = "Password is a required field";
		$errorInduced = true;
	} else {
		$newPassword = testInput($_POST["newPassword"]);
		if(strlen($_POST["newPassword"]) < 8) {
			$newPasswordErr = "Your password must contain at least 8 characters";
			$errorInduced = true;
		}
		if(!preg_match("#[0-9]+#", $newPassword)) {
			$newPasswordErr = "Your password must contain at least 1 number";
			$errorInduced = true;
		}
		if(!preg_match("#[A-Z]+#", $newPassword)) {
			$newPasswordErr = "Your password must contain at least 1 capital letter";
			$errorInduced = true;
		}
		if(!preg_match("#[a-z]+#", $newPassword)) {
			$newPasswordErr = "Your password must contain at least 1 lowercase letter";
			$errorInduced = true;
		}
	}
	
	if(empty($_POST["confirmPassword"])) {
		$confirmPasswordErr = "You need to confirm your password";
		$errorInduced = true;
	} else {
		$confirmPassword = testInput($_POST["confirmPassword"]);
		if($confirmPassword !== $newPassword) {
			$confirmPasswordErr = "This password doesn't match your first password";
			$errorInduced = true;
		}
	}
	
	$verificationCode = $_POST["verificationCode"];
	$email = $_POST["email"];
}
	
function testInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
		
if($newPassword !== "" && $errorInduced === false && isset($verificationCode) && isset($email)) {
	
	$servername = "localhost";
    $username = "root";
    $dbName = "user_database";	
    $connection = mysqli_connect($servername, $username, "", $dbName);

    if($connection === false) {
        die("ERROR: connection attempt failed " . mysqli_connect_error());
    } 
		
	$passwordQuery = mysqli_query($connection, "SELECT * FROM user_info WHERE password='".$newPassword."'");
	$passwordCount = mysqli_num_rows($passwordQuery);
    $sql = "SELECT verification, email FROM user_info WHERE verification='".$verificationCode."' 
    AND email='".$email."'";
    $search = mysqli_query($connection, $sql) or die(mysqli_error($connection));
    $match = mysqli_num_rows($search);

    if($match>0 && $passwordCount === 0) {
	    mysqli_query($connection, "UPDATE user_info SET activated='0', password='".$newPassword."' WHERE verification='".$verificationCode."' AND email='".$email."'");
    } else {
	    echo "Problem verifying account";
    }
	
	mysqli_close($connection);
} 
?>

<form class="form_cont" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		
		<label><b>New Password</b></label>
    	<span class="errorSpan">* <?php echo $newPasswordErr;?></span>
     	<input class="inputClass" type="password" placeholder="Enter Password" name="newPassword" required value="<?php echo $newPassword;?>" />
     	
     	<label><b>Confirm Password</b></label>
    	<span class="errorSpan">* <?php echo $confirmPasswordErr;?></span>
     	<input class="inputClass" type="password" placeholder="Confirm Password" name="confirmPassword" required value="<?php echo $confirmPassword;?>" />
     	
     	<input type="hidden" name="verificationCode" value="<?php echo htmlspecialchars(@$_GET["val"]);?>" />
     	<input type="hidden" name="email" value="<?php echo htmlspecialchars(@$_GET["val2"]);?>" />
    
	    <button class="loginSubmit" type="submit" name="changePasswordSubmit">Update Password</button> 
</form>

<script type="text/javascript" src="login_form.js"></script>

</body>
</html>