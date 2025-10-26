<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $age = intval($_POST['age']);
    $email = $_POST['email'];

    if (empty($nom) || empty($prenom)) {
        echo "<p>Erreur : Nom et Prénom sont requis.</p>";
    } elseif ($age <= 0) {
        echo "<p>Erreur : L'âge doit être positif.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p>Erreur : Email invalide.</p>";
    } else {
        $sql = "INSERT INTO Persons (Nom, Prenom, Age, Email) VALUES ('$nom', '$prenom', $age, '$email')";
        if (mysqli_query($conn, $sql)) {
            echo "<p>Étudiant ajouté avec succès !</p>";
        } else {
            echo "<p>Erreur lors de l'ajout : " . mysqli_error($conn) . "</p>";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        echo "<p>Erreur : Aucun email fourni pour la suppression.</p>";
    } else {
        $email = $_POST['email'];
        $sql = "DELETE FROM Persons WHERE Email='$email'";
        if (mysqli_query($conn, $sql)) {
            echo "<p>Supprimé avec succès</p>";
        } else {
            echo "<p>Erreur de suppression : " . mysqli_error($conn) . "</p>";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    if (!isset($_POST['original_email']) || empty($_POST['original_email'])) {
        echo "<p>Erreur : Aucun email original fourni.</p>";
    } else {
        $original_email = $_POST['original_email'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $age = intval($_POST['age']);
        $email = $_POST['email'];

        if (empty($nom) || empty($prenom)) {
            echo "<p>Erreur : Nom et Prénom sont requis.</p>";
        } elseif ($age <= 0) {
            echo "<p>Erreur : L'âge doit être positif.</p>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p>Erreur : Email invalide.</p>";
        } else {
            $sql = "UPDATE Persons SET Nom='$nom', Prenom='$prenom', Age=$age, Email='$email' WHERE Email='$original_email'";
            if (mysqli_query($conn, $sql)) {
                echo "<p>Mise à jour réussie !</p>";
            } else {
                echo "<p>Erreur de mise à jour : " . mysqli_error($conn) . "</p>";
            }
        }
    }
}

$sql = "SELECT Nom, Prenom, Age, Email FROM Persons";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "<p>Erreur lors de la récupération des données : " . mysqli_error($conn) . "</p>";
    exit;
}

$edit_person = null;
if (isset($_GET['edit_email'])) {
    $edit_email = $_GET['edit_email'];
    $sql = "SELECT Nom, Prenom, Age, Email FROM Persons WHERE Email='$edit_email'";
    $edit_result = mysqli_query($conn, $sql);
    if ($edit_result) {
        $edit_person = mysqli_fetch_assoc($edit_result);
    } else {
        echo "<p>Erreur lors de la récupération : " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            margin: 40px;
        }

        h2, h3 {
            text-align: center;
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .edit-form, .add-form {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .edit-form label, .add-form label {
            display: block;
            margin: 10px 0 5px;
        }

        .edit-form input, .add-form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .edit-form input[type="submit"], .add-form input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        .edit-form input[type="submit"]:hover, .add-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .add-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .add-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h2>Liste des étudiants</h2>

    <a href="?add=true" class="add-button">Ajouter un étudiant</a>

    <?php if (isset($_GET['add']) && $_GET['add']): ?>
        <div class="add-form">
            <h3>Ajouter un nouvel étudiant</h3>
            <form action="" method="post">
                <label>Nom:</label>
                <input type="text" name="nom" required>
                <label>Prénom:</label>
                <input type="text" name="prenom" required>
                <label>Âge:</label>
                <input type="number" name="age" required>
                <label>Email:</label>
                <input type="email" name="email" required>
                <input type="submit" name="add" value="Ajouter">
            </form>
        </div>
    <?php endif; ?>

    <?php if ($edit_person): ?>
        <div class="edit-form">
            <h3>Modifier l'étudiant</h3>
            <form action="" method="post">
                <input type="hidden" name="original_email" value="<?php echo htmlspecialchars($edit_person['Email']); ?>">
                <label>Nom:</label>
                <input type="text" name="nom" value="<?php echo htmlspecialchars($edit_person['Nom']); ?>" required>
                <label>Prénom:</label>
                <input type="text" name="prenom" value="<?php echo htmlspecialchars($edit_person['Prenom']); ?>" required>
                <label>Âge:</label>
                <input type="number" name="age" value="<?php echo htmlspecialchars($edit_person['Age']); ?>" required>
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($edit_person['Email']); ?>" required>
                <input type="submit" name="update" value="Mettre à jour">
            </form>
        </div>
    <?php endif; ?>

    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Nom</th><th>Prénom</th><th>Âge</th><th>Email</th><th>Action</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>" . htmlspecialchars($row["Nom"]) . "</td>
                <td>" . htmlspecialchars($row["Prenom"]) . "</td>
                <td>" . htmlspecialchars($row["Age"]) . "</td>
                <td>" . htmlspecialchars($row["Email"]) . "</td>
                <td>
                    <a href='?edit_email=" . $row["Email"] . "'><button>Modifier</button></a>
                    <form action='' method='post' style='display:inline;'>
                        <input type='hidden' name='email' value='" . $row["Email"] . "'>
                        <input type='submit' name='delete' value='Supprimer'>
                    </form>
                </td>
              </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucun étudiant trouvé ou erreur: " . ($result ? '' : mysqli_error($conn)) . "</p>";
    }

    mysqli_close($conn);
    ?>
</body>
</html>