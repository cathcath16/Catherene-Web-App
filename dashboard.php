<?php
session_start();
include('db_connection.php');

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Default query is INNER JOIN
$query_inner = "
    SELECT p.product_id, p.product_name, p.price, p.stock, c.customer_name, c.email
    FROM products p
    INNER JOIN customers c ON p.customer_id = c.customer_id
";

// RIGHT JOIN query
$query_right = "
    SELECT p.product_id, p.product_name, p.price, p.stock, c.customer_name, c.email
    FROM products p
    RIGHT JOIN customers c ON p.customer_id = c.customer_id
";

// LEFT JOIN query
$query_left = "
    SELECT p.product_id, p.product_name, p.price, p.stock, c.customer_name, c.email
    FROM products p
    LEFT JOIN customers c ON p.customer_id = c.customer_id
";

// FULL OUTER JOIN (Simulated) query
$query_outer = "
    (SELECT p.product_id, p.product_name, p.price, p.stock, c.customer_name, c.email
    FROM products p
    LEFT JOIN customers c ON p.customer_id = c.customer_id)
    UNION
    (SELECT p.product_id, p.product_name, p.price, p.stock, c.customer_name, c.email
    FROM products p
    RIGHT JOIN customers c ON p.customer_id = c.customer_id)
";

// Default query is inner join
$query = $query_inner;

// Check if the user has selected a specific join type from a dropdown or link
if (isset($_GET['join_type'])) {
    $join_type = $_GET['join_type'];

    // Sanitize the input to prevent SQL Injection
    $join_type = mysqli_real_escape_string($conn, $join_type);

    switch ($join_type) {
        case 'inner':
            $query = $query_inner;
            break;
        case 'right':
            $query = $query_right;
            break;
        case 'left':
            $query = $query_left;
            break;
        case 'outer':
            $query = $query_outer;
            break;
        default:
            $query = $query_inner;  // Default to inner join if no valid type is selected
    }
}

// Execute the selected query
$result = mysqli_query($conn, $query);

// Error handling for query execution
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Link to Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        /* Global Body Styles */
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background: url('https://www.kokoroyale.com/wp-content/uploads/A-collection-of-luxury-crossbody-bags-on-a-display.png') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        /* Header Styling */
        header {
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent black for contrast */
            color: #fff;
            padding: 30px 20px;
            text-align: center;
            font-family: 'Playfair Display', serif;
        }

        header h1 {
            font-size: 2.8rem; /* Larger, impactful font size */
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #d1a10e; /* Luxurious gold color */
        }

        /* Navigation Bar */
        nav {
            margin: 15px 0;
            text-align: center;
        }

        nav a {
            color: #d1a10e;
            text-decoration: none;
            margin: 0 20px;
            font-size: 1.2rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* Join Type Links */
        .join-links {
            text-align: center;
            margin-bottom: 30px;
        }

        .join-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .join-links a:hover {
            text-decoration: underline;
        }

        /* Table Styles */
        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.8); /* Light background for contrast */
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 1.1rem;
        }

        th {
            background-color: #222;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        tr:hover {
            background-color: #f1c40f;
            cursor: pointer;
        }

        /* Logout Button */
        .logout {
            display: inline-block;
            margin-top: 40px;
            padding: 12px 25px;
            background: #d9534f;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .logout:hover {
            background: #c9302c;
        }
    </style>
</head>
<body>

    <header>
        <h1>Welcome To Luxury Crossbody Bags, <?php echo htmlspecialchars($username); ?></h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="customers.php">Customers</a>
            <a class="logout" href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <!-- Links to change the join type -->
        <div class="join-links">
            <a href="?join_type=inner">INNER JOIN</a> | 
            <a href="?join_type=right">RIGHT JOIN</a> | 
            <a href="?join_type=left">LEFT JOIN</a> | 
            <a href="?join_type=outer">FULL OUTER JOIN</a>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>

</body>
</html>
