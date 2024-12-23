<?php
// login.php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connect to database
    $host = 'localhost';
    $db = 'userdb';
    $user = 'root';
    $pass = '';
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the input values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if user exists
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $db_username, $db_password);

    if ($stmt->num_rows > 0) {
        // Fetch the result
        $stmt->fetch();
        
        // Verify the password
        if (password_verify($password, $db_password)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;

            header('Location: college_dashboard.php'); // Redirect to dashboard
            exit();
        } else {
            $error_message = "Incorrect password!";
        }
    } else {
        $error_message = "No user found with that username!";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login Page</h2>
    
    <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>
    
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="signup.php">Sign Up here</a></p>
</body>
</html>
