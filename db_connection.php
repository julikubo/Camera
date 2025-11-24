<?php
/**
 * Database connection for CAM system
 * Uses the same database as loja but in READ-ONLY mode
 * NO MODIFICATIONS to loja database or files
 */

function createCamPDOConnection() {
    // Use the same configuration as loja
    $host = '151.106.116.205';
    $dbname = 'u621433639_loja';
    $username = 'u621433639_lojaadmin';
    $password = 'Leandrok2022@@';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        error_log("CAM DB Connection Error: " . $e->getMessage());
        throw $e;
    }
}
?>
