<?php
 require_once '../classes/database.php';

header('Content-Type: application/json'); // Ensure JSON response

if (isset($_POST['genreName'])) {
    $genreName = $_POST['genreName']; 
    $con = new database();

    $db = $con->opencon();
    if (!$db) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    $query = $db->prepare("SELECT genre_name FROM Genres WHERE genre_name = ?");
    $query->execute([$genreName]);
    $existingGenre = $query->fetch();

    if ($existingGenre) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
}

?>
