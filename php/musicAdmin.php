<?php
session_start();
if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
} else {
    header("Location: ../html/logIn.html");
    exit(); 
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harmony Music Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>    
    
</head>
<body>
<body>
    <main>

        <div class="container py-4">
            <header class="pb-3 mb-4 border-bottom">
                <a href="index.php" class="d-flex align-items-center text-dark text-decoration-none"></a>
                <span class="fs-4"> Admin Dashboard</span>

            </header>
            <div class="d-flex">
    <nav class="nav flex-column bg-dark vh-100 p-3" style="width: 250px;">
        <h4 class="text-center text-light">Admin Panel</h4>
        <a class="nav-link text-light active" href="musicAdmin.php">Available Music</a>
        <a class="nav-link text-light" href="categoryAdmin.php">Categories</a>
        <a class="nav-link text-light" href="usersAdmin.php">Users</a>
        <a class="nav-link text-light" id = 'signOut' href="logOut.php">Logout</a>
    </nav>
    <div class="container">
        <h1 class="my-4">View Music</h1>
    <!-- search -->
    <div class="container py-3">
    <form action="musicAdmin.php" method="GET">
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
                require('config.php');
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
                            <img src=\"$image\" class=\"w-100 fixed-image\" />
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
                            <button class=\"modify btn btn-info\" data-id=\"" . $id . "\"> Modify Music </button>
                            <button class=\"delete btn btn-danger\" data-id=\"" . $id . "\"> Delete Music </button>
                        </div>
                        </div>
                    </div>";
                    }
                }
                ?>
            </div>
        </div>
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
</div>
</body>
</html>

<script>
    $(document).ready(function(){
        $("#signOut").click(function(e){
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "logOut.php",
                success: function(response){
                    window.location.href = "../html/logIn.html";
                }
            });
        })});

    $(document).ready(function(){
        $(".delete").click(function(e){
            var productId = $(this).data("id");
            $.ajax({
                type: "POST",
                url: "deleteMusic.php",
                data : { id : productId },
                success: function(response){
                    //alert("Succesfully Deleted Music!");  
                    alert(response);      
                }
            });
        })});



    $(document).ready(function(){
        $(".modify").click(function(e){
            e.preventDefault();
            alert("hello");
        })});



</script>


<style>
    .nav{
        min-height: 100%;
    }
    .pagination {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }
    .pagination a {
      display: inline-block;
      padding: 5px 10px;
      margin-right: 5px;
      border: 1px solid #ccc;
      text-decoration: none;
    }
    .pagination a.active {
      background-color: #ccc;
    }
    .fixed-image {
            height: 300px;
            object-fit: cover;
        }

</style>