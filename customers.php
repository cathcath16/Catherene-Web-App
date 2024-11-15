<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission for adding customers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $register_id = $_POST['register_id']; // Get the selected register ID

    // Insert the new customer with the foreign key
    $query = "INSERT INTO customers (customer_name, email, register_id) VALUES ('$customer_name', '$email', '$register_id')";
    if (mysqli_query($conn, $query)) {
        $success = "Customer added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Fetch existing customers
$query = "SELECT * FROM customers";
$result = mysqli_query($conn, $query);

// Fetch register users for the dropdown
$register_query = "SELECT register_id, username FROM register";
$register_result = mysqli_query($conn, $register_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Management</title>
    <!-- Add Google Fonts link for custom font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://threadcurve.com/wp-content/uploads/2021/10/1-various-leather-bags-and-purses-display-Oct192021-1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        /* Header Styling */
        header {
            background: rgba(0, 0, 0, 0.6); /* Dark overlay for contrast */
            color: white;
            padding: 30px 20px;
            text-align: center;
            font-family: 'Playfair Display', serif; /* Elegant serif font for header */
        }

        header h1 {
            font-size: 3rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            font-weight: 500;
            font-size: 1.1rem;
            text-transform: uppercase;
        }

        nav a:hover {
            color: #ffb600; /* Golden hover effect */
            text-decoration: underline;
        }

        /* Main Content Styling */
        main {
            padding: 40px;
            text-align: center;
            margin-top: 40px;
        }

        h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        /* Form Styling */
        form {
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 50%;
            margin: 0 auto;
            font-family: 'Roboto', sans-serif;
        }

        form input, form select, form button {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            font-family: 'Roboto', sans-serif;
        }

        form button {
            background: #ffb600; /* Golden background */
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        form button:hover {
            background: #e69500; /* Darker golden shade for hover */
        }

        /* Table Styling */
        table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 1rem;
        }

        th {
            background: #333;
            color: #fff;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #ffb600;
            color: #333;
        }

        /* Logout Button Styling */
        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 25px;
            background: #d9534f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-family: 'Roboto', sans-serif;
            transition: background 0.3s ease;
        }

        .logout:hover {
            background: #c9302c;
        }

        /* Footer Join Links */
        .join-links {
            text-align: center;
            margin-top: 40px;
        }

        .join-links a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .join-links a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                width: 80%;
            }

            table {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <header>
        <h1>Customers Management</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="customers.php">Customers</a>
            <a class="logout" href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <!-- Form for adding new customers -->
        <h2>Add New Customer</h2>
        <form method="POST" action="">
            <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <input type="text" name="customer_name" placeholder="Customer Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <select name="register_id" required>
                <option value="">Select User</option>
                <?php while ($register_row = mysqli_fetch_assoc($register_result)): ?>
                    <option value="<?php echo htmlspecialchars($register_row['register_id']); ?>">
                        <?php echo htmlspecialchars($register_row['username']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Add Customer</button>
        </form>

        <!-- Existing Customers Table -->
        <h2>Existing Customers</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Register ID</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['customer_id']); ?></td>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['register_id']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>

</body>
</html>
