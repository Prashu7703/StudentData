<?php
// delete_student.php

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
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        $message = "No student found with ID: $student_id";
    }

    $stmt->close();
}

// Handle delete submission
if (isset($_POST['delete'])) {
    $student_id = $_POST['student_id'];

    // Delete the student
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("s", $student_id);

    if ($stmt->execute()) {
        $message = "Student with ID $student_id has been deleted successfully!";
        $student = null; // Reset the student data after deletion
    } else {
        $message = "Error deleting student: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student</title>
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
            border: none;
            cursor: pointer;
        }

        .form-container .delete-btn {
            background-color: #d9534f;
        }

        .form-container .delete-btn:hover {
            background-color: #c9302c;
        }

        .form-container .search-btn {
            background-color: #5bc0de;
        }

        .form-container .search-btn:hover {
            background-color: #31b0d5;
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
        <h2>Delete Student</h2>
        <!-- Search Form -->
        <form action="" method="post">
            <label for="student_id">Enter Student ID to Search</label>
            <input type="text/number" id="student_id" name="student_id" value="<?php echo isset($_POST['student_id']) ? $_POST['student_id'] : ''; ?>" required>
            <button type="submit" class="search-btn" name="search">Search</button>
        </form>

        <!-- Display and Delete Form -->
        <?php if ($student): ?>
            <p><strong>Student ID:</strong> <?php echo $student['id']; ?></p>
            <p><strong>Name:</strong> <?php echo $student['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
            <p><strong>Age:</strong> <?php echo $student['age']; ?></p>
            <p><strong>Course:</strong> <?php echo $student['course']; ?></p>

            <form action="" method="post">
                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                <button type="submit" class="delete-btn" name="delete">Delete</button>
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
