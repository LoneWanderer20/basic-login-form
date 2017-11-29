<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Verification Page</title>
<link href="login_signup_form.css" rel="stylesheet" type="text/css" />
<link href="resetCSS.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
	
$verificationMessage = "";
$servername = "localhost";
$username = "root";
$dbName = "user_database";	
	
$conn = mysqli_connect($servername, $username, "", $dbName);

if($conn === false) {
    die("ERROR: connection attempt failed " . mysqli_connect_error());
} 

$verifyHash;
$name;
if(isset($_GET["val"]) && !empty($_GET["val"])) {
	$verifyHash = mysqli_real_escape_string($conn, $_GET["val"]);
	$name = mysqli_real_escape_string($conn, $_GET["val2"]);
} else {
	die("ERROR: verifiyHash is undefined");
}

$sql = "SELECT verification, activated FROM user_info WHERE verification='".$verifyHash."' 
AND activated='1'";
$search = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$match = mysqli_num_rows($search);

if($match>0) {
	mysqli_query($conn, "UPDATE user_info SET activated='0' WHERE verification='".$verifyHash."'");
	
	$sql_two = "INSERT INTO quiz_user_table (username, right_one, wrong_one, right_two, wrong_two, right_three, wrong_three, right_four, wrong_four, right_five, wrong_five, right_six, wrong_six) VALUES ('".$name."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";	
	mysqli_query($conn, $sql_two);
		
	$sql_three = "INSERT INTO image_table (username, images) VALUES ('".$name."', '[]')";	
	mysqli_query($conn, $sql_three);
				
	$verificationMessage = "Your email has been verified, and your account has been activated.";
} else {
	echo "Problem verifying account";
	$verificationMessage = "An error occurred whilst trying to verify your account. Please try again.";
}

mysqli_close($conn);		
?>
    
    <div class="verifyCont">
    	<div class="verifiyTitle">
    		<p>Verification Page</p>
    	</div>
    	
    	<span><?php echo $verificationMessage;?> <a></a></span>
    	
    </div>

</body>
</html>