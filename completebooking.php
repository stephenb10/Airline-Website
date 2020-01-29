<?php
   
   session_start();

    $connection = new mysqli('localhost', 'twa032', 'twa032xf', 'cooper_flights032');
    if($connection->connect_error) {
        die("failed to connect to the database: " . $connection->connect_error);
    }


    $user = null;

    if(isset($_SESSION["userid"])){
        $id = $_SESSION["userid"];
        $query = "SELECT * FROM customer WHERE id = $id";
        if(!$result = $connection->query($query)){
            die('Query Statement Error' . $connection->error);
        }
        $user = $result->fetch_assoc();
    }
    else{
        Header("Location: Login.php");
    }

    if(!isset($_GET['flightid'])){
        Header("Location: newbooking.php");
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

                <?php if($user != null) : ?>
                <a href="profile.php">My Profile</a>
                <a href="newbooking.php">New Booking</a>
                <a href="bookings.php">Bookings</a>

                <?php if($user['admin']) :?> 
                <a href="flights.php">Flights</a>
                <?php endif ?>

                <a href="logoff.php">Logout</a>

                <?php else: ?>
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
                <?php endif ?>
            </nav>

    </header>

    <div class="form">
        <h1>Complete your booking</h1>
        <p>Enter your card details to complete your booking.</p>

        <div class="booking-details">
            <?php displayInfo(); ?>
            
        </div>
        


        <form class="booking" onsubmit="return validateForm(this)">

            <div>
                <label for="fname">Name on Card <img src="error.png" width="20" height="20"></label>
                <input type="text" id="name" name="name" placeholder="" onblur="">
                <p>This field </p>
                            <p>is required</p>
            </div>


            <div>
                <label for="email">Card number <img src="error.png" width="20" height="20"></label>
                <input type="text" id="number" name="number" placeholder="1111-2222-3333-4444" onblur="">
                <p>This field </p>
                            <p>is required</p>
            </div>

            <span>
                <div>
                    <label for="postcode">Expiry Month <img src="error.png" width="20" height="20"></label>
                    <input type="text" id="month" name="month" placeholder="" onblur="">
                    <p>This field </p>
                            <p>is required</p>
                </div>

                <div>
                    <label for="postcode">Expiry Year <img src="error.png" width="20" height="20"></label>
                    <input type="text" id="year" name="year" placeholder="" onblur="">
                    <p style="display:none; color:red;">This field </p>
                            <p style="display:none; color:red;">is required</p>
                </div>
                
            </span>

            
            <div>
                <label for="email">CVV <img src="error.png" width="20" height="20"></label>
                <input type="text" id="cvv" name="cvv" placeholder="123" onblur="">
                <p>This field </p>
                            <p>is required</p>
            </div>



            <div>
                <input type="submit" name="submit" id="submit" value="Book Flight">
            </div>


        </form>


    </div>

</body>

</html>


<?php 


function displayInfo(){
    global $connection;
$id = $_GET['flightid'];

$query = "SELECT * FROM flight WHERE id = '$id'";
if(!$result = $connection->query($query)){
    die('Query Statement Error' . $connection->error);
}
$flight = $result->fetch_assoc();
$flight_number = $flight['flight_number'];
$from = $flight['from_airport'];
$to = $flight['to_airport'];
$day =  date("l j F Y",strtotime($flight['flight_datetime']));



echo    "<h2>Flight $flight_number</h2>
            <h3>$day</h3>
            <h3>From $from</h3>
            <h3>To $to</h3>
            <div>
                <h3>Passengers</h3>
                <p>1</p>
            </div>

            <div>
                <h3>Total</h3>
                <p>$455</p>
            </div>";
}

?>