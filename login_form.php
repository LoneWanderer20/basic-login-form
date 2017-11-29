
<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<title>Login Form</title>
	<link href="login_signup_form.css" rel="stylesheet" type="text/css" />
	<link href="resetCSS.css" rel="stylesheet" type="text/css">
</head>

<body>

<?php
session_start();	

$name = $password  = "";
$nameErr = $passwordErr = "";
	
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
}

function testInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if($name !== "" && $password !== "") {

    $servername = "localhost";
    $username = "root";
    $dbName = "user_database";
    $connection = mysqli_connect($servername, $username, "", $dbName);	
		
    $nameQuery = mysqli_query($connection, "SELECT * FROM user_info WHERE username='".$name."'");
    $nameCount = mysqli_num_rows($nameQuery);
    $passwordQuery = mysqli_query($connection, "SELECT * FROM user_info WHERE password='".$password."'");
    $passwordCount = mysqli_num_rows($passwordQuery);
    if($nameCount === 0) {
	    echo "Unrecognised username";
    } else if($passwordCount === 0) {
	    echo "Unrecognised password";
    } else {
	    $activatedQuery = mysqli_query($connection, "SELECT * FROM user_info WHERE username='".$name."' AND password='".$password."' AND activated='0' ");
		$activatedCount = mysqli_num_rows($activatedQuery);
	    if($activatedCount > 0) {
			$_SESSION['login_user'] = $name;
			
			$urlVal;
			while($row = mysqli_fetch_row($passwordQuery)) {
			    $urlVal = $row[6];
			}
			header("Location: http://localhost/practicePHP/login_form_project/user_account.php?val=" . $urlVal);
	    } else {
			echo "Your account has not been verifiied. Please check your email and follow the verification link";
	    }	
	    mysqli_close($connection);
    }		
}
?>

       
        <h2 class="projectTitle">Login Form Project</h2>
        
        <form class="form_cont" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
     
            <div class="input_cont">
     	        <label><b>Username</b></label>
                <input id="usernameInput" class="inputClass" type="text" placeholder="Enter Username" name="name" required value="<?php echo $name;?>" />
 
		        <label><b>Password</b></label>
     	        <input class="inputClass" type="password" placeholder="Enter Password" name="password" required value="<?php echo $password;?>" />
     	
     	        <button class="loginSubmit" id="loginSubmit" type="submit" name="loginSubmit">Login</button>
     		
     	        <span class="signUpDiv" >Not got an account? <a href="signup_form.php">Sign up</a></span>
     	
     	        <span class="passwordSpan">Forgot <a href="forgot_password.php">password?</a></span>	
            </div>
          	
        </form>
    	
   

<script type="text/javascript" src="login_form.js"></script>
</body>
</html>






