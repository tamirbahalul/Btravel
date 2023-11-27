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
    $is_admin = $_SESSION['is_admin'];
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
    <link rel="stylesheet" type="text/css" href="../css/styleCityPage.css">
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
                <li><a href="Comments.php">Comments</a></li>
                <li><a href="Recommendations.php">Recommendations</a></li>
            </ul>
        </div>
        <form id="searchForm" class="search-form" method="GET">
            <input id="place_autocomplete" type="text" placeholder="search city" name="location" id='city' required>
            <button type="submit" name="submit" value="search"><i class="search-button fa fa-search" aria-hidden="true"></i></button>
        </form>
    </div>
<div class="inclu">  
    <div class="nameC"><h1 class="temp" id="cityName"><?php echo "$location"; ?></h1></div>
    <div class="weatherAndSort">
        <div class="weatherC" id="getWeather">
           
        </div>
        <button id="sort-button" name="Sort" value="Sort" class="sort-button"><h3>Sort by Rating</h3></button>
    </div>
        <div class="heading">
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
</div>    
    <script>
        var currLocation = "<?php echo $location; ?>";
        window.onload = function() {
            if (currLocation) {
                searchRestaurants();
                searchAttractions();
                searchHotels();
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
            }
        };

        function searchHotels(){

            var city = currLocation;//document.getElementById('city').value;
            // Create a PlacesService object to interact with the Places API
            var placesService = new google.maps.places.PlacesService(document.createElement('div'));

            // Define the search request parameters
            var request = {
                query: 'hotels in ' + city,
                fields: ['name', 'formatted_address', 'geometry', 'rating', 'photos', 'opening_hours', 'website', 'reviews']
            };

            searchWithDetails(placesService, request)
                .then(results => {
                    displayResults(results, 'hotelsResults');
                })
                .catch(error => {
                    document.getElementById('hotelsResults').innerHTML = "No hotels found in the specified city.";
                });

}

        function searchAttractions(){

            var city = currLocation;//document.getElementById('city').value;

            // Create a PlacesService object to interact with the Places API
            var placesService = new google.maps.places.PlacesService(document.createElement('div'));

            // Define the search request parameters
            var request = {
                query: 'attractions in ' + city,
                fields: ['name', 'formatted_address', 'geometry', 'rating', 'photos', 'opening_hours', 'website', 'reviews']
            };

            searchWithDetails(placesService, request)
                .then(results => {
                    displayResults(results, 'attrectionResults');
                })
                .catch(error => {
                    document.getElementById('attrectionResults').innerHTML = "No attrections found in the specified city.";
                });

        }

        function getResultDetails(resolve, reject, placesService, result) {
            placesService.getDetails(
                { placeId: result['place_id'] }, 
                function(place, status) {
                    if (status !== google.maps.places.PlacesServiceStatus.OK) {
                        reject("Failed to fetch details for place_id=" + result['place_id'])
                    } else {
                        resolve(place);
                    }
                }
            );
        }

        async function searchWithDetails(placesService, request) {
            return new Promise((resolve, reject) => {
                placesService.textSearch(request, function (results, status) {
                    if (status !== google.maps.places.PlacesServiceStatus.OK) {
                        reject("Failed to fetch text search results from google");
                    } else {
                        const resultsWithDetailsPromises = results.map(result => 
                            new Promise((res, rej) => getResultDetails(res, rej, placesService, result))
                        );
                        resolve(Promise.all(resultsWithDetailsPromises));
                    }
                });
            });
        }

        function searchRestaurants() {
            var city = currLocation;//document.getElementById('city').value;

            // Create a PlacesService object to interact with the Places API
            var placesService = new google.maps.places.PlacesService(document.createElement('div'));

            // Define the search request parameters
            var request = {
                query: 'restaurants in ' + city,
                fields: ['name', 'formatted_address', 'geometry', 'rating', 'photos', 'opening_hours']
            };

            searchWithDetails(placesService, request)
                .then(results => {
                    displayResults(results, 'restaurantResults');
                })
                .catch(error => {
                    document.getElementById('restaurantResults').innerHTML = "No restaurants found in the specified city.";
                });
        }

        function displayResults(results, resultsDivId) {
            const resultsDiv = document.getElementById(resultsDivId);
            results.forEach(function (place) {
                const element = document.createElement('div');
                element.classList.add("google-place-result");
                const isOpen = !place.opening_hours ? '' : `${place.opening_hours.isOpen() ? 'Open' : 'Closed'} now`;
                const isRating = !place.rating ? 'No have rating' : `${place.rating}`;
                element.innerHTML = `
                    <a href="${place.url}" target="_blank"><h2>${place.name}</h2></a>
                    <p>${place.formatted_address}</p>
                    <p name="rating">rating: ${isRating}</p>
                    <p>${isOpen}</p>
                `;
                resultsDiv.appendChild(element);
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
        const sortButton = document.getElementById('sort-button');
        if(sortButton){
            sortButton.addEventListener('click', function () {
                const getRating = (child) => parseFloat(child.querySelector('p[name="rating"]').innerText.split(' ')[1]);
                const sortResults = (resultsDivId) => {
                    let sortedChildren = [];
                    const resultsDiv = document.getElementById(resultsDivId);
                    const children = resultsDiv.children;
                    for (let child of children) {
                        sortedChildren.push(child);
                    }

                    sortedChildren.sort((a, b) => getRating(b) - getRating(a));

                    resultsDiv.innerHtml = '';
                    sortedChildren.forEach(child => resultsDiv.appendChild(child));
                };
                
                ['hotelsResults', 'restaurantResults', 'attrectionResults'].forEach(resultsDiv => sortResults(resultsDiv));
            });
        }
    });

        function sortPlacesByRating(places) {
            return places.sort((a, b) => (b.rating || 0) - (a.rating || 0));    
        }
     
        //Weather :
        const apiKey = '177c4aa44e57140c1a28eba1c6c8b4e2';
        //document.getElementById('city').addEventListener('click', () => {
        const cityWeather = currLocation;
        
        if (cityWeather) {
            fetch(`https://api.openweathermap.org/data/2.5/weather?q=${cityWeather}&appid=${apiKey}`)
                .then(response => response.json())
                .then(data => {
                    if (data.cod === 200) {
                        const temperature = (data.main.temp - 273.15).toFixed(2); // Convert temperature to Celsius
                        const weatherDescription = data.weather[0].description;
                        const cityName = data.name;
                        const country = data.sys.country;
                        const weatherInfo = `Temperature in ${cityName},<br>${country}: ${temperature}°C<br>Weather: ${weatherDescription}`;
                        document.getElementById('getWeather').innerHTML = `<h2>${weatherInfo}</h2>`;
                    } else {
                        document.getElementById('weatherInfo').innerHTML = 'City not found.';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('weatherInfo').innerHTML = 'An error occurred while fetching weather data.';
                });
        } else {
            document.getElementById('weatherInfo').innerHTML = 'Please enter a city name.';
        }

    </script>

</body>
</html>