<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>User Account</title>
<link href="login_signup_form.css" rel="stylesheet" type="text/css">
<link href="resetCSS.css" rel="stylesheet" type="text/css">
</head>

<body>

<?php 

$accountUserName;
$gender;
$birthday;
$verificationCode = $_GET["val"];

$servername = "localhost";
$username = "root";
$dbName = "user_database";

$connection = mysqli_connect($servername, $username, "", $dbName);

if($connection === false) {
	die("ERROR: connection attempt failed " . mysqli_connect_error());
}
	
$search = mysqli_query($connection, "SELECT * FROM user_info WHERE verification='".$verificationCode."'");
$match = mysqli_num_rows($search);
if($match > 0) {
	while($row = mysqli_fetch_row($search)) {
		$accountUserName = $row[1];
		$gender = $row[5];
		$birthday = $row[4];
	}
} else {	
	echo "The verification code is: " . $verificationCode;		
}		
mysqli_close($connection);
	
?>
<div class="form_cont">
     
    <div class="img_cont">
     		
    </div>
     
    <div class="account_username">
        <h3><?php echo $accountUserName;?></h3> 	
    </div>
    
    <div class="account_username">
        <span class="titleSpan">Gender: </span>
    	<span class="valueSpan"><?php echo $gender;?></span>
    </div>
    
     <div class="account_username">
    	<span class="titleSpan">Date of Birth: </span>
    	<span class="valueSpan"><?php echo $birthday;?></span>
    </div> 
    
    <form action="../image_gallery_project/image_gallery.html">
        <button class="toImageGallery loginSubmit">Go To Image Gallery</button>
	</form>
     		
</div>

</body>

</html>
