<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission for adding products
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $customer_id = $_POST['customer_id']; // Get the selected customer ID

    // Insert the new product with the foreign key
    $query = "INSERT INTO products (product_name, price, stock, customer_id) VALUES ('$product_name', '$price', '$stock', '$customer_id')";
    if (mysqli_query($conn, $query)) {
        $success = "Product added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Fetch existing products
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

// Fetch customers for the dropdown
$customer_query = "SELECT customer_id, customer_name FROM customers";
$customer_result = mysqli_query($conn, $customer_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <!-- Link to Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Raleway:wght@400;600&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        /* Global Styles */
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://t3.ftcdn.net/jpg/03/55/60/70/360_F_355607062_zYMS8jaz4SfoykpWz5oViRVKL32IabTP.jpg') no-repeat center center fixed;
            background-size: cover;
            color: black;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        /* Header Styling */
        header {
            background: rgba(0, 0, 0, 0.7); /* Dark overlay for contrast */
            color: black;
            padding: 30px 20px;
            text-align: center;
            font-family: 'Raleway', sans-serif;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5);
        }

        header h1 {
            font-size: 3rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 20px;
            font-weight: 500;
            font-size: 1.1rem;
            text-transform: uppercase;
        }

        nav a:hover {
            color: #ffcc00; /* Golden hover effect */
            text-decoration: underline;
        }

        /* Form Styling */
        form {
            background: rgba(255, 255, 255, 0.85); /* Semi-transparent white */
            padding: 40px;
            margin: 20px auto;
            width: 60%;
            border-radius: 8px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            font-family: 'Roboto', sans-serif;
        }

        form h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        input, select, button {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        input[type="number"], select {
            font-family: 'Montserrat', sans-serif;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #0056b3;
        }

        /* Table Styling */
        table {
            width: 90%;
            margin: 40px auto;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.15);
        }

        th, td {
            padding: 15px;
            text-align: left;
            font-size: 1rem;
        }

        th {
            background: #333;
            color: #fff;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        tr:hover {
            background-color: #ffcc00; /* Golden hover effect for rows */
            color: #333;
        }

        /* Logout Button */
        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 25px;
            background: #d9534f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-family: 'Raleway', sans-serif;
            transition: background 0.3s ease;
        }

        .logout:hover {
            background: #c9302c;
        }

        /* Join Links */
        .join-links {
            text-align: center;
            margin-bottom: 20px;
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
    </style>
</head>
<body>

    <header>
        <h1>Product Management</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="customers.php">Customers</a>
            <a class="logout" href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <!-- Form for adding new products -->
        <form method="POST" action="">
            <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <h2>Add New Product</h2>
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="number" name="stock" placeholder="Quantity" required>
            <select name="customer_id" required>
                <option value="">Select Customer</option>
                <?php while ($customer_row = mysqli_fetch_assoc($customer_result)): ?>
                    <option value="<?php echo htmlspecialchars($customer_row['customer_id']); ?>">
                        <?php echo htmlspecialchars($customer_row['customer_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Add Product</button>
        </form>

        <!-- Existing Products Table -->
        <h2>Existing Products</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Customer ID</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                <td><?php echo htmlspecialchars($row['customer_id']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>

</body>
</html>
