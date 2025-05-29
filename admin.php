<?php
// admin_login.php
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and validate input
    $user = $_POST['user'];
    $password = $_POST['password'];

    // Check if user is 'admin' and password is 5 digits
    if ($user === "admin" && strlen($password) === 5 && is_numeric($password)) {
        // Connect to database
        $conn = new mysqli("localhost", "root", "", "hotel_reservation");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Validate against database
        $stmt = $conn->prepare("SELECT * FROM admin WHERE user_id = ? AND password = ?");
        $stmt->bind_param("ss", $user, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Successful login
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password for admin.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $error_message = "Only user 'admin' with 5-digit password is allowed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('admin3.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin-left: 30px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .input-field {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .input-field label {
            margin-bottom: 5px;
            text-align: left;
        }

        .input-field input {
            width: 90%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .input-field button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .input-field button:hover {
            background-color: #218838;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <div class="input-field">
                <label for="user">User:</label>
                <input type="text" id="user" name="user" value="admin" readonly>
            </div>
            <div class="input-field">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" maxlength="5" required>
            </div>
            <div class="input-field">
                <button type="submit">Login</button>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </form>
    </div>

</body>
</html>
