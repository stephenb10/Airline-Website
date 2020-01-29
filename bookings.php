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
        //echo var_dump($user);
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

               
                   <div class="flight-item">
                    <span>
                        <h3>20th Sep 2020</h3>
                        <p>Booked: 1st July 2020</p>
                    </span>
                        <span>
                            <h3>9:30am</h3>
                            <p>Cape York</p>
                        </span>
                        <span>
                            <img src="plane.png" width="40" height="40">
                        </span>
                       <span>
                        <h3>11:30am</h3>
                        <p>Cairns</p>
                       </span>
    
                       <span>
                        <p>Flight: QF343</p>
                        <p>2 Hour Flight (777km)</p>
                       </span>
    
                       <span>
                        <a style="display: inline;" href="checkin.html"> <h2>Checkin</h2> </a href="bookingform">
                       </span>

                        
                    </div>

               
        </div>
       
        
    </body>
</html>