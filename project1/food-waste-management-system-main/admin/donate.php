<?php
// Include the necessary connection file
include("../connect.php");

// Start session to manage user sessions
session_start();

// Check if the user is logged in, otherwise redirect to signin
if (!isset($_SESSION['name']) || $_SESSION['name'] == '') {
    header("location:signin.php");
    exit();
}

// Database connection
$host = 'localhost'; // Change if necessary
$username = 'root';
$password = '';
$database = 'demo';
$port = 3306; // Adjust if MySQL uses a different port

$connection = mysqli_connect($host, $username, $password, $database, $port);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize result
$result = null;

if (isset($_POST['location'])) {
    $location = mysqli_real_escape_string($connection, $_POST['location']);

    // Query for donations
    $sql = "SELECT * FROM food_donations WHERE location='$location'";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Admin Dashboard Panel</title>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo-name">
            <span class="logo_name">ADMIN</span>
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="admin.php"><i class="uil uil-estate"></i><span class="link-name">Dashboard</span></a></li>
                <li><a href="analytics.php"><i class="uil uil-chart"></i><span class="link-name">Analytics</span></a></li>
                <li><a href="#"><i class="uil uil-heart"></i><span class="link-name">Donates</span></a></li>
                <li><a href="feedback.php"><i class="uil uil-comments"></i><span class="link-name">Feedbacks</span></a></li>
                <li><a href="adminprofile.php"><i class="uil uil-user"></i><span class="link-name">Profile</span></a></li>
            </ul>
            <ul class="logout-mode">
                <li><a href="../logout.php"><i class="uil uil-signout"></i><span class="link-name">Logout</span></a></li>
                <li class="mode"><a href="#"><i class="uil uil-moon"></i><span class="link-name">Dark Mode</span></a></li>
            </ul>
        </div>
    </nav>

    <!-- Dashboard Section -->
    <section class="dashboard">
        <div class="top">
            <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
        </div>
        <div class="activity">
            <div class="location">
                <form method="post">
                    <label for="location" class="logo">Select Location:</label>
                    <select id="location" name="location">
                        <option value="chennai">Chennai</option>
                        <option value="madurai">Madurai</option>
                        <option value="coimbatore">Coimbatore</option>
                    </select>
                    <input type="submit" value="Get Details">
                </form>
                <br>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    echo "<div class=\"table-container\">";
                    echo "<table class=\"table\">";
                    echo "<thead><tr><th>Name</th><th>Food</th><th>Category</th><th>Phone No</th><th>Date</th><th>Address</th><th>Quantity</th></tr></thead><tbody>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['food']) . "</td>
                                <td>" . htmlspecialchars($row['category']) . "</td>
                                <td>" . htmlspecialchars($row['phoneno']) . "</td>
                                <td>" . htmlspecialchars($row['date']) . "</td>
                                <td>" . htmlspecialchars($row['address']) . "</td>
                                <td>" . htmlspecialchars($row['quantity']) . "</td>
                              </tr>";
                    }
                    echo "</tbody></table></div>";
                } else {
                    echo "<p>No results found for this location.</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <script src="admin.js"></script>
</body>
</html>
