<?php
// search_marks.php

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

$search_message = "";
$student_data = null;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];

    if (!empty($student_id)) {
        $stmt = $conn->prepare("SELECT student_name, subject1, marks1, subject2, marks2 FROM student_marks WHERE student_id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $student_data = $result->fetch_assoc();
        } else {
            $search_message = "No records found for the entered Student ID.";
        }

        $stmt->close();
    } else {
        $search_message = "Please enter a valid Student ID.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Student Marks</title>
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

        .container {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 400px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }

        .container form {
            display: flex;
            flex-direction: column;
        }

        .container label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .container input {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }

        .container button {
            padding: 10px;
            font-size: 1em;
            color: #fff;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .container button:hover {
            background-color: #4cae4c;
        }

        .container .message {
            margin: 10px 0;
            color: #d9534f;
        }

        .container .message.success {
            color: #5cb85c;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border: 1px solid #ddd;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Search Student Marks</h2>
        <form action="" method="post">
            <label for="student_id">Enter Student ID</label>
            <input type="number" id="student_id" name="student_id" required>

            <button type="submit">Search Marks</button>
        </form>

        <?php if (!empty($search_message)): ?>
            <p class="message"><?php echo $search_message; ?></p>
        <?php endif; ?>

        <?php if ($student_data): ?>
            <table>
                <tr>
                    <th>Student Name</th>
                    <th>Subject 1</th>
                    <th>Marks 1</th>
                    <th>Subject 2</th>
                    <th>Marks 2</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($student_data['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($student_data['subject1']); ?></td>
                    <td><?php echo htmlspecialchars($student_data['marks1']); ?></td>
                    <td><?php echo htmlspecialchars($student_data['subject2']); ?></td>
                    <td><?php echo htmlspecialchars($student_data['marks2']); ?></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
