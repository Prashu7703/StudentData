$sql = "SELECT * FROM students";
$result = $conn->query($sql);

echo "<h3>List of Students</h3>";
echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Course</th><th>Year</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['course']}</td>
            <td>{$row['year']}</td>
          </tr>";
}

echo "</table>";
