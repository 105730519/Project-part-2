<?php 
include("header.inc"); 
include("menu.inc"); 
require("settings.php");
    //Database  connection 
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn){
    die("Database connection failed: ".mysqli_connect_error());
}

if (!$_SERVER)
?>
<?php include("footer.inc"); ?>