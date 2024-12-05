<?php
    session_start();
    include 'database.php';

    if (isset($_POST['email']) && isset($_POST['mdp'])) {
        $email = $_POST['email'];
        $password = $_POST['mdp'];

        $db = new DatabaseConnexion();
        $conn = $db->connect();

        $query = "SELECT mdp, pseudo FROM utilisateurs WHERE mail = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $user['mdp'];

            if (password_verify($password, $hashedPassword)) {
                $_SESSION["utilisateur_connecte"] = $user["pseudo"];
                echo json_encode(["success" => true, "redirect" => "./annonces.php"]); 
            } else {
                echo json_encode(["success" => false, "message" => "Identifiants incorrects."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Identifiant incorrects."]);
        }
    }
?>