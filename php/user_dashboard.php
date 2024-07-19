<?php
session_start();
if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
} else {
    header("Location: ../html/logIn.html");
    exit(); 
    }
require("config.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Harmony Music</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/userDashboard.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>
    <!-- NAVIGATION BAR -->
    <nav>
  <ul>
    <li>
      <a href="#">Home</a>
    </li>
    <li>
      <a href="user_library.php">Library</a>
    </li>
    <li>
      <a href="#" id="signOut">Sign Out</a>
    </li>
  </ul>
  </nav>
  
  <!-- CAROSEL -->
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="../assets/images/carosel1.jpg" alt="Carosel1">
      <div class="carousel-caption d-none d-md-block">
      <h5>Abbey Road - The Beatles</h5>
      <p>"Abbey Road" is The Beatles' iconic 1969 album,
        celebrated for its innovative production and timeless songwriting.
         The album features a mix of rock, pop, and experimental tracks, 
         showcasing the band's diverse musical talents. Standout songs like "Come Together,"
          "Here Comes the Sun," and the medley on Side Two highlight the band's creative peak. 
          Known for its seamless transitions and sophisticated arrangements, "Abbey Road" captures The Beatles' 
          artistic synergy, making it a landmark release that remains influential in the music world.</p>
  </div>
    </div>

    <div class="item">
      <img src="../assets/images/carosel2.jpg" alt="Carosel2">
      <div class="carousel-caption d-none d-md-block">
      <h5>For All the Dogs - Drake</h5>
      <p>"For All the Dogs" is Drake's 2023 album that continues to explore his signature blend of rap,
         R&B, and introspective lyrics. The album features collaborations with a diverse array of artists, 
         showcasing Drake's versatility and ability to stay relevant in the ever-evolving music scene.
          With introspective tracks that delve into themes of love, loyalty, and success, "For All the Dogs"
           offers a nuanced look at Drake's personal and professional journey, resonating with both long-time 
           fans and new listeners alike.</p>
    </div>
    </div>

    <div class="item">
      <img src="../assets/images/carosel3.png" alt="Carosel3">
      <div class="carousel-caption d-none d-md-block">
      <h5>Astroworld - Travis Scott</h5>
      <p>"Astroworld" is Travis Scott's critically acclaimed 2018 album, 
        named after the defunct theme park in Houston, Texas.
         The album is a psychedelic journey through Scott's eclectic musical influences, 
         featuring a mix of rap, trap, and experimental sounds. With standout tracks like "Sicko Mode"
         and "Stargazing," "Astroworld" includes collaborations with a variety of top-tier artists and producers.
          The album's lush production, combined with Scott's distinctive vocal style, creates an immersive 
          listening experience that solidifies his place as a visionary in modern hip-hop.</p>
    </div>
    </div>
  </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

<!-- search -->
<div class="container py-3">
    <form action="user_dashboard.php" method="GET">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for Music..." name="search">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>
</div>


<!-- Music -->
<section style="background-color: #eee;">
  <div class="text-center container py-5">
    <h4 class="mt-4 mb-5"><strong>Available</strong></h4>
    <div class="row">
      <?php
require_once('config.php');
// NUMBER OF MUSIC PER PAGE
$musicPerPage = 6;

// Get the current page number from the URL parameter
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset based on the current page and number of music per page
$offset = ($page - 1) * $musicPerPage;

// Get music data from db using LIMIT and OFFSET for pagination, ALSO NAME WILL BE EITHER EMPTY OR NOT DEPENDING ON SEARCH
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT Products.*, Categories.category_name 
FROM Products 
INNER JOIN Categories ON Products.category_id = Categories.category_id
WHERE
    Products.name LIKE '%$search%'
    LIMIT $offset, $musicPerPage";


$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $cnt = 1;
  while($row = $result->fetch_assoc()) {
    $id = $row["product_id"];
    $name = $row["name"];
    $price = $row["price"];
    $category = $row["category_name"];
    $image = $row["image_url"];
    
    echo "<div class=\"col-lg-4 col-md-12 mb-4\">
        <div class=\"card\">
          <div class=\"bg-image hover-zoom ripple ripple-surface ripple-surface-light\"
            data-mdb-ripple-color=\"light\">
            <img src=\"$image\" class=\"w-100\" />
            <a href=\"#\">
              <div class=\"mask\">
                <div class=\"d-flex justify-content-start align-items-end h-100\">
                  <h5><span class=\"badge bg-primary ms-2\">New</span></h5>
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
            <h6 class=\"mb-3\">$$price</h6>
            <button class=\"addToLibrary\" data-id=\"" . $id . "\"> Add to Library </button>
          </div>
        </div>
      </div>";
    }
  }
?>
  </div>
</section>
<!-- Pagination -->
<div class="pagination">
    <?php
    // Total number of pages
    $totalPagesSql = "SELECT CEIL(COUNT(*) / $musicPerPage) AS totalMusic FROM Products";
    $totalPagesResult = $conn->query($totalPagesSql);
    $totalPages = $totalPagesResult->fetch_assoc()['totalMusic'];

    // Pagination links
    for ($i = 1; $i <= $totalPages; $i++) {
        $activeClass = $page == $i ? 'active' : '';
        echo "<a class='$activeClass' href='?page=$i'>$i</a>";
    }
    ?>
</div>

  <!-- FOOTER -->
<footer>
<!-- Copyright -->
<div class="text-center text-dark p-3" style="background-color: rgba(0, 0, 0, 0.2);">
  © 2024 Copyright:
  <a class="text-dark" >HarmonyMusic.com</a>
</div>
<!-- Copyright -->
</footer>
</div>
<!-- End of .container -->
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

        $(".addToLibrary").click(function(){
    var productId = $(this).data("id");
    var email = "<?php echo $email; ?>";
    if (confirm("Are you sure you want to add this item to your library?")) {
        $.ajax({
            type: "POST",
            url: "addToLibrary.php",
            data: { product_id: productId },
            success: function(response){
                alert("Product added to library!");
            }
        });
    }
});
    </script>

</html>