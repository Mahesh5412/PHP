<html>

<head>
    <title>Ptms login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container1">
        <img class="image" src="images/background.jpg">
        <div class="ptms-logo">
            <img src="images/ptms-logo.png" style="height: 180px;">
        </div>
        <div class="login">
            <img class="logo" src="images/user.png" alt="user">
            <h3 class="text">Sign in here</h3>
            <form method="post" action="reactlogin.php">
                <div class="inputBox">
                    <input type="text" name="username" placeholder="Username*" required>
                    <span><i class="fa fa-user" aria-hidden="true"></i></span>
                </div>
                <div class="inputBox">
                    <input type="password" name="password" placeholder="Password*" required>
                    <span><i class="fa fa-lock" aria-hidden="true"></i></span>
                </div>
                <div class="inputBox">
                    <input type="password" name="corp" placeholder="Crop code*" required>
                    <span><i class="fa fa-lock" aria-hidden="true"></i></span>
                </div>

                <div class="inputBox">
                    <label class="radio-inline">
                        <input type="radio" value="admin" name="utype" style="margin-left: -48px;" required>Admin
                    </label>
                    <label class="radio-inline">
                        <input type="radio" value="user" name="utype" style="margin-left: -40px;" required>User
                    </label>

                </div>
                <input type="submit" name="submit" value="Login">
            </form>
        </div>
    </div>
</body>

</html>
<?php

?>
