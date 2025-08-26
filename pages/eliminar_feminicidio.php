<?php
require_once __DIR__ . '/../db/config.php';

// Función para eliminar un registro de personal
function eliminarPersonal($id) {
    global $conn; // Acceder a la conexión con la base de datos

    try {
        // Preparar y ejecutar la consulta SQL para eliminar el registro de personal
        $query = "DELETE FROM Feminicidios WHERE ID = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Verificar si se eliminó el registro de personal correctamente
        if ($stmt->rowCount() > 0) {
            return true; // Éxito al eliminar el registro de personal
        } else {
            return false; // No se encontró el registro de personal con el ID proporcionado
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false; // Error al intentar eliminar el registro de personal
    }
}

// Verificar si se ha enviado una solicitud para eliminar un registro de personal
if(isset($_GET['eliminar_id'])) {
    $id = $_GET['eliminar_id'];

    // Llamar a la función para eliminar el registro de personal
    if(eliminarPersonal($id)) {
        echo "<script>alert('Registro eliminado con éxito');</script>";
        echo "<script>window.location.href='../pages/ver-feminicidio.php';</script>"; // Redirigir a la página deseada
    } else {
        echo "<script>alert('Error al eliminar el registro de personal');</script>";
    }
}
?>