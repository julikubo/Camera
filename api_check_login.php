<?php
/**
 * API: Check Login Status
 */
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['cam_user_id'])) {
    echo json_encode([
        'logged_in' => true,
        'user' => [
            'id' => $_SESSION['cam_user_id'],
            'name' => $_SESSION['cam_user_name'],
            'email' => $_SESSION['cam_user_email'],
            'face_name' => $_SESSION['cam_face_name'] ?? null,
            'login_time' => $_SESSION['cam_login_time'] ?? null
        ]
    ]);
} else {
    echo json_encode(['logged_in' => false]);
}
?>
