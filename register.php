<?php
// customer_register.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $aadhaar = $_POST['aadhaar'];
    $phone = $_POST['phone'];

    // Basic server-side validation
    $errors = [];

    if (!preg_match("/^\d{12}$/", $aadhaar)) {
        $errors[] = "Aadhaar number must be exactly 12 digits.";
    }

    if (!preg_match("/^\d{10}$/", $phone)) {
        $errors[] = "Phone number must be exactly 10 digits.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($age < 0 || $age > 120) {
        $errors[] = "Age must be between 0 and 120.";
    }

    if (empty($errors)) {
        // You can insert into your database here

        echo "<script>alert('Registration Successful!'); window.location.href='booking12.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('regcus3.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 150vh;
            margin: 0;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            width: 350px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 8px; /* Reduced radius */
        }

        .input-group button {
            width: 100%;
            padding: 12px;
            font-size: 15px;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .input-group button:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Customer Registration</h2>
    <form method="POST" action="#">
        <?php
        if (!empty($errors)) {
            echo '<div class="error">' . implode('<br>', $errors) . '</div>';
        }
        ?>
        <div class="input-group">
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="input-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob" required>
        </div>

        <div class="input-group">
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" required>
        </div>

        <div class="input-group">
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Transgender">Transgender</option>
            </select>
        </div>

        <div class="input-group">
            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="input-group">
            <label for="aadhaar">Aadhaar Number:</label>
            <input type="text" name="aadhaar" id="aadhaar" maxlength="12" pattern="\d{12}" required>
        </div>

        <div class="input-group">
            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" id="phone" maxlength="10" pattern="\d{10}" required>
        </div>

        <div class="input-group">
            <button type="submit">Register</button>
        </div>
    </form>
</div>

</body>
</html>
