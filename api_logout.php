<?php
/**
 * API: Logout
 */
session_start();

header('Content-Type: application/json');

// Clear all cam session variables
unset($_SESSION['cam_user_id']);
unset($_SESSION['cam_user_name']);
unset($_SESSION['cam_user_email']);
unset($_SESSION['cam_face_name']);
unset($_SESSION['cam_login_time']);

// Destroy session if no other session variables exist
if (empty($_SESSION)) {
    session_destroy();
}

echo json_encode(['success' => true, 'message' => 'Logout realizado com sucesso']);
?>
