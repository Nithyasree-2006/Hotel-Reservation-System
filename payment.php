<?php
// payment.php
session_start();
require_once("db_connection.php");

if (!isset($_SESSION['booking']) || !isset($_SESSION['total_price'])) {
    header("Location: booking.php");
    exit;
}

$bookingData = $_SESSION['booking'];
$total_price = $_SESSION['total_price'];

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $booking_date = $_POST['booking_date'];

    $stmt = $conn->prepare("INSERT INTO payments (name, amount, payment_method, booking_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $amount, $payment_method, $booking_date);
    $stmt->execute();
    $stmt->close();

    // Redirect to feedback page
    header("Location: feedback.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('payment-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 500px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .summary, .payment-options {
            margin-bottom: 25px;
        }

        .summary p {
            margin: 5px 0;
        }

        .payment-options label {
            display: block;
            margin-bottom: 10px;
        }

        .payment-options input[type="radio"] {
            margin-right: 10px;
        }

        .qr-image {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .qr-image img {
            width: 150px;
            height: 150px;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
    <script>
        function toggleQR(option) {
            const qr = document.getElementById('qr');
            const qrImage = document.getElementById('qr-img');
            const qrText = document.getElementById('qr-text');

            if (option === 'gpay' || option === 'phonepe') {
                qr.style.display = 'block';
                qrText.innerText = option === 'gpay' ? 'Scan to Pay with GPay' : 'Scan to Pay with PhonePe';
                qrImage.src = 'qrcode.jpeg';
            } else {
                qr.style.display = 'none';
            }
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Payment Summary</h2>

    <div class="summary">
        <p><strong>Name:</strong> <?= htmlspecialchars($bookingData['name']) ?></p>
        <p><strong>Room Type:</strong> <?= $bookingData['room_type'] ?></p>
        <p><strong>Number of Persons:</strong> <?= $bookingData['num_persons'] ?></p>
        <p><strong>Number of Days:</strong> <?= $bookingData['num_days'] ?></p>
        <p><strong>Single Cots:</strong> <?= $bookingData['single_cots'] ?></p>
        <p><strong>Double Cots:</strong> <?= $bookingData['double_cots'] ?></p>
        <p><strong>Booking Date:</strong> <?= $bookingData['booking_date'] ?></p>
        <hr>
        <p><strong>Total Price:</strong> $<?= $total_price ?></p>
    </div>

    <form method="POST">
        <div class="payment-options">
            <label><input type="radio" name="payment_method" value="cash" checked onclick="toggleQR('none')"> Cash on Delivery</label>
            <label><input type="radio" name="payment_method" value="gpay" onclick="toggleQR('gpay')"> Google Pay</label>
            <label><input type="radio" name="payment_method" value="phonepe" onclick="toggleQR('phonepe')"> PhonePe</label>
        </div>

        <div class="qr-image" id="qr">
            <p id="qr-text">Scan to Pay</p>
            <img id="qr-img" src="" alt="QR Code for payment">
        </div>

        <input type="hidden" name="amount" value="<?= $total_price ?>">
        <input type="hidden" name="name" value="<?= htmlspecialchars($bookingData['name']) ?>">
        <input type="hidden" name="booking_date" value="<?= $bookingData['booking_date'] ?>">
        <button type="submit">Confirm Payment</button>
    </form>
</div>

</body>
</html>
