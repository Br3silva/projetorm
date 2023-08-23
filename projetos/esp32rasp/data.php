
<?php
if(isset($_GET["I2"] )) {
   $entrada2 = $_GET["I2"]; // get temperature value from HTTP GET
   $entrada4 = $_GET["I4"]; // get temperature value from HTTP GET

   $servername = "localhost";
   $username = "root";
   $password = "";
   $database_name = "db_esp32";

   // Create MySQL connection fom PHP to MySQL server
   $connection = new mysqli($servername, $username, $password, $database_name);
   // Check connection

  
   if ($connection->connect_error) {
      die("MySQL connection failed: " . $connection->connect_error);
   }

   //descomente quando for update
   $id = 1;

   //descomente quando for inserir
   //$sql = "INSERT INTO tbl_temp (temp_value) VALUES ($temperature)";

   //descomente quando for update
   $sql = "UPDATE tbl_temp SET temp_value = $entrada2 WHERE temp_id = $id";

   if ($connection->query($sql) === TRUE) {
      echo "New record created successfully";

   } else {
      echo "Error: " . $sql . " => " . $connection->error;
   }

   $connection->close();
} else {
   echo "entrada is not set in the HTTP request";
  
}

?>
