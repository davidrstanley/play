<?php

    // Start the session
    session_start();
    include("inc/inc.path.php");
    require_once($path."class/user.class.php");

    $user = new USER;
    /*
    if(isset($_SESSION["rfname"]))
    {
        $fname = $_SESSION["rfname"];
        $lname = $_SESSION["rlname"];
        $email = $_SESSION["remail"];
    }
    */

    if(isset($_POST['fname'])){
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $result = $user->register($fname, $lname, $email, $password);
        if($result){
            header('location: /index.php');
        }
        
    }

?>

<!DOCTYPE html>
    <head>
        <?php require_once('inc/head.php'); ?>
        <link rel="stylesheet" href="style/signup.css">
        <title>Sign Up</title>
    </head>
    <body>
        <div class="container">
            <main>
                <form action="signup.php" method="post">
                    <h1>Sign Up</h1>
                        <input type="text" placeholder="First Name" name="fname" value="<?php if(isset($fname)){echo $fname;} ?>" required>
                        <input type="text" placeholder="Last Name" name="lname" value="<?php if(isset($lname)){echo $lname;} ?>" required>
                        <input type="text" placeholder="Email" name="email" value="<?php if(isset($email)){echo $email;} ?>" required>
                        <input type="password" placeholder="Password" name="password" required>
                        <input type="submit" value="Submit" required>
                </form>
            </main>
        </div>
    </body>
</html>