<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM register WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid credentials!";
        }
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Add Google Fonts link for custom fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Lobster&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('https://img.freepik.com/free-vector/fluid-style-modern-abstract-business-backdrop-design_1017-55802.jpg?semt=ais_hybrid') no-repeat center center fixed; 
            background-size: cover;
            background-position: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.75); /* Slightly opaque background for blending */
            padding: 50px 40px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px); /* Blur effect to blend with the background image */
            transition: transform 0.3s ease-in-out;
        }

        .login-container:hover {
            transform: scale(1.05);
        }

        h2 {
            text-align: center;
            font-family: 'Lobster', cursive;
            color: blue; /* White text for contrast */
            font-weight: 600;
            font-size: 36px;
            margin-bottom: 20px;
            letter-spacing: 1px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4); /* Add text shadow for better readability */
        }

        .input-field {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: 2px solid rgba(255, 255, 255, 0.6); /* Light border that blends into the background */
            border-radius: 10px;
            font-size: 16px;
            color: #333;
            background-color: rgba(255, 255, 255, 0.8); /* Light background with transparency */
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        .input-field:focus {
            border-color: #ff5f5f;
            background-color: #fff;
            outline: none;
        }

        .button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #ff5f5f, #ff3b3b);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .button:hover {
            background: linear-gradient(45deg, #ff3b3b, #ff1c1c);
            transform: translateY(-5px);
        }

        .error-message {
            color: #e74c3c;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            font-size: 14px;
            color: #fff;
        }

        a {
            color: #ff5f5f;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #ff3b3b;
            text-decoration: underline;
        }

        /* Responsive design */
        @media screen and (max-width: 480px) {
            .login-container {
                padding: 30px;
            }
            h2 {
                font-size: 28px;
            }
            .input-field, .button {
                font-size: 14px;
            }
        }

    </style>
</head>
<body>

    <div class="login-container">
    
        <h2>Login to Your Account</h2>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

        <form method="POST" action="">
            <input type="text" name="username" class="input-field" placeholder="Username" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>
            <button type="submit" class="button">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
    </div>

</body>
</html>
