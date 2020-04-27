<?php
    // I'm assuming this is for getting our parameter from the URL and assigning
    //  it to local variable `view'
    $lat = "";
    if(isset($_GET["lat"]))
        $lat = $_GET["lat"];

    $lon = "";
    if(isset($_GET["lon"]))
        $lon = $_GET["lon"];

    $rad = "";
    if(isset($_GET["rad"]))
        $rad = $_GET["rad"];

    //echo $lat;
    //echo ':';
    //echo $lon;
    //echo ",";
    //echo $rad;
    //echo "<br><br>";

    //// SQL Access
    // Taken from https://www.tutorialspoint.com/php/php_and_mysql.htm

    // Create a connection to the MySQL database
    // Use this command to get port: SHOW VARIABLES WHERE Variable_name = 'port';
    $dbhost = 'localhost:3306';
    $dbuser = 'radiusme_matthew';
    $dbpass = 'Hdq#?nL#!smU';
    $mysql_connection = mysqli_connect($dbhost, $dbuser, $dbpass);

    // Test if connection was successful
    if(! $mysql_connection ) {
        //die('Could not connect: ' . mysqli_error());
        echo "Failed to connect to MySQL: " . $mysql_connection -> connect_error;
        exit();
    }

    // Perform the actual fetch by sending a query to our MySQL server
    mysqli_select_db($mysql_connection, 'radiusme_posts');

    // Check for posts inside the radius $rad using the Haversine formula:
    // https://en.wikipedia.org/wiki/Haversine_formula
    // https://developers.google.com/maps/solutions/store-locator/clothing-store-locator#findnearsql
    // https://dba.stackexchange.com/questions/211167/find-locations-in-table-that-are-within-a-certain-radius-distance-of-a-given-lat
    // latitude and longitude are SQL variables while $lat, $lon, and $rad are
    //  PHP variables.
    // This query pulls posts such that user's current location ($lat:$lon) is
    //  within $rad distance of a potential nearby post's
    //  location (latitude:longitude)
    $sql_query_input = 'SELECT *
                        FROM postArchive 
                        HAVING
                        ( 6371 
                            * acos( cos( radians( ' . $lat . ') ) 
                            * cos( radians( latitude ) ) 
                            * cos( radians( longitude ) - radians( ' . $lon . ') )
                            + sin( radians(' . $lat . ') ) 
                            * sin( radians( latitude ) ) ) ) < ' . $rad . '';

    $sql_query_output = mysqli_query( $mysql_connection, $sql_query_input );

    if(! $sql_query_output ) {
        die('Could not get data: ' . $mysql_connection -> error);
    }

    $sql_query_output_array = $sql_query_output -> fetch_array(MYSQLI_ASSOC);
    //print_r($sql_query_output_array);
    //echo "<br><br>";

    //echo "Returned rows are: " . $sql_query_output -> num_rows . "<br><br>";

    $sql_query_output_array_json = json_encode($sql_query_output_array);
    echo $sql_query_output_array_json;

    $mysql_connection -> close();
?>
