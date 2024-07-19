<?php
    session_start();
    require_once('config.php');

    // Check if the email is set in the session
    if (isset($_SESSION["email"])) {
        $email = $_SESSION["email"];
    } else {
        echo("Error");
        header("Location: ../html/logIn.html");
        exit(); 
    }

    $sql = "SELECT customer_id, username FROM Customers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row["customer_id"];
        $username = $row["username"];
    } else {
        echo "Error";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Harmony Music</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <style>
            .large-button {
                font-size: 1.5em;
                padding: 15px 30px;
            }
        </style>
    </head>
    <body>
    <!-- NAVIGATION BAR -->
    <nav>
        <ul>
            <li><a href="user_dashboard.php">Home</a></li>
            <li>
              <a href="user_library.php">Library</a>
            </li>
            <li><a href="#" id="signOut">Sign Out</a></li>
        </ul>
    </nav>
    <!-- Music -->
    <section style="background-color: #eee;">
        <div class="text-center container py-5">
            <h4 class="mt-4 mb-5"><strong><?php echo $username."'s Library" ?></strong></h4>
            <button class="btn btn-primary mb-4 large-button" id="shuffleButton">Shuffle</button>
            <button class="btn btn-primary mb-4 large-button" id="playAllButton">Play All</button>
            <button class="btn btn-primary mb-4 large-button" id="pauseButton" disabled>Pause</button>
            <button class="btn btn-primary mb-4 large-button" id="previousButton" disabled>Previous</button>
            <button class="btn btn-primary mb-4 large-button" id="skipButton" disabled>Skip</button>
            <div class="mb-4">
            <label for="categoryFilter">Filter by Category:</label>
            <select class="form-select" id="categoryFilter">
                <option value="all">All Categories</option>
                <?php
                // Fetch categories from the database and dynamically generate options
                $sql = "SELECT category_id, category_name FROM Categories";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $categoryId = $row["category_id"];
                        $categoryName = $row["category_name"];
                        echo "<option value=\"$categoryId\">$categoryName</option>";
                    }
                }
                ?>
            </select>
        </div>

            <div class="row" id="musicContainer">
                <?php
                require('config.php');
                require("getLibrary.php");

                if (!empty($product_ids)) {
                    $product_ids_str = implode(",", $product_ids);

                    $selectedCategoryId = isset($_GET['category']) ? $_GET['category'] : 'all';

                      if ($selectedCategoryId !== 'all') {
                          $sql = "SELECT Products.*, Categories.category_name 
                                  FROM Products 
                                  INNER JOIN Categories ON Products.category_id = Categories.category_id
                                  WHERE Products.product_id IN ($product_ids_str)
                                  AND Categories.category_id = $selectedCategoryId";
                      } else {
                          $sql = "SELECT Products.*, Categories.category_name 
                                  FROM Products 
                                  INNER JOIN Categories ON Products.category_id = Categories.category_id
                                  WHERE Products.product_id IN ($product_ids_str)";
                      }

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $id = $row["product_id"];
                            $name = $row["name"];
                            $category = $row["category_name"];
                            $categoryId = $row["category_id"];
                            $image = $row["image_url"];
                            $audio = $row["audio_url"];

                            echo "<div class=\"col-lg-4 col-md-12 mb-4 music-card\" data-category=\"$categoryId\">
                                    <div class=\"card\">
                                      <div class=\"bg-image hover-zoom ripple ripple-surface ripple-surface-light\"
                                        data-mdb-ripple-color=\"light\">
                                        <img src=\"$image\" class=\"w-100\" />
                                        <a href=\"#\">
                                          <div class=\"mask\">
                                            <div class=\"d-flex justify-content-start align-items-end h-100\">
                                              <h5><span class=\"badge bg-primary ms-2\">Owned</span></h5>
                                            </div>
                                          </div>
                                          <div class=\"hover-overlay\">
                                            <div class=\"mask\" style=\"background-color: rgba(251, 251, 251, 0.15);\"></div>
                                          </div>
                                        </a>
                                      </div>
                                      <div class=\"card-body\">
                                      <p>#$id</p>
                                        <a href=\"\" class=\"text-reset\">
                                          <h5 class=\"card-title mb-3\">$name</h5>
                                        </a>
                                        <a href=\"\" class=\"text-reset\">
                                          <p>Category: $category</p>
                                        </a>
                                        <audio controls class=\"audio-element\">
                                            <source src=\"$audio\" type=\"audio/mpeg\">
                                            Your browser does not support the audio element.
                                        </audio>
                                        <br>
                                        <button class=\"btn btn-danger\" data-id=\"" . $id . "\">
                                            Remove From Library
                                        </button> 
                                      </div>
                                    </div>
                                  </div>";
                        }
                    }
                } else {
                    echo "<p>No songs found.</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <script>
        document.getElementById("shuffleButton").addEventListener("click", function() {
            var container = document.getElementById("musicContainer");
            var elementsArray = Array.prototype.slice.call(container.getElementsByClassName("music-card"));
            elementsArray.sort(function() {
                return 0.5 - Math.random();
            });
            container.innerHTML = "";
            elementsArray.forEach(function(element) {
                container.appendChild(element);
            });
        });

        let audioElements;
        let currentAudioIndex = 0;
        let isPlayingAll = false;

        function playNextAudio() {
            if (currentAudioIndex < audioElements.length) {
                audioElements[currentAudioIndex].play();
                audioElements[currentAudioIndex].addEventListener('ended', playNextAudio);
            } else {
                document.getElementById("skipButton").disabled = true;
                document.getElementById("pauseButton").disabled = true;
                document.getElementById("previousButton").disabled = true;
                isPlayingAll = false;
            }
        }

        document.getElementById("playAllButton").addEventListener("click", function() {
            audioElements = document.querySelectorAll(".audio-element");
            currentAudioIndex = 0;
            if (audioElements.length > 0) {
                playNextAudio();
                document.getElementById("skipButton").disabled = false;
                document.getElementById("pauseButton").disabled = false;
                document.getElementById("previousButton").disabled = false;
                isPlayingAll = true;
            }
        });

        document.getElementById("skipButton").addEventListener("click", function() {
            if (currentAudioIndex < audioElements.length) {
                audioElements[currentAudioIndex].pause();
                audioElements[currentAudioIndex].currentTime = 0;
                audioElements[currentAudioIndex].removeEventListener('ended', playNextAudio);
                currentAudioIndex++;
                playNextAudio();
            }
        });

        document.getElementById("pauseButton").addEventListener("click", function() {
            if (isPlayingAll && currentAudioIndex < audioElements.length) {
                audioElements[currentAudioIndex].pause();
                document.getElementById("pauseButton").disabled = true;
                document.getElementById("playAllButton").disabled = false;
            }
        });

        document.getElementById("previousButton").addEventListener("click", function() {
            if (currentAudioIndex > 0) {
                audioElements[currentAudioIndex].pause();
                audioElements[currentAudioIndex].currentTime = 0;
                audioElements[currentAudioIndex].removeEventListener('ended', playNextAudio);
                currentAudioIndex--;
                playNextAudio();
            }
        });
    </script>
      <!-- FOOTER -->
    <footer>
    <!-- Copyright -->
    <div class="text-center text-dark p-3" style="background-color: rgba(0, 0, 0, 0.2);">
      © 2024 Copyright:
      <a class="text-dark" >HarmonyMusic.com</a>
    </div>
    <!-- Copyright -->
    </footer>
    </body>


<script>
    $(document).ready(function(){
        $("#signOut").click(function(e){
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "logout.php",
                success: function(response){
                    window.location.href = "../html/logIn.html";
                }
            });
        })});
