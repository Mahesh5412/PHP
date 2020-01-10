<?php
session_start();


if($_SESSION['userName'] == null){
	echo '<script>alert("Please Login");window.location.href="index.php"</script>';
}

?>
<html>
    <head>
        <title>Ptms login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    </head>
    <body>
		<div class="topnav">
  <a href="add.php">PTMS</a>
  <div class="topnav-right">
  <a  href="signout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;sign-out</a>
  <a  href="add.php"><i class="fa fa-user-o" aria-hidden="true"></i>&nbsp;<?php echo $_SESSION['userName']?></a>
  
  </div>
</div>
        <div class="container">
                <img class="image" src="images/background.jpg">
           
                    <div class="login" id="addon">
                       
                        <form>
                            <div class="inputBox">
                                <input type="text" name="" placeholder="Enter Crop Code">
                                <span><i class="fa fa-lock" aria-hidden="true"></i></span>
                            </div>
                            <div class="inputBox">
                                <input type="text" name="" placeholder="Add your roles">
                            </div>
                                <input type="submit" name="" value="Submit">
                        </form>
                       
                    </div>
            </div>
        </div>

    </body>
</html>
