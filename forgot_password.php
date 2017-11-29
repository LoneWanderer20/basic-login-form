<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Forgot Password</title>
<link href="login_signup_form.css" rel="stylesheet" type="text/css">
<link href="resetCSS.css" rel="stylesheet" type="text/css">
</head>

<body>

<?php 
$email = $confirmEmail = "";	
$emailErr = $confirmEmailErr = "";
$verificationCode = md5(rand(0,1000));	
$errorInduced = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {
		
	if(empty($_POST["email"])) {
		$emailErr = "Email is a required field";
		$errorInduced = true;
	} else {
		$email = testInput($_POST["email"]);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "Invalid email format";
			$errorInduced = true;
		}
	}
	
	if(empty($_POST["confirmEmail"])) {
		$confirmEmailErr = "You need to confirm your email";
		$errorInduced = true;
	} else {
		$confirmEmail = testInput($_POST["confirmEmail"]);
		if($confirmEmail !== $email) {
			$confirmEmailErr = "This email doesn't match your first email";
			$errorInduced = true;
		}
	}
}
	
function testInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
	
	
if(isset($_REQUEST["newPasswordSubmit"]) && $errorInduced === false && $email !== "") {	
	
	$servername = "localhost";
    $username = "root";
    $dbName = "user_database";	
	
    $connection = mysqli_connect($servername, $username, "", $dbName);

    if($connection === false) {
	    die("ERROR: connection attempt failed " . mysqli_connect_error());
    }
	
	$emailQuery = mysqli_query($connection, "SELECT * FROM user_info WHERE email='".$email."'");
	$emailCount = mysqli_num_rows($emailQuery);
	if($emailCount === 0) {
		echo "The email you've submitted is not associated with any account";
	} else {	
	    mysqli_query($connection, "UPDATE user_info SET activated='1', verification='".$verificationCode."' WHERE email='".$email."'");
		$to = $email;
	    $subject = "Email Verification";
	    $message = '
	    To change your password, click on the following link:
	    http://localhost/practicePHP/login_form_project/change_password.php?val=' . $verificationCode . '&val2=' . $email;
	
	    if(mail($to, $subject, $message)) {
		    echo "A verification link has been sent to your email address. Follow the link to change your password.";
	    } else {
		    die("Sending failed.");
	    }		
	}
		
    mysqli_close($connection);
}	
?>

<form class="form_cont" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		
	    <label><b>Email</b></label>
	    <span class="errorSpan">* <?php echo $emailErr;?></span>
	    <input class="inputClass" type="email" placeholder="Enter Email" name="email" required value="<?php echo $email;?>" />
	    
	    <label><b>Confirm Email</b></label>
    	<span class="errorSpan">* <?php echo $confirmEmailErr;?></span>
     	<input class="inputClass" type="email" placeholder="Confirm Email" name="confirmEmail" required value="<?php echo $confirmEmail;?>" />
	       
	    <button class="loginSubmit" type="submit" name="newPasswordSubmit">Send Verification</button> 
</form>

<script type="text/javascript" src="../login_form.js"></script>
</body>
</html>