</script>

<script>
    $(document).ready(function(){
        $(document).on("click", ".btn-danger", function(){
            var buttonId = $(this).data("id"); 
            if (confirm("Are you sure you want to remove this song from the library?")) {
                $.ajax({
                    type: "POST",
                    url: "deleteFromLibrary.php",
                    data: {
                        songId: buttonId
                    },
                    success: function(response){
                        alert("Song Removed Successfully!");
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Request failed with status: ' + status);
                    }
                });
            }
        });
    });
</script>


      <script>
      function filterByCategory(categoryId) {
          var cards = document.querySelectorAll(".music-card");
          cards.forEach(function(card) {
              var category = card.dataset.category;
              if (categoryId === 'all' || category === categoryId) {
                  card.style.display = 'block';
              } else {
                  card.style.display = 'none';
              }
          });
      }

      document.getElementById("categoryFilter").addEventListener("change", function() {
          var selectedCategoryId = this.value;
          filterByCategory(selectedCategoryId);
      });
      </script>


<style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans);

      html {
        height:100%;
        margin: 0 0;
        padding: 0 0;
        background: #fccb90;

            /* Chrome 10-25, Safari 5.1-6 */
        background: -webkit-linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
      }

      nav {
        min-width: 100%;
        background: #fccb90;

        /* Chrome 10-25, Safari 5.1-6 */
        background: -webkit-linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);

        /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
        margin: 0 0;
        padding: 0 0;
      }

      nav ul {
        text-align: center;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.2) 25%, rgba(255, 255, 255, 0.2) 75%, rgba(255, 255, 255, 0) 100%);
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.1), inset 0 0 1px rgba(255, 255, 255, 0.6);
      }

      nav ul li {
        display: inline-block;
      }

      nav ul li a {
        padding: 48px;
        font-family: "Open Sans";
        text-transform:uppercase;
        color: white;
        font-size: 18px;
        text-decoration: none;
        display: block;
      }

      nav ul li a:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1), inset 0 0 1px rgba(255, 255, 255, 0.6);
        background: rgba(255, 255, 255, 0.1);
        color: white;
      }
      .carousel-inner .item img {
      width: 100%; /* Ensures images take full width of carousel */
      height: 700px; /* Maintains aspect ratio */
      }

    .carousel {
        max-width: 100%; /* Ensures the carousel fits its container */
        margin: 0 0; /* Centers the carousel */
    }

</style>
</html>