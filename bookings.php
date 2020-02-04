<?php
   
   session_start();

    $connection = new mysqli('localhost', 'twa032', 'twa032xf', 'cooper_flights032');
    if($connection->connect_error) {
        die("failed to connect to the database: " . $connection->connect_error);
    }


    $user = null;

    $isadmin = false;
 
   $id = null;
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

    

    
   $query = "SELECT booking.id, flight_id, booking_datetime, checkedin FROM booking, flight WHERE customer_id = $id AND flight_id = flight.id ORDER BY flight_datetime";
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

            <div>
                <h1 class="heading">My Bookings</h1>
                    <?php 
                        $numrows = mysqli_num_rows($result);
                        if($numrows > 0) {
                            while($booking = $result->fetch_assoc()){
                                    displayBooking($booking);
                            }
                        }
                        else {
                            echo "<p style='text-align:center;'>No bookings. Make one <a href='newbooking.php'>here</a>.";
                        }




                    ?>


               
                 

               
        </div>
       
        
    </body>
</html>


<?php
function displayBooking($booking){
    global $connection;

    $fid = $booking['flight_id'];
    $query = "SELECT * FROM flight where id = $fid";
    if(!$result = $connection->query($query)){
        die('Query Statement Error' . $connection->error);
    }

    $flight = $result->fetch_assoc();


    $planeid = $flight['plane'];
    $query = "SELECT name, seating FROM plane WHERE id='$planeid'";

    if(!$planes = $connection->query($query)){
        die('Query Statement Error' . $connection->error);
    }
    $i = $planes->fetch_assoc();

  


    // flight information
    $flight_number = $flight['flight_number'];
    $depart_time = date("g:ia",strtotime($flight['flight_datetime']));
    $plane = $i['name'];
    $distance = $flight['distance_km'] . "km";
    $from = $flight['from_airport'];
    $to = $flight['to_airport'];
    $day =  date("l j F Y",strtotime($flight['flight_datetime']));
    $bookedday = date("l j F Y",strtotime($booking['booking_datetime']));
    //average flight speed 900km/h
    $hours = ($flight['distance_km'] / 900) +1;
    $hours = round($hours);
    $arrival = new DateTime($flight['flight_datetime']);
    $arrival->modify("+$hours hours");
    $arrival_time = $arrival->format("g:ia");

    // Seating
    $flightid = $flight['id'];
    $query = "SELECT id from booking ";
    $query .= "WHERE flight_id = $flightid";
    if(!$result = $connection->query($query)){
        die('Query Statement Error' . $connection->error);
    }
    $numrows = mysqli_num_rows($result);
    $maxSeats = $i['seating'];
    $seats = $maxSeats - $numrows;
    

    echo " <div class='flight-item'>
    <span>
        <h3>$day</h3>
        <p>Booked: $bookedday</p>
    </span>
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
  </span>";

  if(!$booking['checkedin']){
    

    $d = new DateTime(date("Y-m-d h:i:s"));
    $c = new DateTime($flight['flight_datetime']);
    $diff = $c->diff($d);

    $hours = $diff->h;
        $hours = $hours + ($diff->days*24);

        $bid = $booking['id'];
        if($hours <= 48) {
        echo "<span>
            <a style='display: inline;' href='checkin.php?bookingid=$bid'> <h2>Checkin</h2> </a>
        </span>";
    }
}
else {
    echo "<span>
    <h2>Checkedin</h2>
    </span>";
}

        
    echo "</div>";


}

$connection->close();

?>