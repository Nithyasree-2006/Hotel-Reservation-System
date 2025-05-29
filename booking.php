<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking'])) {
    $name = $_POST['name'];
    $num_persons = (int)$_POST['num_persons'];
    $num_days = (int)$_POST['num_days'];
    $room_type = $_POST['room_type'];
    $single_cots = (int)$_POST['single_cots'];
    $double_cots = (int)$_POST['double_cots'];
    $booking_date = $_POST['booking_date'];

    $prices = [
        'AC' => 100,
        'Non-AC' => 70,
        'Conference' => 200
    ];

    $base_price = $prices[$room_type] ?? 0;
    $extra_single_cot = max(0, $single_cots - 1) * 10;
    $extra_double_cot = max(0, $double_cots - 1) * 20;
    $total_price = ($base_price + $extra_single_cot + $extra_double_cot) * $num_days;

    $_SESSION['booking'] = [
        'name' => $name,
        'num_persons' => $num_persons,
        'num_days' => $num_days,
        'room_type' => $room_type,
        'single_cots' => $single_cots,
        'double_cots' => $double_cots,
        'booking_date' => $booking_date
    ];
    $_SESSION['total_price'] = $total_price;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel_reservation";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO booking (name, num_persons, num_days, room_type, single_cots, double_cots, booking_date, total_price)
                                VALUES (:name, :num_persons, :num_days, :room_type, :single_cots, :double_cots, :booking_date, :total_price)");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':num_persons', $num_persons);
        $stmt->bindParam(':num_days', $num_days);
        $stmt->bindParam(':room_type', $room_type);
        $stmt->bindParam(':single_cots', $single_cots);
        $stmt->bindParam(':double_cots', $double_cots);
        $stmt->bindParam(':booking_date', $booking_date);
        $stmt->bindParam(':total_price', $total_price);

        $stmt->execute();

        header("Location: payment144.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Booking with Chatbot</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('book.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            width: 420px;
            margin: 50px auto;
        }
        .input-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }

        .chatbot-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            background: #fef4e8;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            font-size: 14px;
        }
        .chatbot-header {
            background: #ffb877;
            padding: 10px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            font-weight: bold;
            text-align: center;
        }
        .chatbot-messages {
            padding: 10px;
            flex: 1;
            overflow-y: auto;
            max-height: 250px;
        }
        .chatbot-message {
            margin-bottom: 10px;
        }
        .chatbot-message.user {
            text-align: right;
        }
        .chatbot-message.bot {
            text-align: left;
        }
        .chatbot-input-area {
            display: flex;
            padding: 10px;
            gap: 5px;
        }
        .chatbot-input-area input {
            flex: 1;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .chatbot-input-area button {
            background: #ff9933;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Hotel Booking</h2>
    <form method="POST" action="">
        <input type="hidden" name="booking" value="1">

        <div class="input-group">
            <label>Full Name:</label>
            <input type="text" name="name" required>
        </div>
        <div class="input-group">
            <label>Number of Persons:</label>
            <input type="number" name="num_persons" required>
        </div>
        <div class="input-group">
            <label>Number of Days:</label>
            <input type="number" name="num_days" required>
        </div>
        <div class="input-group">
            <label>Room Type:</label>
            <select name="room_type" required>
                <option value="AC">AC - $100/day</option>
                <option value="Non-AC">Non-AC - $70/day</option>
                <option value="Conference">Conference - $200/day</option>
            </select>
        </div>
        <div class="input-group">
            <label>Single Cots:</label>
            <select name="single_cots" required>
                <option value="1">1 Cot - Free</option>
                <option value="2">2 Cots - $10 extra</option>
                <option value="3">3 Cots - $20 extra</option>
            </select>
        </div>
        <div class="input-group">
            <label>Double Cots:</label>
            <select name="double_cots" required>
                <option value="1">1 Cot - Free</option>
                <option value="2">2 Cots - $20 extra</option>
                <option value="3">3 Cots - $40 extra</option>
            </select>
        </div>
        <div class="input-group">
            <label>Booking Date:</label>
            <input type="date" name="booking_date" required>
        </div>
        <button type="submit">Book Now</button>
    </form>
</div>

<!-- Chatbot UI -->
<div class="chatbot-container">
    <div class="chatbot-header">Ask Me Anything</div>
    <div class="chatbot-messages" id="chatMessages"></div>
    <div class="chatbot-input-area">
        <input type="text" id="userInput" placeholder="Ask about rooms, pricing..." />
        <button onclick="sendMessage()">Send</button>
    </div>
</div>

<script>
function sendMessage() {
    const input = document.getElementById("userInput");
    const message = input.value.trim();
    if (!message) return;

    appendMessage("You", message, "user");
    input.value = "";

    const typing = appendMessage("Bot", "<i>Typing...</i>", "bot");

    fetch("chatbot.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message })
    }).then(res => res.json())
    .then(data => {
        typing.innerHTML = `<strong>Bot:</strong> ${data.reply}`;
    }).catch(() => {
        typing.innerHTML = "<strong>Bot:</strong> Sorry, something went wrong.";
    });
}

function appendMessage(sender, text, type) {
    const msg = document.createElement("div");
    msg.className = `chatbot-message ${type}`;
    msg.innerHTML = `<strong>${sender}:</strong> ${text}`;
    const box = document.getElementById("chatMessages");
    box.appendChild(msg);
    box.scrollTop = box.scrollHeight;
    return msg;
}
</script>

</body>
</html>
