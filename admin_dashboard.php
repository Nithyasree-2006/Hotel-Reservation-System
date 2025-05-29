<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "hotel_reservation");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('dashboard.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        .sidebar {
            position: fixed;
            width: 220px;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
        }

        .section {
            display: none;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .active {
            display: block;
        }

        .filter-form {
            margin-bottom: 15px;
        }

        .filter-form label,
        .filter-form input,
        .filter-form select {
            margin: 5px;
        }
    </style>
    <script>
        function showSection(id) {
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => section.classList.remove('active'));
            document.getElementById(id).classList.add('active');
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <a href="#" onclick="showSection('rooms')">Room Availability</a>
        <a href="#" onclick="showSection('payments')">Payment Details</a>
        <a href="#" onclick="showSection('profit')">Profit</a>
        <a href="#" onclick="showSection('feedback')">Feedback</a>
    </div>

    <div class="main-content">

        <!-- ROOMS -->
        <div id="rooms" class="section active">
            <h2>Rooms Available</h2>
            <ul>
                <?php
                $res = $conn->query("SELECT room_type, available_count FROM room_availability");
                while ($row = $res->fetch_assoc()) {
                    echo "<li>{$row['room_type']} Rooms: {$row['available_count']} available</li>";
                }
                ?>
            </ul>
        </div>

        <!-- PAYMENTS -->
        <div id="payments" class="section">
            <h2>Payment Details</h2>
            <form class="filter-form" method="GET">
                <label for="payment-range">Select Date Range:</label>
                <select name="payment-range" id="payment-range">
                    <option value="10">Last 10 Records</option>
                    <option value="30">Last 30 Days</option>
                    <option value="60">Last 2 Months</option>
                </select>
                <input type="submit" value="Filter">
            </form>
            <ul>
                <?php
                $limit = isset($_GET['payment-range']) ? (int)$_GET['payment-range'] : 10;
                $result = $conn->query("SELECT name, room_type, total_price, booking_date FROM booking ORDER BY booking_date DESC LIMIT $limit");
                while ($row = $result->fetch_assoc()) {
                    echo "<li>{$row['booking_date']} - {$row['name']} booked {$row['room_type']} - ₹{$row['total_price']}</li>";
                }
                ?>
            </ul>
        </div>

        <!-- PROFIT -->
        <div id="profit" class="section">
            <h2>Profit</h2>
            <ul>
                <?php
                $daily = $conn->query("SELECT SUM(total_price) AS daily_profit FROM booking WHERE DATE(booking_date) = CURDATE()");
                $monthly = $conn->query("SELECT SUM(total_price) AS monthly_profit FROM booking WHERE MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE())");

                $daily_profit = $daily->fetch_assoc()['daily_profit'] ?? 0;
                $monthly_profit = $monthly->fetch_assoc()['monthly_profit'] ?? 0;
                ?>
                <li>Daily Profit: ₹<?= number_format($daily_profit, 2) ?></li>
                <li>Monthly Profit: ₹<?= number_format($monthly_profit, 2) ?></li>
            </ul>
        </div>

        <!-- FEEDBACK -->
        <div id="feedback" class="section">
            <h2>Feedback</h2>
            <form class="filter-form" method="GET">
                <label for="feedback-range">Select Date Range:</label>
                <select name="feedback-range" id="feedback-range">
                    <option value="10">Last 10 Feedbacks</option>
                    <option value="30">Last 30 Days</option>
                    <option value="60">Last 2 Months</option>
                </select>
                <input type="submit" value="Filter">
            </form>
            <ul>
                <?php
                $limit = isset($_GET['feedback-range']) ? (int)$_GET['feedback-range'] : 10;
                $res = $conn->query("SELECT name, comment FROM feedback ORDER BY feedback_date DESC LIMIT $limit");
                while ($row = $res->fetch_assoc()) {
                    echo "<li><strong>{$row['name']}</strong>: {$row['comment']}</li>";
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
