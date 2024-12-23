<?php
// dashboard.php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College Dashboard</title>
    <style>
        /* General body properties */
        body {
            display: flex;
            font-family: Arial, sans-serif;
            height: 100vh;
            margin: 0;
        }

        /* Sidebar navigation styles */
        .sidebar-left {
            width: 250px;
            background-color: #f4f4f4;
            padding: 15px;    
            height: 100vh;
            border-right: 1px solid #ddd;
            position: fixed;
            left: 0;
            top: 0;
            box-sizing: border-box;
        }

        /* Styling for the welcome message in the sidebar */
        .welcome-message {
            font-size: 1em;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Navigation links */
        .sidebar-left a {
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
            text-decoration: none;
            color: #333;
            background-color: #e7e7e7;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .sidebar-left a:hover {
            background-color: #ddd;
            border-color: #aaa;
        }

        /* Main content area */
        .content-container {
            margin-left: 270px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar Section with Welcome Message -->
    <div class="sidebar-left">
        <div class="welcome-message">
            <h3>Welcome, <?php echo $username; ?>!</h3>
        </div>
        <a href="search_student.php">Search Student</a>
        <a href="add_student.php">Add Student</a>
        <a href="modify_student.php">Modify Student</a>
        <a href="update_student.php">Update Student</a>
        <a href="delete_student.php">Delete Student</a>
        <a href="student_marks.php">Student Marks</a>
        <a href="logout.php">Logout</a>

    </div>

    <!-- Main Content Area -->
    <div class="content-container">
        <h2>Main Dashboard Content</h2>
        <p>This is the area for the main content display.</p>
    </div>
</body>
</html>
