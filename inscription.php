<?php
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

    // Vérifier si l'utilisateur existe déjà
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "L'adresse e-mail est déjà utilisée.";
    } else {
        // Hash du mot de passe avant de le stocker
        $mdp_hash = password_hash($mdp, PASSWORD_BCRYPT);

        // Insérer l'utilisateur dans la base de données
        $stmt = $conn->prepare("INSERT INTO utilisateurs (email, pseudo, mdp) VALUES (:email, :pseudo, :mdp)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':mdp', $mdp_hash); // Utilise 'mdp' pour correspondre à ta structure
        $stmt->execute();

        echo "Inscription réussie !";
    }
}
?>
