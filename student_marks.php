<?php
// student_marks.php

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
$dbname = "userdb"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = trim($_POST['student_name']);
    $student_id = trim($_POST['student_id']);
    $subject1 = trim($_POST['subject1']);
    $marks1 = trim($_POST['marks1']);
    $subject2 = trim($_POST['subject2']);
    $marks2 = trim($_POST['marks2']);

    // Validate input
    if (
        !empty($student_name) &&
        !empty($student_id) &&
        !empty($subject1) &&
        !empty($marks1) &&
        !empty($subject2) &&
        !empty($marks2)
    ) {
        // Check if the student ID exists in the students table
        $check_student = $conn->prepare("SELECT id FROM students WHERE id = ?");
        $check_student->bind_param("i", $student_id);
        $check_student->execute();
        $check_student_result = $check_student->get_result();

        if ($check_student_result->num_rows > 0) {
            // Check for duplicate entry in student_marks table
            $check_duplicate = $conn->prepare("SELECT id FROM student_marks WHERE student_id = ? AND subject1 = ? AND subject2 = ?");
            $check_duplicate->bind_param("iss", $student_id, $subject1, $subject2);
            $check_duplicate->execute();
            $result = $check_duplicate->get_result();

            if ($result->num_rows > 0) {
                $message = "Marks already added for this student and subjects.";
            } else {
                // Insert marks into the student_marks table
                $stmt = $conn->prepare("INSERT INTO student_marks (student_name, student_id, subject1, marks1, subject2, marks2) VALUES (?, ?, ?, ?, ?, ?)");
                if (!$stmt) {
                    die("SQL Error: " . $conn->error);
                }
                $stmt->bind_param("sisisi", $student_name, $student_id, $subject1, $marks1, $subject2, $marks2);

                if ($stmt->execute()) {
                    $message = "Marks added successfully!";
                } else {
                    $message = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
            $check_duplicate->close();
        } else {
            $message = "Student ID not found.";
        }
        $check_student->close();
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
    <title>Add Student Marks</title>
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
        <h2>Add Student Marks</h2>
        <form action="" method="post">
            <label for="student_name">Student Name</label>
            <input type="text" id="student_name" name="student_name" required>

            <label for="student_id">Student ID</label>
            <input type="number" id="student_id" name="student_id" required>

            <label for="subject1">Subject 1</label>
            <input type="text" id="subject1" name="subject1" required>

            <label for="marks1">Marks 1</label>
            <input type="number" id="marks1" name="marks1" required>

            <label for="subject2">Subject 2</label>
            <input type="text" id="subject2" name="subject2" required>

            <label for="marks2">Marks 2</label>
            <input type="number" id="marks2" name="marks2" required>

            <button type="submit">Add Marks</button>
        </form>

        <?php if (!empty($message)): ?>
            <p class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : ''; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
