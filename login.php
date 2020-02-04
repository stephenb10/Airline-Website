<?php
   
   session_start();

    $user = null;
    
    $invalidLogin = false;
    $email = "";
    $password = "";
 
    if(isset($_SESSION["userid"])){
        header("Location: index.php");
    }

    
    $connection = new mysqli('localhost', 'twa032', 'twa032xf', 'cooper_flights032');
    if($connection->connect_error) {
        die("failed to connect to the database: " . $connection->connect_error);
    }

    if(isset($_POST['submit'])){
        authenticate($_POST);
    }
    

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Book flights all over the world at great prices.">
        <meta name="keywords" content="Stephen Airlines">
        <meta name="author" content="Stephen Byatt">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Stephen Airlines</title>
        <script type="text/javascript" src="action.js"></script>

        <link href="https://fonts.googleapis.com/css?family=PT+Sans&display=swap" rel="stylesheet">


        <link rel="stylesheet" href="styles.css">

        
    </head>
    <body>
        <header>
            <h1>Stephen Airlines</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
            </nav>

           
        </header>

        <div class="form">
            <h1>Log in for Stephen Airlines</h1>
            <p>Keep your details, bookings, web check-in, and special deals 
                and offers all in one place.</p>


                <form id="login" method="post" action="login.php" onsubmit="return validateForm(this)">
                

               <div>
                    <label for="email">Email <img  src="error.png" width="20" height="20"></label>
                    <input type="text" id="email" name="email" placeholder="Eg. john.smith@email.com" onblur="isElementValid(this)" value="<?php echo $email?>">
                    <p>Email </p>
                    <p>is invalid</p>
                </div>

                <div>
                    <label for="password">Password <img  src="error.png" width="20" height="20"></label>
                    <input type="password" id="password" name="password" placeholder="" onblur="isElementValid(this)" value="<?php echo $password?>">
                    <p>Password </p>
                    <p>is required</p>
                </div>

                <?php if($invalidLogin) :?>
                <div>
                    <h3>Invalid email or password </h3>
                </div>
                <?php endif; ?>
               

                <div>
                    <input type="submit" name="submit" id="submit" value="Log in">
                </div>

                <div>
                    <p>Not a member yet? <a href="register.php">Create account</a></p>
                </div>


            </form>


        </div>
    
    </body>
</html>

<?php  



function authenticate($form) {
    global $connection;
    global $invalidLogin;
    global $email;
    global $password;


    $email = $connection->real_escape_string($form['email']);
    $password = hash('sha256', $form['password']);
    $query = "SELECT id, password FROM customer WHERE email = '$email' and password ='$password'";
        if(!$result = $connection->query($query)){
            die('Query Statement Error' . $connection->error);
        }
    
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION["userid"] = $user['id'];
            header("Location: index.php");
        }
        else{
            $invalidLogin = true;
            $email = $form['email'];
            $password = $form['password'] ;
        }
    

}


$connection->close();


?>
