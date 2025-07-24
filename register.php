<?php
include 'db_connect.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = strtolower(trim($_POST['role'])); // Make role lowercase and trim spaces
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $status = 'active'; // Default status

    // Check if email already exists
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $msg = "❌ Email already registered. Please use another email.";
    } else {
        $sql = "INSERT INTO users (name, email, password, role, phone, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $password, $role, $phone, $status);

        if ($stmt->execute()) {
            header("Location: login.php?msg=registered");
            exit();
        } else {
            $msg = "❌ Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | Indoor Hub</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            background: linear-gradient(120deg, #3f87a6, #ebf8e1, #f69d3c);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-box {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
        }
        .form-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-box input, .form-box select, .form-box button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .form-box button {
            background: #3f87a6;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .form-box button:hover {
            background: #336b8b;
        }
        .form-box a {
            text-align: center;
            display: block;
            margin-top: 10px;
            color: #3f87a6;
            text-decoration: none;
        }
        .form-box p {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="form-box">
    <h2>Create Account</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="admin">Administrator</option>
            <option value="manager">Centre Manager</option>
            <option value="trainer">Trainer</option>
            <option value="user">User</option>
        </select>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
        <?php if (!empty($msg)) echo "<p>$msg</p>"; ?>
    </form>
    <a href="login.php">Already have an account? Login</a>
</div>
</body>
</html>