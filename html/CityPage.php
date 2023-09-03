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
} else {
    header('Refresh:0; url=https://localhost:443/www/project/html/SignupPage.php');
    exit();
}

$location = "";  // Initialize the variable

if (isset($_GET['location'])) {
    // Get the location from the URL parameter sent by the homepage form
    $location = $_GET['location'];
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
    <link rel="stylesheet" type="text/css" href="../css/style6.css">
    <link rel="icon" href="../img/istockphoto-840458514-612x612.png">
    <script src="https://kit.fontawesome.com/12a8802bc9.js" crossorigin="anonymous"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyTrk632VPw8v3qxrpuLdSha9QdPqxL4I&libraries=places" async defer></script>
</head>
<body class="body">
    
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
        <div class="container">
            <form id="searchForm">
                <input type="text" placeholder="search city" name="location" id='city' required>
                <button type="submit" name="submit" value="search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>      
    </div>
    <div class="heading">
        <h1 class="temp" id="cityName"><?php echo "$location"; ?></h1>
        <div>
            <h2 class="temp">hotels</h2>
            <div class="hotels" id="hotelsResults"></div>
        </div>
        <div>
            <h2 class="temp">restaurant</h2>    
            <div class="restaurant" id="restaurantResults"></div>
        </div>
        <div>
            <h2 class="temp">attraction</h2>
            <div class="attraction" id="attrectionResults"></div>            
        </div>
    </div>    
    <script>
        var currLocation = "<?php echo $location; ?>";
        window.onload = function() {
            console.log('searching for', currLocation);
            if (currLocation) {
                searchRestaurants();
                searchAttractions();
                searchHotels();
            }
        };

        document.getElementById('searchForm').addEventListener('submit', function (event) {
            
        });

        function searchHotels(){

            var city = currLocation;//document.getElementById('city').value;
            console.log('city', city);
            // Create a PlacesService object to interact with the Places API
            var placesService = new google.maps.places.PlacesService(document.createElement('div'));

            // Define the search request parameters
            var request = {
                query: 'hotels in ' + city,
                fields: ['name', 'formatted_address']
            };

            // Perform the Places API search
            placesService.textSearch(request, function (results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    console.log(results);
                    displayResults(results, 'hotelsResults');
                } else {
                    document.getElementById('hotelsResults').innerHTML = "No hotels found in the specified city.";
                }
            });

}

        function searchAttractions(){

            var city = currLocation;//document.getElementById('city').value;

            // Create a PlacesService object to interact with the Places API
            var placesService = new google.maps.places.PlacesService(document.createElement('div'));

            // Define the search request parameters
            var request = {
                query: 'attractions in ' + city,
                fields: ['name', 'formatted_address']
            };

            // Perform the Places API search
            placesService.textSearch(request, function (results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    console.log(results);
                    displayResults(results, 'attrectionResults');
                } else {
                    document.getElementById('attrectionResults').innerHTML = "No attraction found in the specified city.";
                }
            });

        }


        function searchRestaurants() {
            var city = currLocation;//document.getElementById('city').value;

            // Create a PlacesService object to interact with the Places API
            var placesService = new google.maps.places.PlacesService(document.createElement('div'));

            // Define the search request parameters
            var request = {
                query: 'restaurants in ' + city,
                fields: ['name', 'formatted_address']
            };

            // Perform the Places API search
            placesService.textSearch(request, function (results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    console.log(results);
                    displayResults(results, 'restaurantResults');
                } else {
                    document.getElementById('restaurantResults').innerHTML = "No restaurants found in the specified city.";
                }
            });
        }

        function displayResults(results, resultsDivId) {
            var resultsDiv = document.getElementById(resultsDivId);
            results.forEach(function (place) {
                var element = document.createElement('div');
                element.classList.add("google-place-result");
                element.innerHTML = `
                    <h2>${place.name}</h2>
                    <p>${place.formatted_address}</p>
                `;
                resultsDiv.appendChild(element);
            });
        }

        function displayResults1(results) {
            var restaurantResultsDiv = document.getElementById('restaurantResults');
            restaurantResultsDiv.innerHTML = "";

            // results.forEach(function (place) {
            //     var restaurantDiv = document.createElement('div');
            //     var name = document.createElement('h2');
            //     var img = document.createElement('div');
            //     img.innerHTML = place.photos ? place.photos[0].html_attributions[0] : "";
            //     name.textContent = place.name;
            //     var address = document.createElement('p');
            //     address.textContent = place.formatted_address;

            //     restaurantDiv.appendChild(name);
            //     restaurantDiv.appendChild(address);
            //     restaurantDiv.appendChild(img);
            //     restaurantResultsDiv.appendChild(restaurantDiv);
            // });

            results.forEach(function (place) {
                var restaurantDiv = document.createElement('div');
                restaurantDiv.innerHTML = `
                    <h2>${place.name}</h2>
                    <p>${place.formatted_address}</p>
                `;
                restaurantResultsDiv.appendChild(restaurantDiv);
            });
        }

        function displayResults2(results) {
            var attractionResultsDiv = document.getElementById('attrectionResults');
            attractionResultsDiv.innerHTML = "";

            results.forEach(function (place) {
                var attractionDiv = document.createElement('div');
                var name = document.createElement('h2');
                var img = document.createElement('div');
                img.innerHTML = place.photos ? place.photos[0].html_attributions[0] : "";
                name.textContent = place.name;
                var address = document.createElement('p');
                address.textContent = place.formatted_address;

                attractionDiv.appendChild(name);
                attractionDiv.appendChild(address);
                attractionDiv.appendChild(img);
                attractionResultsDiv.appendChild(attractionDiv);
            });
        }

        function displayResults3(results) {
            var hotelsResultsDiv = document.getElementById('hotelsResults');
            hotelsResultsDiv.innerHTML = "";

            results.forEach(function (place) {
                var hotelsDiv = document.createElement('div');
                var name = document.createElement('h2');
                var img = document.createElement('div');
                img.innerHTML = place.photos ? place.photos[0].html_attributions[0] : "";
                name.textContent = place.name;
                var address = document.createElement('p');
                address.textContent = place.formatted_address;

                hotelsDiv.appendChild(name);
                hotelsDiv.appendChild(address);
                hotelsDiv.appendChild(img);
                hotelsResultsDiv.appendChild(hotelsDiv);
            });
        }

    </script>

</body>
</html>