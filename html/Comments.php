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
    <link rel="stylesheet" type="text/css" href="../css/styleComments.css">
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
        <form id="searchForm" class="search-form">
            <input type="text" placeholder="search city" name="location" id='city' required>
            <button type="submit" name="submit" value="search"><i class="search-button fa fa-search" aria-hidden="true"></i></button>
        </form>
        <div class="comments-body">
            <button onClick="fetchByRating()">Sort by rating</button>
            <div>
                <form id="add-comment-form" class="add-comment-form">
                    <label for="place">Place</label>
                    <input id="place_autocomplete" name="place" type="text" required />
                    <label for="rating">Rating</label>
                    <select name="rating" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <label for="text">Text</label>
                    <input name="text" type="text" />
                    <button type="submit">Add Comment</button>
                </form>
            </div>
            <div id="comments">

            </div>
        </div>
    </div>
    <script>
        var currLocation = "<?php echo $location; ?>";
        window.onload = function() {
            const input = document.getElementById("place_autocomplete");
            const options = {
                fields: ["place_id", "formatted_address", "name"],
                strictBounds: false,
            };
            const autocomplete = new google.maps.places.Autocomplete(input, options);

            var form = document.getElementById("add-comment-form");
            form.addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent the default form submission
                const place = autocomplete.getPlace();
                onAddComment(this, place); // Call the "x" function with the form as an argument
            });

            loadComments(null);
        };

        function loadComments(queryParams) {
            let url = 'api/comments/fetch.php';
            if (queryParams) {
                url += '?' + Object.keys(queryParams).map(k => `${k}=${queryParams[k]}`).join('&');
            }

            fetch(url, {
                method: 'GET'
            })
            .then(response => {
                return response.json();
            })
            .then(comments => {
                const isAdmin = "<?php echo $is_admin ?>";
                const userId = "<?php echo $user_id ?>";
                const commentsDiv = document.getElementById("comments");
                commentsDiv.innerHTML = '';
                comments.forEach(comment => {
                    const canDelete = isAdmin || comment['user_id'] === userId;
                    const deleteButton = `
                        <button onClick="deleteComment(${comment['id']})">
                            Delete
                        </button>
                    `;
                    const commentDiv = document.createElement('div');
                    commentDiv.innerHTML = `
                        <div class="comment">
                            <h3>${comment['place_name']}</h3>
                            <h4>${comment['place_address']}</h4>
                            <h5>Rating: ${comment['rating']}</h5>
                            <p>${comment['text']}</p>
                            <span>${comment['username']}</span>
                            <span>${comment['creation_date']}</span>
                            ${canDelete ? deleteButton : ''}
                        </div>
                    `;
                    commentsDiv.append(commentDiv);
                });
            });
        }

        function fetchByRating() {
            loadComments({ orderby: 'desc' })
        }

        function deleteComment(comment_id) {
            const data = { comment_id };
            postJsonData('api/comments/delete.php', data);
            location.reload();
        }

        async function postJsonData(url, jsonData) {
            // Make an HTTP POST request using the Fetch API
            return fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jsonData)
            })
            .catch(function(error) {
                // Handle any errors that occurred during the fetch
                console.error('Fetch error:', error);
            });
        }

        function onAddComment(form, place) {
            const { formatted_address: place_address, place_id, name: place_name } = place;
            const rating = form.rating.value;
            const text = form.text.value;
            const user_id = "<?php echo $user_id; ?>";
            const data = { place_id, place_name, place_address, rating, text, user_id };
            postJsonData('api/comments/add.php', data);
            location.reload();
        }
    </script>
</body>
</html>