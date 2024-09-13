<?php

// session_start();

// $fullname = $_POST['fullname'];
// $phone = $_POST['phone'];
// $email = $_POST['email'];
// $password = $_POST['password'];
// // $otp = $_POST['otp'];

// // if ($_SESSION['otp'] != $otp || $_SESSION['email'] != $email) {
// //     die('OTP verification failed or email mismatch');
// // }

// // Hash the password
// $hashed_password = password_hash($password, PASSWORD_BCRYPT);

// $con = new mysqli("localhost", "root", "", "user");

// // Check connection
// if ($con->connect_error) {
//     die('Failed to connect: ' . $con->connect_error);
// }

// // Prepare and bind the SQL statement
// $stmt = $con->prepare('INSERT INTO login (FULLNAME, EMAIL, PASSWORD, PHONE, OTP) VALUES (?, ?, ?, ?, "verified")');
// if ($stmt === false) {
//     die('Error preparing statement: ' . $con->error);
// }

// // Bind parameters
// $stmt->bind_param('ssss', $fullname, $email, $hashed_password, $phone);

// // Execute the statement
// if ($stmt->execute()) {
//     header("Location: http:://localhost/project/log.php");
//     exit();
// } else {
//     echo '<h2>Error registering user: ' . $stmt->error . '</h2>';
// }

// // Close statement and connection
// $stmt->close();
// $con->close();






// without OTP
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password'];

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$con = new mysqli("localhost", "root", "", "user");

// Check connection
if ($con->connect_error) {
    die('Failed to connect: ' . $con->connect_error);
}

// Prepare and bind the SQL statement
$stmt = $con->prepare('INSERT INTO login (FULLNAME, EMAIL, PASSWORD, PHONE) VALUES (?, ?, ?, ?)');
if ($stmt === false) {
    die('Error preparing statement: ' . $con->error);
}

// Bind parameters
$stmt->bind_param('ssss', $fullname, $email, $hashed_password, $phone);

// Execute the statement
if ($stmt->execute()) {
    header("Location: log.php");
                                   exit();
} else {
    echo '<h2>Error registering user: ' . $stmt->error . '</h2>';
}

// Close statement and connection
$stmt->close();
$con->close();

?>