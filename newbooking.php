<?php
   
   session_start();

    $connection = new mysqli('localhost', 'twa032', 'twa032xf', 'cooper_flights032');
    if($connection->connect_error) {
        die("failed to connect to the database: " . $connection->connect_error);
    }


    $user = null;
    $searchValid = false;
    $flights = null;

    $from = "";
    $to = "";
    $depart = "";


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
        $from = $_GET['from'];
        $to = $_GET['to'];
        $depart = $_GET['depart'];
        validateForm($_GET);
    }


 

   $query = "SELECT DISTINCT from_airport, to_airport FROM flight";
    if(!$result = $connection->query($query)){
        die('Query Statement Error' . $connection->error);
    }
    $fromFlights = Array();
    $toFlights = Array();
    while($flight = $result->fetch_assoc()){
        $f = $flight['from_airport'];
        $t = $flight['to_airport'];
        if(!in_array($f, $fromFlights)){
            $fromFlights[] = $f;
        }

        if(!in_array($t, $toFlights)) {
            $toFlights[] = $t;
        }
    }

    $fromFlights = json_encode($fromFlights);
    $toFlights = json_encode($toFlights);

    

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
                <h1 class="heading">Book a flight</h1>


                <form class="booking" autocomplete="off" >
                    <div>
                        <span class="autocomplete">
                            <label>From <img src="error.png" width="20" height="20"></label>
                            <input class="auto" type="text" id="from" name="from" placeholder="" onblur="" value="<?=$from?>">
                            <p>This field </p>
                            <p>is required</p>
                        </span>
                        <span class="autocomplete">
                            <label>To <img src="error.png" width="20" height="20"></label>
                            <input class="auto" type="text" id="to" name="to" placeholder="" onblur="" value="<?=$to?>">
                            <p>This field </p>
                            <p>is required</p>
                        </span>
                    </div>
                    <div>
                        <span>
                            <label>Earliest Departure <img src="error.png" width="20" height="20"></label>
                            <input type="date" id="depart" name="depart" placeholder="" onblur="" value="<?=$depart?>" required>
                        </span>
                    </div>

                    <div style="padding-top: 20px;">
            
                    <input type="submit" name="submit" id="submit" value="Search">
             
                    </div>
                </form>

                <?php
                    if($searchValid && isset($_GET['submit']))
                        {
                            while($flight = $flights->fetch_assoc()){
                                displayFlight($flight);
                            }
                        }
                ?>

               
        </div>

        <script> 
        var fromFlights = <?=$fromFlights?>;
        var toFlights = <?=$toFlights?>;
        autocomplete(document.getElementById("from"), fromFlights);
        autocomplete(document.getElementById("to"), toFlights);
        
        
        var element = document.getElementById("depart");
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();

        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;

        var now = year + "-" + month + "-" + day;
        <?php if(!isset($_GET['submit'])) :        ?>
        element.value = now;
        <?php endif; ?>
        element.min = now;
        
      
        
        </script>
       
        
    </body>
</html>



<?php
function displayFlight($flight){
    global $connection;
    $planeid = $flight['plane'];
    $query = "SELECT * FROM plane WHERE id='$planeid'";

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
    

    echo "<div class='flight-item'>
    <h3>$day</h3>
    <span>
    <h3>$flight_number</h3>
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
      </span>

       <span>
        <h2>";
        if($seats > 0)
            echo "$seats Seats left </h2>
            <a style='display: inline;' href='completebooking.php?flightid=$flightid'> <h2>Book now</h2> </a>";
        else
            echo "Full </h2>";
        echo "</span>

</div>";


}



function validateForm($form){

    global $connection;
    global $flights;
    global $searchValid;
    


    $date = $connection->real_escape_string(trim(urldecode($form['depart'])));
    $from = $connection->real_escape_string(trim(urldecode($form['from'])));
    $to = $connection->real_escape_string(trim(urldecode($form['to'])));


    $query = "SELECT * FROM flight ";
    $query .= "WHERE CAST(flight_datetime as date) > '$date'" ;
    if(!empty($from))
        $query .= "AND from_airport = '$from' ";
    if(!empty($to))
        $query .= "AND to_airport = '$to' ";


    if(!$result = $connection->query($query)){
        die('Query Statement Error' . $connection->error);
    }
    
    $numrows = mysqli_num_rows($result);

    if($numrows > 0){
        $searchValid = true;
        $flights =
         $result;
    }
    else{
        echo "no flights found";
    }





}




$connection->close();

?>