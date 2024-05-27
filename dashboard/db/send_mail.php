<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

// Set JSON header
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
        echo json_encode(array('success' => false, 'message' => 'Please fill in all the fields.'));
        exit();
    }
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';

    // Initialize success flag
    $success = false;
    $errorMessage = '';

    // PHPMailer configuration
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server host
        $mail->SMTPAuth = true;
        $mail->Username = 'afastsystem@gmail.com'; // Your SMTP username
        $mail->Password = 'gqli inak tbtk sumo'; // Your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Sender and recipient details
        $mail->setFrom($email, 'System Admin'); // Sender's email and name
        $mail->addAddress('rogeranthony1127@gmail.com', 'Roger Anthony D. Bairoy'); // Recipient's email and name

        //Content 
        $mail->isHTML(false);
        $mail->Subject = 'Contact Form Submission';
        $mail->Body = "Name: $name\nEmail: $email\nMessage:\n$message";

        $mail->send();

        // Set success flag to tru
        $success = true;
    } catch (Exception $e) {
        // Set error message
        $errorMessage = $e->getMessage();
    }

    // Prepare response array
    $response = array(
        'success' => $success,
        'message' => $success ? 'Email has been sent successfully to ' . $email : $errorMessage
    );

    // Send response as JSON
    echo json_encode($response);
} else {
    // If accessed directly without POST method
    echo json_encode(array('success' => false, 'message' => 'Method not allowed'));
}
?>