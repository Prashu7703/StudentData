<?php
// update_student.php

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
$student = null;

// Handle search submission
if (isset($_POST['search'])) {
    $student_id = $_POST['student_id'];

    // Fetch the student's current details
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        $message = "No student found with ID: $student_id";
    }

    $stmt->close();
}

// Handle update submission
if (isset($_POST['update'])) {
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $student_email = $_POST['student_email'];
    $student_age = $_POST['student_age'];
    $student_course = $_POST['student_course'];

    // Update the student details
    $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, age = ?, course = ? WHERE id = ?");
    $stmt->bind_param("ssiss", $student_name, $student_email, $student_age, $student_course, $student_id);

    if ($stmt->execute()) {
        $message = "Student details updated successfully!";
    } else {
        $message = "Error updating student: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Student</title>
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
        .form-container select,
        .form-container button {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }

        .form-container button {
            color: #fff;
            background-color: #5cb85c;
            border: none;
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
        <h2>Update Student</h2>
        <!-- Search Form -->
        <form action="" method="post">
            <label for="student_id">Enter Student ID to Search</label>
            <input type="text/number" id="student_id" name="student_id" value="<?php echo isset($_POST['student_id']) ? $_POST['student_id'] : ''; ?>" required>
            <button type="submit" name="search">Search</button>
        </form>

        <!-- Update Form -->
        <?php if ($student): ?>
            <form action="" method="post">
                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                <label for="student_name">Student Name</label>
                <input type="text" id="student_name" name="student_name" value="<?php echo $student['name']; ?>" required>

                <label for="student_name">Student ID</label>
                <input type="text" id="student_id" name="student_id" value="<?php echo $student['id']; ?>" required>


                <label for="student_email">Student Email</label>
                <input type="email" id="student_email" name="student_email" value="<?php echo $student['email']; ?>" required>

                <label for="student_age">Student Age</label>
                <input type="number" id="student_age" name="student_age" value="<?php echo $student['age']; ?>" required>

                <label for="student_course">Student Course</label>
                <input type="text" id="student_course" name="student_course" value="<?php echo $student['course']; ?>" required>

                <button type="submit" name="update">Update</button>
            </form>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <p class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : ''; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
