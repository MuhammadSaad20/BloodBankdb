<?php

//config all database//

define('DB_SERVER','');
define('DB_USERNAME','root');
define('DB_PASSWORD','');
define('DB_NAME','hope');
 
//try to connect DB
$connect = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);
 
//chreck connection
if($connect==false){
    dir('Error: Cannot Connect to DB');
}

?>