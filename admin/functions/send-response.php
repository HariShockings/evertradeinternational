<?php
include('config.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get raw JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        $required = ['inquiry_id', 'recipient_email', 'subject', 'message'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Sanitize inputs
        $inquiryId = (int)$input['inquiry_id'];
        $recipientEmail = filter_var($input['recipient_email'], FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars($input['subject']);
        $message = htmlspecialchars($input['message']);

        // Validate email
        if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address");
        }

        // Mailjet API request
        $api_url = 'https://api.mailjet.com/v3.1/send';
        $auth = base64_encode(MAILJET_API_KEY . ':' . MAILJET_SECRET_KEY);
        
        $data = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "haritheguy21@gmail.com",
                        'Name' => "EverTrade"
                    ],
                    'To' => [
                        [
                            'Email' => $recipientEmail,
                            'Name' => ''
                        ]
                    ],
                    'Subject' => $subject,
                    'TextPart' => $message
                ]
            ]
        ];

        // Create context
        $options = [
            'http' => [
                'header' => [
                    'Content-Type: application/json',
                    'Authorization: Basic ' . $auth
                ],
                'method' => 'POST',
                'content' => json_encode($data),
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($api_url, false, $context);
        
        // Check response
        if (!$result) {
            throw new Exception("Failed to connect to Mailjet API");
        }

        $response = json_decode($result, true);
        
        if (isset($response['Messages'][0]['Status']) && $response['Messages'][0]['Status'] === 'success') {
            // Update database status
            $stmt = $conn->prepare("UPDATE tbl_inquiry SET status='responded', updated_at=NOW() WHERE id=?");
            $stmt->bind_param("i", $inquiryId);
            $stmt->execute();
            
            echo json_encode(['status' => 'success']);
        } else {
            $error = $response['Messages'][0]['Errors'][0]['ErrorMessage'] ?? 'Unknown error';
            throw new Exception("Mailjet error: $error");
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}