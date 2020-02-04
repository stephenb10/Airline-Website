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

    if(isset($_GET['submit'])){
        checkIn($_GET);
    }


    if(!isset($_GET['bookingid'])){
        Header("Location: bookings.php");
    }

    $bid = $_GET['bookingid'];

    $query = "SELECT flight_id from booking where id = $bid";
    if(!$result = $connection->query($query)){
        die('Query Statement Error' . $connection->error);
    }
    $fetched = $result->fetch_assoc();
    $fid = $fetched['flight_id'];

    $query = "SELECT flight_number, plane FROM flight WHERE id = $fid";
    if(!$result = $connection->query($query)){
        die('Query Statement Error' . $connection->error);
    }
    $fetched = $result->fetch_assoc();
    $fnumber = $fetched['flight_number'];
    $planeid = $fetched['plane'];

    $query = "SELECT max_baggage_weight, seating FROM plane WHERE id = $planeid";
    if(!$result = $connection->query($query)){
        die('Query Statement Error' . $connection->error);
    }
    $fetched = $result->fetch_assoc();
    $maxweight = $fetched['max_baggage_weight'];
    $seating = $fetched['seating'];

    $weightpc = $maxweight / $seating;

   


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
            <h1>Flight <?=$fnumber?></h1> 
        


        <form class="booking">

            <h3>Max weight: <?=$weightpc?> KG</h3>

            <div>
                <label for="weight">Your baggage weight</label>
                <input type="number" id="weight" name="weight" min=0 max=<?=$weightpc?> placeholder="" onblur="" required>
            </div>




            <div>
                <input type="submit" name="submit" id="submit" value="Checkin">
            </div>


        </form>


    </div>

</body>

</html>



<?php 

function checkIn($info){
    global $connection;
    global $bid;

    $weight = $connection->real_escape_string($info['weight']);
    $date = date("Y-m-d h:i:s");


    $query = "UPDATE booking ";
    $query .= "SET baggage=$weight, checkedin=1, checkin_datetime='$date'";

    if($connection->query($query) == TRUE){
        header("Location: bookings.php");
    } else {
        die('<p>Query Statement Error: ' . $connection->error) . "</p>";
    }



}


$connection->close();

?>