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

$orderby = "";  // Initialize the variable
if (isset($_GET['orderby'])) {
    // Get the location from the URL parameter sent by the homepage form
    $orderby = $_GET['orderby'];
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 0;
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
                <li><a href="Comments.php">Comments</a></li>
                <li><a href="Recommendations.php">Recommendations</a></li>
            </ul>
        </div>
    </div>
    <div class="comments-body">    
        <button id="sortB" onClick="fetchByRating()">Sort by rating</button>
        <form id="searchForm" class="search-form" onsubmit="GET">
            <input type="text" id="name-filter" placeholder="filter location" name="location">
            <button type="submit" value="search"><i class="fa fa-search" aria-hidden="true"></i></button>
        </form>
        <form id="add-comment-form" class="add-comment-form">
            <label for="place">Place</label>
            <input id="place_autocomplete" name="place" type="text" placeholder="write place" required />
            <label for="rating">Rating</label>
            <select name="rating" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <label for="text">Text</label>
            <input name="text" type="text" placeholder="write text"/>
            <button type="submit">Add Comment</button>
        </form>
        <div id="comments"></div>
        <div id="page-buttons" class="page-buttons">
            <button id="prev-page-button" onclick="goToPrevPage()">Previous</button>
            <button id="next-page-button" onclick="goToNextPage()">Next</button>
        </div>
    </div>
    <script>
        const pageSize = 5;
        var pageNumber = parseInt("<?php echo $page; ?>");
        var currLocation = "<?php echo $location; ?>";
        var currOrderBy = "<?php echo $orderby; ?>";


        window.onload = function() {
            const input = document.getElementById("place_autocomplete");
            const options = {
                fields: ["place_id", "formatted_address", "name"],
                strictBounds: false,
                types: ['geocode', 'establishment']
            };
            const autocomplete = new google.maps.places.Autocomplete(input, options);

            var form = document.getElementById("add-comment-form");
            form.addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent the default form submission
                const place = autocomplete.getPlace();
                onAddComment(this, place); // Call the "x" function with the form as an argument
            });

            loadComments();
        };

        function loadComments(queryParams = {}) {
            let url = `api/comments/fetch.php?`;

            queryParams['limit'] = pageSize;
            queryParams['offset'] = pageSize * pageNumber;

            if (currOrderBy) {
                queryParams['orderby'] = currOrderBy;
            }

            if (currLocation) {
                queryParams['location'] = currLocation;
            }

            if (queryParams) {
                url += Object.keys(queryParams).map(k => `${k}=${queryParams[k]}`).join('&');
            }
            
            fetch(url, {
                method: 'GET'
            })
            .then(response => {
                return response.json();
            })
            .then(response => {
                const comments = response['rows'];
                const count = parseInt(response['count']);
                const isAdmin = "<?php echo $is_admin ?>";
                const userId = "<?php echo $user_id ?>";
                const commentsDiv = document.getElementById("comments");
                
                const prevPageButton = document.getElementById("prev-page-button");
                const nextPageButton = document.getElementById("next-page-button");
                prevPageButton.disabled = pageNumber <= 0;
                nextPageButton.disabled = count <= (pageNumber + 1) * pageSize;

                commentsDiv.innerHTML = '';
                comments.forEach(comment => {
                    const canDelete = isAdmin !== "0" || comment['user_id'] === userId;
                    const canEdit = comment['user_id'] === userId;
                    const deleteButton = `
                        <button onClick="deleteComment(${comment['id']})">
                            Delete
                        </button>
                    `;
                    const EditButton = `
                        <button name="edit-button" onClick="editComment(${comment['id']})">
                            Edit Comment
                        </button>
                    `;
                    const commentDiv = document.createElement('div');
                    commentDiv.innerHTML = `
                        <div class="comment" id="comment-${comment['id']}">
                            <h3>${comment['place_name']}</h3>
                            <h4>${comment['place_address']}</h4>
                            <h5>Rating: ${comment['rating']}</h5>
                            <div name="comment-text">
                                <p name="content">${comment['text']}</p>
                            </div>
                            <span><strong>${comment['username']}</strong></span>
                            <span><strong>${comment['creation_date']}</strong></span>
                            ${canDelete ? deleteButton : ''}
                            ${canEdit ? EditButton : ''}
                        </div>
                    `;
                    commentsDiv.append(commentDiv);
                });
            });
        }
        
        function updateQueryParam(paramName, paramValue) {
            const currentUrl = window.location.href;
            if (currentUrl.indexOf("?") !== -1) {
                if (currentUrl.match(/(&|\?)page=/) !== null) {
                    const regexStr = `([?&])${paramName}=.*?(&|$)`;
                    const regex = new RegExp(regexStr);
                    updatedUrl = currentUrl.replace(regex, `$1${paramName}=${paramValue}$2`);
                } else {
                    updatedUrl = currentUrl + `&${paramName}=${paramValue}`;
                }
            } else {
                updatedUrl = currentUrl + `?${paramName}=${paramValue}`;
            }

            // Redirect to the updated URL
            console.log(updatedUrl);
            window.location.href = updatedUrl;
        }

        function goToPage(newPageNumber) {
            updateQueryParam('page', newPageNumber);
            // console.log('newPageNumber', newPageNumber);
            // const currentUrl = window.location.href;
            // console.log('currentUrl', currentUrl);
            // let updatedUrl = currentUrl;
            // if (currentUrl.indexOf("?") !== -1) {
            //     if (currentUrl.match(/(&|?)page=/) !== null) {
            //         updatedUrl = currentUrl.replace(/([?&])page=.*?(&|$)/, '$1page=' + newPageNumber + '$2');
            //     } else {
            //         updatedUrl = currentUrl + "&page=" + newPageNumber;    
            //     }
            // } else {
            //     updatedUrl = currentUrl + "?page=" + newPageNumber;
            // }
        }

        function goToPrevPage() {
            goToPage(pageNumber - 1);
        }

        function goToNextPage() {
            goToPage(pageNumber + 1);
        }

        function fetchByRating() {
            updateQueryParam('orderby', currOrderBy === 'desc' ? 'asc' : 'desc');
        }

        function deleteComment(comment_id) {
            const data = { comment_id };
            postJsonData('api/comments/delete.php', data);
            location.reload();
        }

        function editComment(comment_id) {
            const commentDiv = document.getElementById(`comment-${comment_id}`);
            const commentTextDiv = commentDiv.querySelector('[name="comment-text"]');
            const commentContent = commentTextDiv.querySelector('[name="content"]');
            commentTextDiv.innerHTML = `
                <input type="text" name="content" value="${commentContent.textContent}">
            `;

            const editButton = commentDiv.querySelector('[name="edit-button"]');
            editButton.innerHTML = "Save Comment";
            editButton.onclick = function() {
                saveComment(comment_id);
            };
        }

        function saveComment(comment_id) {
            const commentDiv = document.getElementById(`comment-${comment_id}`);
            const commentTextDiv = commentDiv.querySelector('[name="comment-text"]');
            const commentContent = commentTextDiv.querySelector('[name="content"]');
            console.log(commentContent);
            commentTextDiv.innerHTML = `
                <p name="content">${commentContent.value}</p>
            `;

            fetch('api/comments/edit.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ comment_id, comment_text: commentContent.value })
            })
            .then(response => {
                const saveButton = commentDiv.querySelector('[name="edit-button"]');
                saveButton.innerHTML = "Edit Comment";
                saveButton.onclick = function() {
                    editComment(comment_id);
                };
            })
            .catch(error => {
                console.log(error);
            });
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