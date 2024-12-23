<?php
// add_student.php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$dbname ="userdb"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'];
    $student_id = $_POST['student_id'];
    $student_email = $_POST['student_email'];
    $student_age = $_POST['student_age'];
    $student_course = $_POST['student_course'];

    // Validate input
    if (!empty($student_name) && !empty($student_id) && !empty($student_email) && !empty($student_age) && !empty($student_course)) {
        // Prepare SQL query to insert the student into the database
        $stmt = $conn->prepare("INSERT INTO students (name, id, email, age, course) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $student_name, $student_id, $student_email, $student_age, $student_course);

        if ($stmt->execute()) {
            $message = "Student added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9f9f9;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 400px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-container input,
        .form-container select {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }

        .form-container button {
            padding: 10px;
            font-size: 1em;
            color: #fff;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #4cae4c;
        }

        .form-container .message {
            margin-top: 10px;
            color: #d9534f;
        }

        .form-container .message.success {
            color: #5cb85c;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Student</h2>
        <form action="" method="post">
            <label for="student_name">Student Name</label>
            <input type="text" id="student_name" name="student_name" required>

            <label for="student_ID">Student ID</label>
            <input type="text/number" id="student_id" name="student_id" required>

            <label for="student_email">Student Email</label>
            <input type="email" id="student_email" name="student_email" required>

            <label for="student_age">Student Age</label>
            <input type="number" id="student_age" name="student_age" required>

            <label for="student_course">Student Course</label>
            <input type="text" id="student_course" name="student_course" required>

            <button type="submit">Add Student</button>
        </form>

        <?php if (!empty($message)): ?>
            <p class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : ''; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
