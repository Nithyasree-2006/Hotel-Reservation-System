<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System</title>
    <style>
        body {
            background: url('regimage.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            text-align: center;
            color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            background: rgba(0, 0, 0, 0.6);
            padding: 30px;
            border-radius: 12px;
            display: inline-block;
            margin-top: 60px;
            width: 85%;
            max-width: 650px;
        }

        h1 {
            margin-bottom: 10px;
        }

        p {
            margin-top: 5px;
            font-size: 18px;
        }

        .button-container {
            margin: 25px 0;
        }

        .button-container a button {
            padding: 12px 25px;
            margin: 10px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            background: #28a745;
            color: white;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .button-container a button:hover {
            background: #218838;
        }

        .feature-list {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        .feature {
            background: white;
            color: black;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            width: 200px;
        }

        .feature img {
            width: 100%;
            height: 120px;
            border-radius: 5px;
        }

        .feature p {
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1> Welcome To Grand Hotel</h1>
    <div class="button-container">
        <a href="admin1.php"><button>Admin</button></a>
        <a href="registeration3.php"><button>Customer</button></a>
    </div>

    <h2>Our Services</h2>
    <div class="feature-list">
        <div class="feature">
            <img src="room.jpg" alt="Luxury Rooms">
            <p>Spacious Rooms with Sea View</p>
        </div>
        <div class="feature">
            <img src="room2.jpg" alt="Fine Dining">
            <p>Room with AC facility</p>
        </div>
        <div class="feature">
            <img src="room3.jpg" alt="Spa Services">
            <p>Luxury room comfortable for conference meet</p>
        </div>
    </div>
</div>

</body>
</html>
