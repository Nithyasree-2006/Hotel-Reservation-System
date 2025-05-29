<?php
// feedback.php

$host = "localhost";
$user = "root";
$pass = "";
$db = "hotel_reservation";

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create table if not exists (without 'comment')
$createTable = "
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    room_maintenance VARCHAR(50),
    room_comfort VARCHAR(50),
    food_service VARCHAR(50),
    overall_experience VARCHAR(50),
    rating INT,
    suggestion TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($createTable);

// Handle form submission
$successMessage = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $room_maintenance = $_POST['room_maintenance'];
    $room_comfort = $_POST['room_comfort'];
    $food_service = $_POST['food_service'];
    $overall_experience = $_POST['overall_experience'];
    $rating = $_POST['rating'];
    $suggestion = $_POST['suggestion'];

    $sql = "INSERT INTO feedback (name, room_maintenance, room_comfort, food_service, overall_experience, rating, suggestion)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssis", $name, $room_maintenance, $room_comfort, $food_service, $overall_experience, $rating, $suggestion);

    if ($stmt->execute()) {
        $successMessage = "Thank you for your feedback!";
    } else {
        $successMessage = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('fee1.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 40px;
            margin: 0;
        }

        .container {
            max-width: 600px;
            background: rgba(255, 255, 255, 0.95);
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Guest Feedback Form</h2>

    <?php if ($successMessage): ?>
        <div class="message"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="name">Your Name:</label>
        <input type="text" name="name" required>

        <label for="room_maintenance">Maintenance of the Room:</label>
        <select name="room_maintenance" required>
            <option value="">-- Select --</option>
            <option value="Excellent">Excellent</option>
            <option value="Good">Good</option>
            <option value="Average">Average</option>
            <option value="Poor">Poor</option>
        </select>

        <label for="room_comfort">Was the room comfortable?</label>
        <select name="room_comfort" required>
            <option value="">-- Select --</option>
            <option value="Very Comfortable">Very Comfortable</option>
            <option value="Comfortable">Comfortable</option>
            <option value="Okay">Okay</option>
            <option value="Uncomfortable">Uncomfortable</option>
        </select>

        <label for="food_service">Food Service:</label>
        <select name="food_service" required>
            <option value="">-- Select --</option>
            <option value="Excellent">Excellent</option>
            <option value="Good">Good</option>
            <option value="Average">Average</option>
            <option value="Poor">Poor</option>
        </select>

        <label for="overall_experience">Overall Experience:</label>
        <select name="overall_experience" required>
            <option value="">-- Select --</option>
            <option value="Outstanding">Outstanding</option>
            <option value="Very Good">Very Good</option>
            <option value="Satisfactory">Satisfactory</option>
            <option value="Needs Improvement">Needs Improvement</option>
        </select>

        <label for="rating">Rate Us (1 to 5):</label>
        <select name="rating" required>
            <option value="">-- Select --</option>
            <option value="5">5 - Excellent</option>
            <option value="4">4 - Very Good</option>
            <option value="3">3 - Good</option>
            <option value="2">2 - Fair</option>
            <option value="1">1 - Poor</option>
        </select>

        <label for="suggestion">Any Other Suggestions?</label>
        <textarea name="suggestion" rows="4" placeholder="Write your suggestions here..."></textarea>

        <button type="submit">Submit Feedback</button>
    </form>
</div>

</body>
</html>
