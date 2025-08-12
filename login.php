<?php
global $conn;
include 'db_con/db_connect.php';
session_start();

// ✅ Redirect if user is already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $role = strtolower($_SESSION['role']);
    switch ($role) {
        case 'admin':
            header("Location: admin.php");
            exit();
        case 'manager':
            header("Location: centremanagerdashboard.php");
            exit();
        case 'trainer':
            header("Location: trainer_dashboard.php");
            exit();
        default:
            header("Location: user_dashboard.php");
            exit();
    }
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($user['status'] !== 'active') {
            $msg = "Your account is inactive. Please contact the administrator.";
        } elseif (password_verify($password_input, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            $role = strtolower($user['role']);
            switch ($role) {
                case 'admin':
                    header("Location: admin.php");
                    break;
                case 'manager':
                    header("Location: centremanagerdashboard.php");
                    break;
                case 'trainer':
                    header("Location: trainer_dashboard.php");
                    break;
                default:
                    header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $msg = "❌ Invalid password.";
        }
    } else {
        $msg = "❌ No user found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Indoor Hub</title>
    <style>
        body {
            background: linear-gradient(120deg, #3f87a6, #ebf8e1, #f69d3c);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-box {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
        }

        .form-box h2 {
            margin-bottom: 24px;
            font-size: 26px;
            color: #333;
            text-align: center;
        }

        .form-box form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .form-box input {
            padding: 14px 16px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }

        .form-box input:focus {
            border-color: #3f87a6;
            outline: none;
        }

        .form-box button {
            padding: 14px;
            background-color: #3f87a6;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .form-box button:hover {
            background-color: #2c6b8f;
            transform: scale(1.02);
        }

        .form-box p {
            color: red;
            text-align: center;
            margin: 0;
            font-weight: 500;
        }

        .form-box a {
            margin-top: 10px;
            text-align: center;
            color: #3f87a6;
            text-decoration: none;
            font-weight: 500;
            display: block;
            font-size: 14px;
        }

        .form-box a:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {
            .form-box {
                padding: 30px 25px;
            }
        }
    </style>
</head>
<body>
<div class="form-box">
    <h2>User Login</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Login</button>
        <?php if (!empty($msg)) echo "<p>$msg</p>"; ?>
    </form>
    <a href="register.php">Don't have an account? Register now</a>
</div>
</body>
</html>
