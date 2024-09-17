<?php
session_start();

// Connexion à la base de données
$host = ''; // Remplacé par 'votre host phpmyadmin'
$dbname = ''; // Remplacé par 'votre database phpmyadmin'
$username = '';// Remplacé par 'votre nom phpmyadmin'
$password = ''; // Remplacé par 'votre mot de pass phpmyadmin'

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];

    // Vérifier si l'utilisateur existe
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && password_verify($mdp, $utilisateur['mdp'])) {
        // Si le mot de passe est correct, démarre une session
        $_SESSION['utilisateur'] = $utilisateur['email'];
        $_SESSION['pseudo'] = $utilisateur['pseudo'];
        
        header("Location: valider.html"); // Redirection après connexion réussie
        exit();


    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>
