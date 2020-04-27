<?php
    // I'm assuming this is for getting our parameter from the URL and assigning
    //  it to local variable `view'
    $text = "";
    if(isset($_GET["text"])) $text = $_GET["text"];

    $rad = "";
    if(isset($_GET["rad"])) $rad = $_GET["rad"];

    $lat = "";
    if(isset($_GET["lat"])) $lat = $_GET["lat"];

    $lon = "";
    if(isset($_GET["lon"])) $lon = $_GET["lon"];

    $dur = "";
    if(isset($_GET["dur"])) $dur = $_GET["dur"];

    //echo "text: ";
    //echo $text;
    //echo "<br>";
    //echo "rad: ";
    //echo $rad;
    //echo "<br>";
    //echo "lat: ";
    //echo  $lat;
    //echo "<br>";
    //echo "lon: ";
    //echo $lon;
    //echo "<br>";
    //echo "dur: ";
    //echo $dur;
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

    // INSERT INTO `postArchive` (`id`, `user_message_text`, `radius`, `latitude`, `longitude`, `message_duration`) VALUES (NULL, 'foobar', '100.0', '34.159850', '-118.991650', '6'); 
    $sql_query_input =
        'INSERT INTO `postArchive` 
        (`id`, `user_message_text`, `radius`, `latitude`, `longitude`, `message_duration`)
        VALUES 
        (NULL, 
        \'' . $text . '\',
        \'' . $rad . '\',
        \'' . $lat . '\',
        \'' . $lon . '\',
        \'' . $dur . '\');';

    //echo $sql_query_input;

    $sql_query_output = mysqli_query( $mysql_connection, $sql_query_input );

    if(! $sql_query_output ) {
        die('Could not get data: ' . $mysql_connection -> error);
    }

    //$sql_query_output_array = $sql_query_output -> fetch_array(MYSQLI_ASSOC);
    //print_r($sql_query_output_array);
    //echo "<br><br>";

    //echo "Returned rows are: " . $sql_query_output -> num_rows . "<br><br>";

    //$sql_query_output_array_json = json_encode($sql_query_output_array);
    //echo $sql_query_output_array_json;

    $mysql_connection -> close();
?>
