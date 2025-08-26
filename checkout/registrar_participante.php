<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db/config.php';

$response = ['success' => false, 'message' => '', 'id' => '', 'nombreCompleto' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $apellidoPaterno = isset($_POST['apellido_paterno']) ? trim($_POST['apellidopaterno']) : '';
    $apellidoMaterno = isset($_POST['apellido_materno']) ? trim($_POST['apellidomaterno']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;

    if (empty($nombre) || empty($apellidoPaterno) || empty($apellidoMaterno)) {
        $response['message'] = "Los campos Nombre y Apellidos son obligatorios.";
        echo json_encode($response);
        exit;
    }

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $conn->exec("SET NAMES utf8");


        $sql = "INSERT INTO Participante (Nombre, ApellidoPaterno, ApellidoMaterno, Email, Telefono)
                VALUES (:nombre, :apellidoPaterno, :apellidoMaterno, :email, :telefono)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidoPaterno', $apellidoPaterno);
        $stmt->bindParam(':apellidoMaterno', $apellidoMaterno);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);

        if ($stmt->execute()) {
            $id = $conn->lastInsertId();
            $nombreCompleto = $nombre . ' ' . $apellidoPaterno . ' ' . $apellidoMaterno;

            $response['success'] = true;
            $response['id'] = $id;
            $response['nombreCompleto'] = $nombreCompleto;
        } else {
            $response['message'] = "No se pudo registrar el participante.";
        }
    } catch(PDOException $e) {
        $response['message'] = "Error de base de datos: " . $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
