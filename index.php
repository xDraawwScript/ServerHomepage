<?php

$servername = "localhost";
$username = "exemple";
$password = "but1";
$dbname = "base";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function afficherPersonnes() {
    global $conn;

    $sql = "SELECT * FROM personnes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Liste des personnes :</h2><ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>ID: " . $row["id"] . " - Nom: " . $row["Nom"] . " - Prénom: " . $row["Prenom"] . " - Age: " . $row["Age"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucun résultat trouvé.</p>";
    }
}

// Mettre à jour une personne
function mettreAJourPersonne($id_personne, $nouveau_nom, $nouveau_prenom, $nouvel_age) {
    global $conn;

     // Échapper les valeurs pour éviter les problèmes de sécurité
    $nouveau_nom = mysqli_real_escape_string($conn, $nouveau_nom);
    $nouveau_prenom = mysqli_real_escape_string($conn, $nouveau_prenom);

    // Construire la requête SQL avec des guillemets simples autour des valeurs
    $sql = "UPDATE personnes SET Nom='$nouveau_nom', Prenom='$nouveau_prenom', Age='$nouvel_age' WHERE id=$id_personne";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Personne mise à jour avec succès.</p>";
    } else {
        echo "<p>Erreur lors de la mise à jour : " . $conn->error . "</p>";
    }
}

// Ajouter une personne
function ajouterPersonne($nom, $prenom, $age) {
    global $conn;

    $sql = "INSERT INTO personnes (Nom, Prenom, Age) VALUES ('$nom', '$prenom', '$age')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Personne ajoutée avec succès.</p>";
    } else {
        echo "<p>Erreur lors de l'ajout : " . $conn->error . "</p>";
    }
}
// Supprimer une personne
function supprimerPersonne($id_personne) {
    global $conn;

    $sql = "DELETE FROM personnes WHERE id=$id_personne";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Personne supprimée avec succès.</p>";
    } else {
        echo "<p>Erreur lors de la suppression : " . $conn->error . "</p>";
    }
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier quelle action est soumise dans le formulaire
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
             case 'afficher':
                afficherPersonnes();
                break;
            case 'mettreAJour':
                mettreAJourPersonne($_POST['id_personne'], $_POST['nouveau_nom'], $_POST['nouveau_prenom'], $_POST['nouvel_age']);
                break;
            case 'ajouter':
                ajouterPersonne($_POST['nom'], $_POST['prenom'], $_POST['age']);
                break;
            case 'supprimer':
                supprimerPersonne($_POST['id_personne']);
                break;
            default:
                echo "<p>Action non reconnue.</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemple PHP</title>
	<link rel="stylesheet" href="styles.css">

</head>
<body>
    <h1>Interagir avec la base de données</h1>

    <!-- Ajout du formulaire pour supprimer une personne -->
    <h2>Supprimer une personne</h2>
    <form method="post" action="index.php">
        <label for="id_personne">ID de la personne à supprimer:</label>
    <input type="text" name="id_personne" id="id_personne" required>

        <input type="hidden" name="action" value="supprimer">
        <input type="submit" value="Supprimer Personne">
    </form>

    <!-- Ajout du formulaire pour afficher toutes les personnes -->


    <h2>Afficher toutes les personnes</h2>
    <form method="post" action="index.php">
        <input type="hidden" name="action" value="afficher">
        <input type="submit" value="Afficher Personnes">
    </form>

    <!-- Ajout du formulaire pour mettre à jour une personne -->
    <h2>Mettre à jour une personne</h2>
    <form method="post" action="index.php">
     <label for="id_personne">ID de la personne à mettre à jour:</label>
    <input type="text" name="id_personne" id="id_personne" required>
    <!-- Ajoutez vos champs de formulaire ici -->
    <label for="nouveau_nom">Nouveau Nom:</label>
    <input type="text" name="nouveau_nom" id="nouveau_nom" required>
    <label for="nouveau_prenom">Nouveau Prénom:</label>
    <input type="text" name="nouveau_prenom" id="nouveau_prenom" required>
    <label for="nouvel_age">Nouvel Âge:</label>
    <input type="number" name="nouvel_age" id="nouvel_age" required>
    <!-- Ajoutez un champ caché pour spécifier l'action -->
    <input type="hidden" name="action" value="mettreAJour">
    <!-- Ajoutez le bouton de soumission du formulaire -->
    <input type="submit" value="Mettre à jour Personne">
</form>


    <!-- Ajout du formulaire pour ajouter une personne -->
    <h2>Ajouter une personne</h2>
    <form method="post" action="index.php">
        <!-- Ajoutez vos champs de formulaire pour ajouter ici -->
        <label for="nom">Nom:</label>
        <input type="text" name="nom" id="nom" required>
        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" id="prenom" required>
        <label for="age">Âge:</label>
        <input type="number" name="age" id="age" required>
        <input type="hidden" name="action" value="ajouter">
        <input type="submit" value="Ajouter Personne">
    </form>

</body>
</html>

