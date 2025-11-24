<?php
/**
 * API: Facial Recognition Login
 * Authenticates user based on facial recognition
 */
session_start();
require_once 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $face_name = $data['face_name'] ?? null;
    
    if (!$face_name) {
        echo json_encode(['success' => false, 'message' => 'Nome do rosto não fornecido']);
        exit;
    }
    
    // Load face-to-user mapping
    $face_mapping = require 'face_user_mapping.php';
    
    // Check if face is mapped to a user
    if (!isset($face_mapping[$face_name]) || $face_mapping[$face_name] === null) {
        echo json_encode([
            'success' => false, 
            'message' => 'Rosto reconhecido mas não mapeado para nenhum usuário',
            'face_name' => $face_name
        ]);
        exit;
    }
    
    $user_id = $face_mapping[$face_name];
    
    // Get user data from loja database
    $pdo = createCamPDOConnection();
    $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode([
            'success' => false, 
            'message' => 'Usuário não encontrado no banco de dados',
            'user_id' => $user_id
        ]);
        exit;
    }
    
    // Create session
    $_SESSION['cam_user_id'] = $user['id'];
    $_SESSION['cam_user_name'] = $user['name'];
    $_SESSION['cam_user_email'] = $user['email'];
    $_SESSION['cam_face_name'] = $face_name;
    $_SESSION['cam_login_time'] = time();
    
    echo json_encode([
        'success' => true,
        'message' => 'Login realizado com sucesso',
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'face_name' => $face_name
        ]
    ]);
    
} catch (Exception $e) {
    error_log("Facial Login Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>
