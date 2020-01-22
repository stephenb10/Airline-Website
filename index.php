<?php
   
   session_start();

    $connection = new mysqli('localhost', 'twa032', 'twa032xf', 'cooper_flights032');
    if($connection->connect_error) {
        die("failed to connect to the database: " . $connection->connect_error);
    }


    $user = null;

    $isadmin = false;
 
    if(isset($_SESSION["userid"])){
        echo "session user id was set";
        $id = $_SESSION["userid"];
        $query = "SELECT * FROM customer WHERE id = $id";
        if(!$result = $connection->query($query)){
            die('Query Statement Error' . $connection->error);
        }
        $user = $result->fetch_assoc();
        echo var_dump($user);
    }
 

   $query = "SELECT * FROM flight";
    if(!$result = $connection->query($query)){
        die('Query Statement Error' . $connection->error);
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

        <div>
            <div id="banner-image">
                <img src="https://picsum.photos/1920/500" width="1920" height="500">
            </div>
    
            <div id="flights-container">
                <h2>Flights</h2>

                <div>
                    
                   <?php 
                    $today = new DateTime(date('Y-m-d H:i:s')); 
                    $comparedate = new DateTime(date('Y-m-d H:i:s'));
                    $comparedate->modify("+1 month");

                     while($flight = $result->fetch_assoc()){
                         $date = date("Y-m-d H:i:s",strtotime($flight['flight_datetime']));
                         if($date > $today->format("Y-m-d H:i:s") && $date <  $comparedate->format("Y-m-d H:i:s")){
                            displayFlight($flight);
                         }
                         
                    }
                   ?>
                </div>
        </div>
       
        
    </body>
</html>


<?php

function displayFlight($flight){
    global $connection;
    $planeid = $flight['plane'];
    $query = "SELECT name FROM plane WHERE id='$planeid'";

    if(!$planes = $connection->query($query)){
        die('Query Statement Error' . $connection->error);
    }
    $i = $planes->fetch_assoc();



    $flight_number = $flight['flight_number'];
    $depart_time = date("g:ia",strtotime($flight['flight_datetime']));
    $plane = $i['name'];
    $distance = $flight['distance_km'] . "km";
    $from = $flight['from_airport'];
    $to = $flight['to_airport'];
    $status = $flight['status'];
    $status_image = $status . ".svg";
    $day =  date("l j F Y",strtotime($flight['flight_datetime']));

    //average flight speed 900km/h
    $hours = ($flight['distance_km'] / 900) +1;
    $hours = round($hours);
    $arrival = new DateTime($flight['flight_datetime']);
    $arrival->modify("+$hours hours");
    $arrival_time = $arrival->format("g:ia");
    

    echo "<div class='flight-item'>

    <h3>$day</h3>
    <span>
        <h3>$depart_time</h3>
        <p>$from</p>
    </span>
    <span>
        <img src='plane.png' width='40' height='40'>
    </span>
   <span>
    <h3>$arrival_time</h3>
    <p>$to</p>
   </span>

   <span>
    <p>$hours Hour Flight ($distance)</p>
    <p>$plane</p>
   </span>

   <span>
    <h3>$flight_number</h3>
    <div class='flight-status'>
        <p>$status</p>
        <img src='$status_image' width='20' height='20'>
    </div>
    
   </span>


</div>";
}


?>