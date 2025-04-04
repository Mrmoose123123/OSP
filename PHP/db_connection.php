<?php

function get_database_connection(){
    $servername="ccsw-mysql-exams1.mysql.database.azure.com";
    $username="0020037074_24_890_User1";
    $password="Ip2EQm1z4MAG";
    $database="0020037074_24_890_DB1";
    $port=3306;

    $conn = new mysqli($servername,$username,$password,$database,$port);


    #this will display a connnection fail meesage and join it to the return error
    #from the connection string
    if ($conn->connect_error){
        die("connection failed:" . $conn->connect_error);

    }
    return $conn;

}


?>