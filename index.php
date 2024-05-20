<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="todo.css" />
    <style>
        .task-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .task-container label:hover::after {
            content: "ça doit être trop bien !!!";
            position: absolute;
            background-color: white;
            color: blue;
            padding: 5px;
            border-radius: 5px;
            margin-left: 10px;
        }

        .task-container input[type="checkbox"]:checked~label,
        .task-container input[type="checkbox"]:checked~.task-details {
            text-decoration: line-through;
        }
    </style>
</head>

<body>

    <main>
        <h1>MA TO DO LIST &#128522;</h1>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['Titre'])) {
                // Ajouter une nouvelle tâche
                $newData = [$_POST["Titre"], $_POST["Description"], $_POST["Datefin"], $_POST["Priorite"]];
                $handle = fopen('donnee.csv', 'a');
                fputcsv($handle, $newData);
                fclose($handle);

                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            } elseif (isset($_POST['delete'])) {
                // Supprimer les tâches cochées
                $tasks = [];
                $handle = fopen('donnee.csv', 'r');
                while (($row = fgetcsv($handle)) !== false) {
                    if (!in_array($row[0], $_POST['delete'])) {
                        $tasks[] = $row;
                    }
                }
                fclose($handle);

                $handle = fopen('donnee.csv', 'w');
                foreach ($tasks as $task) {
                    fputcsv($handle, $task);
                }
                fclose($handle);

                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        }

        echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
        $handle = fopen('donnee.csv', 'r');
        while (($row = fgetcsv($handle)) !== false) {
            $prioriteClass = strtolower($row[3]);
            echo '<div class="task-container ' . htmlspecialchars($prioriteClass) . '">';
            echo '<input type="checkbox" id="' . htmlspecialchars($row[0]) . '" name="delete[]" value="' . htmlspecialchars($row[0]) . '">';
            echo '<label for="' . htmlspecialchars($row[0]) . '" class=". $row[3] " title="ça doit être trop bien !!!">' . htmlspecialchars($row[0]) . ' </label>';
            echo '<span class="task-details" style="margin-left: 10px;">' . htmlspecialchars($row[1]) . '</span>';
            echo '<span class="task-details" style="margin-left: 10px;">' . htmlspecialchars($row[2]) . '</span>';
            echo '</div>';
        }
        fclose($handle);
        echo '<br><input type="submit" value="Supprimer">';
        echo '</form> <br>';
        ?>

        <hr>

        <!-- Formulaire pour ajouter des nouvelles tâches -->
        <form method="post">
            <h2>Nouvelle tâche</h2>
            <label for="Titre">Titre :</label>
            <input type="text" name="Titre" required><br><br>
            <label for="Description">Description :</label>
            <textarea name="Description" id="Description" required></textarea><br><br>
            <label for="Datefin">Date de fin :</label>
            <input type="date" name="Datefin" required><br><br>
            <label for="Priorite">Priorité :</label>
            <input type="radio" name="Priorite" value="Basse" required>Basse
            <input type="radio" name="Priorite" value="Normale" required>Normale
            <input type="radio" name="Priorite" value="Haute" required>Haute<br><br>
            <input type="submit" value="Enregistrer">
        </form><br>
    </main>
</body>

</html>