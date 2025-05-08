<?php
// Database connection credentials
$host = 'localhost';  // your MySQL host
$db   = 'tshepang';   // your database name
$user = 'root';       // your MySQL username
$pass = 'Maikano@533'; // your MySQL password
$charset = 'utf8mb4';
// DSN string
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
// Initialize variables for form values and error message
$name = $contact = $drink = "";
$message = "";
try {
    // Connect to database with PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo "Database connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Validate and sanitize inputs
    $name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
    $contact = filter_input(INPUT_GET, 'contact', FILTER_SANITIZE_STRING);
    $drink = filter_input(INPUT_GET, 'drink', FILTER_SANITIZE_STRING);
    // Basic validation
    if (!$name || !$contact || !$drink) {
        $message = "Please fill all required fields.";
    } else {
        // Insert into database using prepared statement
        $sql = "INSERT INTO rsvps (name, contact, drink) VALUES (:name, :contact, :drink)";
        $stmt = $pdo->prepare($sql);

      try {
        $stmt->execute(['name' => $name, 'contact' => $contact, 'drink' => $drink]);
        $message = "Your order has been submitted successfully!";
        // Clear form values after successful submission
        $name = $contact = $drink = "";
      } catch (PDOException $e) {
        $message = "Error inserting RSVP: " . htmlspecialchars($e->getMessage());
      }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>RSVP Received</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #6b8dd6, #b087d6);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      padding: 20px;
    }
    .confirmation {
      background: white;
      padding: 30px 35px;
      border-radius: 18px;
      box-shadow: 0 24px 48px rgba(0,0,0,0.2);
      max-width: 380px;
      text-align: center;
      color: #225522;
      font-weight: 700;
      font-size: 1.1rem;
      line-height: 1.5;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      font-weight: 600;
      color: #6b8dd6;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    a:hover {
      color: #4c67c0;
    }
  </style>
</head>
<body>
<div class="confirmation">
  Thank you, <?=htmlspecialchars($name)?>! Your RSVP has been recorded.<br />
  We look forward to celebrating Tshepang's 20 FEST Birthday with you! 🎉<br />
  <a href="index.html">Submit another response</a>
</div>
</body>
</html>

