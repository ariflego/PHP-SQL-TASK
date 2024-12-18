<?php
// Database connection function
function getDatabaseConnection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "UserData";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Validate input data
function validateInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = validateInput($_POST['name']);
    $email = validateInput($_POST['email']);
    $age = intval($_POST['age']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    $connectionStatus = "";
    $connectionClass = "";

    try {
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("INSERT INTO users (name, email, age) VALUES (:name, :email, :age)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':age', $age);
        $stmt->execute();

        $connectionStatus = "Database connection successful!";
        $connectionClass = "alert-success";
    } catch (PDOException $e) {
        // If connection fails

        $connectionStatus = "Database connection failed: " . $e->getMessage();
        $connectionClass = "alert-danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Status</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .status-container {
            max-width: 600px;
            text-align: center;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .status-container h1 {
            font-size: 28px;
            font-weight: bold;
        }
        .alert {
            margin-top: 20px;
        }
        .btn-home {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-home:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="status-container">
        <h1>Database Connection</h1>
        <div class="alert <?php echo $connectionClass; ?>">
            <?php echo $connectionStatus; ?>
        </div>
        <a href="index.html" class="btn-home">Go Back to Home</a>
    </div>
</body>
</html>
