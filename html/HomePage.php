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
    $user_id = $_SESSION['user_id'];
    $is_admin = $_SESSION['is_admin'];
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyTrk632VPw8v3qxrpuLdSha9QdPqxL4I&libraries=places" async defer></script>
</head>
<body>
    
    <div class="navbar">
        <div class="logo">
            <h1 id="username"><?php echo "Hello $username"; ?></h1>
        </div>
        <div class="menu">
            <ul>
                <li><a href="HomePage.php">Home</a></li>
                <li><a href="Comments.php">Comments</a></li>
                <li><a href="Recommendations.php">Recommendations</a></li>
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
                <input id="place_autocomplete" name="place" type="text" placeholder="write place" required />
                <button type="submit" name="submit" value="search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
            <div class="<?php 
                if ($is_admin) 
                    echo ""; 
                else 
                    echo "hidden"; 
            ?>">
                <p>
                    <span id="admin-data-comments-title">Comments: </span>
                    <span id="admin-data-comments"></span>
                </p>
                <p>
                    <span id="admin-data-users-title">Users: </span>
                    <span id="admin-data-users"></span>
                </p>
            </div>
        </div>
        </div>
    </div>    
    <script>
        const isAdmin = "<?php echo $is_admin ?>" !== "0";
        if (isAdmin) {
            fetch('api/home/adminData.php', {
                method: 'GET'
            })
            .then(response => {
                return response.json();
            })
            .then(adminData => {
                const commentsElement = document.getElementById("admin-data-comments");
                const usersElement = document.getElementById("admin-data-users");
                commentsElement.innerHTML = `${adminData['comments']}`;
                usersElement.innerHTML = `${adminData['users']}`;
            });
        }

        window.onload = function() {
            const input = document.getElementById("place_autocomplete");
            const options = {
                fields: ["name"],
                strictBounds: false,
                types: ['(cities)']
            };
            const autocomplete = new google.maps.places.Autocomplete(input, options);
            autocomplete.addListener("place_changed", async () => {
                const place = autocomplete.getPlace();
                const response = await fetch(`api/home/selectedLocation.php?location=${place['name']}`, {
                    method: 'GET',
                    redirect: 'follow'
                });
                window.location.href = response.url;
            });
        };

    </script>
</body>
</html>