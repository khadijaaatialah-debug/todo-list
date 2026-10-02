<?php

define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'todolist');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');

$pdo = new PDO(
    "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
    DB_USER,
    DB_PASS
);

// Ajouter
if (isset($_POST['new'])) {
    $titre = $_POST['titre'];

    $sql = "INSERT INTO todo (titre, done) VALUES (?, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titre]);
}

// Supprimer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM todo WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

// Toggle
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];

    $sql = "UPDATE todo SET done = 1 - done WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

// Afficher les tâches
$sql = "SELECT * FROM todo ORDER BY id DESC";
$stmt = $pdo->query($sql);
$taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ma Todo List</title>

    <style>
        body {
            font-family: Arial;
            width: 600px;
            margin: 40px auto;
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            gap: 10px;
        }

        input {
            flex: 1;
            padding: 10px;
        }

        button {
            padding: 10px;
        }

        .tache {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        a {
            margin-left: 15px;
        }
    </style>
</head>

<body>

    <h1>Ma Todo List</h1>

    <form method="POST">
        <input type="text" name="titre" placeholder="Nouvelle tâche" required>
        <button type="submit" name="new">Ajouter</button>
    </form>

    <?php foreach ($taches as $tache): ?>

        <div class="tache">

            <?php echo htmlspecialchars($tache['titre']); ?>

            <?php if ($tache['done'] == 1): ?>
                - Terminée
            <?php else: ?>
                - Non terminée
            <?php endif; ?>

            <a href="?toggle=<?php echo $tache['id']; ?>">
                Toggle
            </a>

            <a href="?delete=<?php echo $tache['id']; ?>">
                Supprimer
            </a>

        </div>

    <?php endforeach; ?>

</body>

</html>