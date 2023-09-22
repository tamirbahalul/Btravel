<?php session_start();
 $host = "localhost";
 $username = "root";
 $password = "";
 $database = "btravel";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = "Guest"; // Initialize a variable

if(isset($_SESSION['login_user'])) {
    $username = $_SESSION['login_user'];
    //$is_admin = $_SESSION['is_admin'];
} else {
    header('Refresh:0; url=https://localhost:443/www/project/html/SignupPage.php');
    exit();
}

if(isset($_GET['location'])){
    $location = $_GET['location'];
    header("Location: CityPage.php?location=$location");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Btravel</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/styleHomePage.css">
    <link rel="icon" href="../img/istockphoto-840458514-612x612.png">
    <script src="https://kit.fontawesome.com/12a8802bc9.js" crossorigin="anonymous"></script>
</head>
<body>
    
    <div class="navbar">
        <div class="logo">
            <h1 id="username"><?php echo "Hello $username"; ?></h1>
        </div>
        <div class="menu">
            <ul>
                <li><a href="HomePage.php">Home</a></li>
                <li><a href="#">Comments</a></li>
                <li><a href="#">Recommended</a></li>
            </ul>
        </div>
        <div class="logout">
            <a href="logout.php">logout</a>
        </div>      
    </div>
    <div class="body">
        <div class="heading">
            <h1 class="temp">To Travel Is To Live</h1>
            <p class="temp">Where do you want to visit?</p>
            <br>
        <div class="container">
            <form id="searchForm" method="GET">
                <input type="text" placeholder="search your city" name="location" required>
                <button type="submit" name="submit" value="search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>
        </div>
    </div>    
    
</body>
</html>