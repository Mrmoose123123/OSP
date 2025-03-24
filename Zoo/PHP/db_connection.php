<?php

function get_database_connection(){

    $servername="ccsw-mysql1.mysql.database.azure.com";
    $username="0020037074_User1";
    $password="v9sFZHC28QaM";
    $database="0020037074_DB1";
    $port = 3306;

    $conn = new mysqli($servername,$username,$password,$database,$port);



    if ($conn-> connect_error){
        die("connection failed:" . $conn->connect_error);
    }
    return $conn;
}



?>