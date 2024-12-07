<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Logout logic
if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: ../landing page/index.php");
    exit;
}

// Include user privileges
include_once 'user_privileges.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous" />
</head>

<body>

    <!-- Full Width Header -->
    <header>
        <nav class="navbar">
            <a class="navbar-brand" href="home.php">
            <img src="../assets/img/plmun.png" alt="Logo" width="75" height="auto">
            </a>
            <button class="openbtn" onclick="openNav()">&#9776; Menu</button>
        </nav>
    </header>

    <!-- Sidebar -->
    <div id="mySidepanel" class="bg-sidebar">
        <h1 class="text-start ms-3 mt-3 h6 fw-bold">Welcome</h1>
        <div class="img-admin text-center">
            <img class="rounded-circle" src="../assets/img/default.png" alt="Admin" width="100" height="100">
            <p><?= isset($_SESSION['user_role']) ? htmlspecialchars($_SESSION['user_role']) : 'Guest'; ?></p>
        </div>
        <div class="bg-list d-flex flex-column align-items-start fw-bold gap-2 mt-4">
            <ul class="list-unstyled">
                <li class="h7"><a class="nav-link" href="home.php"><i class="fal fa-home-lg-alt me-2"></i> Home</a></li>
                <?php if (hasAccess('courses.php')): ?>
                    <li class="h7"><a class="nav-link" href="course.php"><i class="fal fa-bookmark me-2"></i> Course</a></li>
                <?php endif; ?>
                <?php if (hasAccess('alumni_list.php')): ?>
                    <li class="h7"><a class="nav-link" href="alumni_list.php"><i class="far fa-graduation-cap me-2"></i> Alumni</a></li>
                <?php endif; ?>
                <?php if (hasAccess('dean_list.php')): ?>
                    <li class="h7"><a class="nav-link" href="alumni_list.php"><i class="far fa-graduation-cap me-2"></i> Alumni</a></li>
                <?php endif; ?>
                <?php if (hasAccess('program_list.php')): ?>
                    <li class="h7"><a class="nav-link" href="alumni_list.php"><i class="far fa-graduation-cap me-2"></i> Alumni</a></li>
                <?php endif; ?>
                <li class="h7"><a class="nav-link" href="?logout=true">Logout<i class="fal fa-sign-out-alt ms-2"></i></a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="dashboard-content">
    </div>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "250px"; // Expand sidebar
            document.querySelector(".dashboard-content").style.marginRight = "250px"; // Adjust content margin
            document.querySelector(".openbtn").style.display = "none"; // Hide the menu button

            // Add event listener to close sidebar when clicking outside
            document.addEventListener('click', closeNavOnClickOutside);
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0"; // Collapse sidebar
            document.querySelector(".dashboard-content").style.marginRight = "0"; // Reset content margin
            document.querySelector(".openbtn").style.display = "block"; // Show the menu button

            // Remove the event listener
            document.removeEventListener('click', closeNavOnClickOutside);
        }

        function closeNavOnClickOutside(event) {
            const sidebar = document.getElementById("mySidepanel");
            const openButton = document.querySelector(".openbtn");

            // Check if the click was outside the sidebar and the button
            if (!sidebar.contains(event.target) && event.target !== openButton) {
                closeNav();
            }
        }
    </script>

</body>
</html>
