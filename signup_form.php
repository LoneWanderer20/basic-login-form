<!doctype html>
<html>
<head>
<meta charset="utf-8">
    <title>Signup Form</title>
    <link href="login_signup_form.css" rel="stylesheet" type="text/css" />
    <link href="resetCSS.css" rel="stylesheet" type="text/css">
    <link href="/practicePHP/practicePHP/login_form_project/projects.css" rel="stylesheet" type="text/css">
</head>

<body>

<?php
$name = $password  = $confirmPassword = $email = $day = $month = $year = $gender = "";
$nameErr = $passwordErr = $confirmPasswordErr = $emailErr = $dateErr = $genderErr = "";
	
$errorInduced = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if(empty($_POST["name"])) {
		$nameErr = "Name is a required field";
		$errorInduced = true;
	} else {
		$name = testInput($_POST["name"]);
		if(!preg_match("/^[a-zA-Z ]*$/", $name)) {
			$nameErr = "Only letters and white space are allowed";
			$errorInduced = true;
		}
	}
	
	if(empty($_POST["password"])) {
		$passwordErr = "Password is a required field";
		$errorInduced = true;
	} else {
		$password = testInput($_POST["password"]);
		if(strlen($_POST["password"]) < 8) {
			$passwordErr = "Your password must contain at least 8 characters";
			$errorInduced = true;
		}
		if(!preg_match("#[0-9]+#", $password)) {
			$passwordErr = "Your password must contain at least 1 number";
			$errorInduced = true;
		}
		if(!preg_match("#[A-Z]+#", $password)) {
			$passwordErr = "Your password must contain at least 1 capital letter";
			$errorInduced = true;
		}
		if(!preg_match("#[a-z]+#", $password)) {
			$passwordErr = "Your password must contain at least 1 lowercase letter";
			$errorInduced = true;
		}
	}
	
	if(empty($_POST["confirmPassword"])) {
		$confirmPasswordErr = "You need to confirm your password";
		$errorInduced = true;
	} else {
		$confirmPassword = testInput($_POST["confirmPassword"]);
		if($confirmPassword !== $password) {
			$confirmPasswordErr = "This password doesn't match your first password";
			$errorInduced = true;
		}
	}
	
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
	
	if(empty($_POST["day"]) || empty($_POST["month"]) || empty($_POST["year"])) {
		$dateErr = "Date of birth is a required field";
		$errorInduced = true;
	} else {
		$day = testInput($_POST["day"]);
		$intDay = intval($day);
		if($day < 1 || $day > 31) {
			$dateErr = "Value falls out of the valid range";
			$errorInduced = true;
		}
		$month = testInput($_POST["month"]);
		$intMonth = intval($month);
		if($month < 1 || $month > 12) {
			$dateErr = "Value falls out of the valid range";
			$errorInduced = true;
		}
		$year = testInput($_POST["year"]);
		$intYear = intval($year);
		if($year < 1900) {
			$dateErr = "Value falls out of the valid range";
			$errorInduced = true;
		}
	}
	
	if(empty($_POST["gender"])) {
		$genderErr = "Gender is a required field";
		$errorInduced = true;
	} else {
		$gender = testInput($_POST["gender"]);
	}
	
}

function testInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if(isset($_REQUEST["signUpSubmit"]) && $errorInduced === false) {	

    $servername = "localhost";
    $username = "root";
    $dbName = "user_database";	
    $birthday = $day . "/" . $month . "/" . $year;
	$verificationCode = md5(rand(0,1000));
	$accountActive = 1;
	
    $connection = mysqli_connect($servername, $username, "", $dbName);

    if($connection === false) {
	    die("ERROR: connection attempt failed " . mysqli_connect_error());
    }
	
	$nameQuery = mysqli_query($connection, "SELECT * FROM user_info WHERE username='".$name."'");
	$nameCount = mysqli_num_rows($nameQuery);
	$passwordQuery = mysqli_query($connection, "SELECT * FROM user_info WHERE password='".$password."'");
	$passwordCount = mysqli_num_rows($passwordQuery);
	if($nameCount > 0) {
		echo "This username is already taken";
	} else if($passwordCount > 0) {
		echo "This password is already taken";
	} else {
		$sql = "INSERT INTO user_info (username, password, email, birthday, gender, verification, activated) 
        VALUES ('" . $name . "', '" . $password . "', '" . $email . "', '" . $birthday . "', '" . $gender . 
	    "', '" . $verificationCode . "', '" . $accountActive .	"')";
	
        if(!mysqli_query($connection, $sql)) {
	        echo "ERROR: problem occured trying to insert records";
        }
		
		$to = $email;
	    $subject = "Email Verification";
	    $message = '
	    Thank you for joining our community. To activate your account, click on the following link:
	    http://localhost/practicePHP/login_form_project/verify.php?val=' . $verificationCode . "&val2=" . $name;
	
	    if(mail($to, $subject, $message)) {
		    echo "A verification link has been sent to your email address. Follow the link to activate your account.";
	    } else {
		    die("Sending failed.");
	    }
	}
		
    mysqli_close($connection);
}
?>

<form class="form_cont" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label><b>Username</b></label>
     	<span class="errorSpan">* <?php echo $nameErr;?></span>
        <input class="inputClass" type="text" placeholder="Enter Username" name="name" required value="<?php echo $name;?>" />
 
		<label><b>Password</b></label>
    	<span class="errorSpan">* <?php echo $passwordErr;?></span>
     	<input class="inputClass" type="password" placeholder="Enter Password" name="password" required value="<?php echo $password;?>" />
     	
     	<label><b>Confirm Password</b></label>
    	<span class="errorSpan">* <?php echo $confirmPasswordErr;?></span>
     	<input class="inputClass" type="password" placeholder="Confirm Password" name="confirmPassword" required value="<?php echo $confirmPassword;?>" />
	
	    <label><b>Email</b></label>
	    <span class="errorSpan">* <?php echo $emailErr;?></span>
	    <input class="inputClass" type="email" placeholder="Enter Email" name="email" required value="<?php echo $email;?>" />
	   
	   <div class="dateCont">
	       <label><b>Date of Birth</b></label>
	       <span class="errorSpan">* <?php echo $dateErr;?></span>
	       <span class="dateSpan">
	    	   <input class="dateInput" type="text" placeholder="<?php echo date("j");?>" name="day" required value="<?php echo $day;?>" />/
	    	   <input class="dateInput" type="text" placeholder="<?php echo date("n");?>" name="month" required value="<?php echo $month;?>" />/
	    	   <input class="dateInput" type="text" placeholder="<?php echo date("Y");?>" name="year" required value="<?php echo $year;?>" />
	       </span>
	    </div>
	  
	   <div class="dateCont">
           <div class="genderLabelCont">
              <label><b>Gender</b></label> 
	           <span class="errorSpan" >* <?php echo $genderErr;?></span>
		   </div>
	       <span class="dateSpan genderSpan">
	    	   <label>Male</label>
	    	   <input type="radio" name="gender" required value="male">
	    	   <label>Female</label>
	    	   <input type="radio" name="gender" required value="female" />
	       </span>
	   </div>
	    
	    <button class="loginSubmit" type="submit" name="signUpSubmit">Create Account</button>
	    <div class="tooLogin">Already got an account? <a href="login_form.php">Login</a></div> 
</form>

<script type="text/javascript" src="login_form.js"></script>

</body>
</html>