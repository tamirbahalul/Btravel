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
    <link rel="stylesheet" type="text/css" href="../css/styleRecommendations.css">
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
    </div>
    <div class="comments-body">    
        <h1>Top 3 Recommendations</h1>
        <div id="comments"></div>
    </div>
    <script>
        var currLocation = "<?php echo $location; ?>";
        window.onload = function() {
            loadComments();
        };

        function loadComments() {
            fetch('api/recommendations/fetch.php', {
                method: 'GET'
            })
            .then(response => {
                return response.json();
            })
            .then(comments => {
                const commentsDiv = document.getElementById("comments");
                commentsDiv.innerHTML = '';
                comments.forEach((comment, index) => {
                    const commentDiv = document.createElement('div');
                    commentDiv.innerHTML = `
                        <div class="comment comment-${index}" id="comment-${comment['id']}">
                            <h3>${comment['place_name']}</h3>
                            <h4>${comment['place_address']}</h4>
                            <h5>Rating: ${comment['rating']}</h5>
                            <div name="comment-text">
                                <p name="content">${comment['text']}</p>
                            </div>
                            <span>${comment['username']}</span>
                            <span>${comment['creation_date']}</span>
                        </div>
                    `;
                    commentsDiv.append(commentDiv);
                });
            });
        }
    </script>
</body>
</html>