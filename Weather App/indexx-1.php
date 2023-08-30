<?php
// fixing CORS error
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

// Changing time zone as php gives time different than that of mysql
date_default_timezone_set('Asia/Kathmandu');

require_once ("dbconnectt-1.php");
$city_name = $_GET['city'];


$queryStatement = "SELECT * FROM weather_data WHERE city='$city_name' and
datee > DATE_SUB(NOW(), INTERVAL 180 SECOND) ORDER BY datee desc limit 1;";

$result = $connection->query($queryStatement);


if ($result->num_rows == 0){
    // Adding new data to database as data is too old or there is no any data
    
    $url = "https://api.openweathermap.org/data/2.5/weather?q="."$city_name"."&units=metric&appid=3fa14b2a2a3fa2dc82911fad048939fc";
    $response = file_get_contents($url);
    $data = json_decode($response, true); // Decoding the json data 

    // echo var_dump($data);

    // Getting the data ready
    $temp = $data['main']['temp'];
    $temp_desc = $data['weather'][0]['description'];
    $max_temp = $data['main']['temp_max'];
    $min_temp = $data['main']['temp_min'];
    $feels_like_t = $data['main']['feels_like'];
    $humidity = $data['main']['humidity'];
    $visibility = $data['visibility'];
    $wind_s = $data['wind']['speed'];
    $datee = date("Y-m-d H:i:s");
    $city = $data['name'];
    $image_code = $data['weather'][0]['icon'];
    

    // Making a query statement for MYSQL as we need to insert new data to MYSQL database.
    $insert_query = "INSERT INTO weather_data(city, temp, temp_desc, feels_like, max_temp, min_temp, humidity, wind_s, visibility, datee, image_icon) 
    VALUES ('$city', $temp, '$temp_desc', $feels_like_t, $max_temp, $min_temp, $humidity, $wind_s, $visibility , '$datee', '$image_code');";

    // Checking the connection
    if ($connection->query($insert_query)== True){
        $queryStatement = "SELECT * FROM weather_data WHERE city='$city_name' and
        datee > DATE_SUB(NOW(), INTERVAL 180 SECOND) ORDER BY datee desc limit 1;";
        $result = $connection->query($queryStatement);
        $arr = $result -> fetch_all(MYSQLI_ASSOC); // Changing the data from database in array form
        print_r(json_encode($arr)); // Converting the array form data to json data and printing it
    }
    else{
        echo "Error: " . $insert_query . "<br>" . mysqli_error($connection);
    }

}
else{
    // Data from Database
    $queryStatement = "SELECT * FROM weather_data WHERE city='$city_name' and
    datee > DATE_SUB(NOW(), INTERVAL 180 SECOND) ORDER BY datee desc limit 1;";
    $result = $connection->query($queryStatement);
    $arr = $result -> fetch_all(MYSQLI_ASSOC); // Changing the data from database in array form
    print_r(json_encode($arr)); // Converting the array form data to json data and printing it


    //echo var_dump($res);

    
    

}

