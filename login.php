<?php
   
   session_start();

    $user = null;
 
    if(isset($_SESSION["userid"])){
        header("Location: index.php");
    }

    
    $connection = new mysqli('localhost', 'twa032', 'twa032xf', 'cooper_flights032');
    if($connection->connect_error) {
        die("failed to connect to the database: " . $connection->connect_error);
    }

    if(isset($_POST['submit'])){
        if(validateForm($_POST)){
            authenticate($_POST);
        }
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
        <title>Stephen Airlines</title>

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


                <form id="login" method="post" action="login.php">
                

                <div>
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="Eg. john.smith@email.com" onblur="">
                </div>

                <div>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="" onblur="">
                </div>
               

                <div>
                    <input type="submit" name="submit" id="submit" value="Log in">
                </div>

                <div>
                    <p>Not a member yet? <a>Create account</a></p>
                </div>


            </form>


        </div>
    
    </body>
</html>

<?php  


function validateForm($form){

    $valid = true;

    echo "validating";

    foreach ($_POST as $key => $value) {
        if($key == "Submit"){
            continue;
        }

       

        if(empty($value)){
            $valid = false;
            echo "<p> $key : $value is empty </p>";
        }


        switch ($key){

            case "email":
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $valid = false;
                    echo "<p> $key : $value not valid </p>";
                }
            break;

            case "password":
                if(!preg_match('/^(?=.*?[0-9]).{8,}$/', $value)){
                    $valid = false;
                    echo "<p> $key : $value not valid </p>";
                }

            break;

        }
    }

    echo $valid ? 'true' : 'false';

    return $valid;

}


function authenticate($form) {
    global $connection;

    $email = $form['email'];
    $query = "SELECT id, password FROM customer WHERE email = '$email'";
        if(!$result = $connection->query($query)){
            die('Query Statement Error' . $connection->error);
        }

        $row = $result->fetch_assoc();

        $password = hash('sha256', $form['password']);

        if($row['password'] == $password){
            logIn($row['id']);
        }

}


function logIn($id){
        $_SESSION["userid"] = $id;
        header("Location: index.php");
}



?>