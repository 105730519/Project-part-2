<?php 
include("header.inc"); 
include("menu.inc"); 
require("settings.php");

// Database connection 
$conn = mysqli_connect($host, $username, $password, $database);

// If connection fail, display error 
if (!$conn){
    die("Database connection failed: ".mysqli_connect_error());
}

// Process login
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $input_usename = trim($_POST['username']);
    $input_password = trim($_POST['password']);
    $query = "SELECT * FROM users WHERE username = '$input_username' AND password = '$input_password'";
    $result = mysqli_query($conn, $query);
    
    if($user = mysqli_fetch_assoc($result)){
        $_SESSION['username'] = $user['username'];

        // If the user is the manager, redirect to manager page
        if($user['username'] == 'Nguyen Gia Bao Pham'){
            header('Location:manager.php');
            exit;
        }else{

            // Otherwise, redirect to standard home page
            header('Location:index.php');
            exit;
        }
    }else{

        // If no user found, set an error message in session
        $_SESSION['error'] = "Invalid username or password. Please try again.";
            header('Location:login.php');
        exit;
    }
}
?>
<?php include("footer.inc"); ?>