<?php
include('class_serpent.php');

// Si le formulaire est soumis
if (isset($_POST['subbutton'])) {
    // Récupérer les données du formulaire
    $data = [
        'srp_name' => $_POST['srp_name'],
        'srp_weight' => $_POST['srp_weight'],
        'srp_life_expect' => $_POST['srp_life_expect'],
        'srp_birthdate' => $_POST['srp_birthdate'],
        'srp_race' => $_POST['srp_race'],
        'srp_sexe' => $_POST['srp_sexe'],
        'srp_mom' => empty($_POST['srp_mom']) ? null : $_POST['srp_mom'],  // Si vide, mettre NULL
        'srp_dad' => empty($_POST['srp_dad']) ? null : $_POST['srp_dad'],  // Si vide, mettre NULL
        'srp_alive' => $_POST['srp_alive']
    ];

    // Créer une instance de la classe Database
    $database = new Database();

    // Insérer les données dans la table serpent
    $serpentId = $database->create('serpent', $data);

    // Rediriger vers la page de la liste
    header('Location: liste.php');
    exit(); // Assurez-vous que le script ne continue pas après la redirection
}



// Si l'ID est différent de 'new', on charge les données du serpent
if ($_GET['id'] != 'new') {
    $Objserpent = new Serpent($_GET['id']);
    $onerow = $Objserpent->SelectParams();

    // Si aucune donnée n'est trouvée pour cet ID, on redirige vers la liste
    if (empty($onerow)) {
        echo "Aucun serpent trouvé avec cet ID.";
        exit; // On arrête le script si l'ID est invalide
    }
} else {
    // Si on est sur la page de création, on initialise les valeurs par défaut
    $onerow[0]['srp_name'] = "";
    $onerow[0]['srp_weight'] = "";
    $onerow[0]['srp_life_expect'] = "";
    $onerow[0]['srp_birthdate'] = "";
    $onerow[0]['srp_race'] = "";
    $onerow[0]['srp_sexe'] = "";
    $onerow[0]['srp_mom'] = "";
    $onerow[0]['srp_dad'] = "";
    $onerow[0]['srp_alive'] = 1; // Vivant par défaut
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Serpent</title>
    <!-- Importation de Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1 class="text-center mb-4"><?php echo ($_GET['id'] == 'new') ? 'Ajouter un nouveau serpent' : 'Modifier un serpent'; ?></h1>

    <!-- Formulaire de modification -->
    <form action="" method="POST">
        <input type="hidden" name="idrow" value="<?php echo $_GET['id']; ?>">

        <div class="mb-3">
            <label for="srp_name" class="form-label">Nom</label>
            <input type="text" id="srp_name" name="srp_name" class="form-control" placeholder="Nom du serpent" value="<?php echo htmlspecialchars($onerow[0]['srp_name'] ?? ''); ?>" required>
        </div>

        <div class="mb-3">
            <label for="srp_weight" class="form-label">Poids (kg)</label>
            <input type="text" id="srp_weight" name="srp_weight" class="form-control" placeholder="Poids du serpent" value="<?php echo htmlspecialchars($onerow[0]['srp_weight'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="srp_life_expect" class="form-label">Durée de vie (ans)</label>
            <input type="text" id="srp_life_expect" name="srp_life_expect" class="form-control" placeholder="Durée de vie" value="<?php echo htmlspecialchars($onerow[0]['srp_life_expect'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="srp_birthdate" class="form-label">Date de naissance</label>
            <input type="datetime-local" id="srp_birthdate" name="srp_birthdate" class="form-control" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($onerow[0]['srp_birthdate'])) ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="srp_race" class="form-label">Race</label>
            <input type="text" id="srp_race" name="srp_race" class="form-control" placeholder="Race du serpent" value="<?php echo htmlspecialchars($onerow[0]['srp_race'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="srp_sexe" class="form-label">Genre</label>
            <select id="srp_sexe" name="srp_sexe" class="form-select">
                <option value="Mâle" <?php echo ($onerow[0]['srp_sexe'] == 'Mâle') ? 'selected' : ''; ?>>Mâle</option>
                <option value="Femelle" <?php echo ($onerow[0]['srp_sexe'] == 'Femelle') ? 'selected' : ''; ?>>Femelle</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="srp_mom" class="form-label">ID de la mère</label>
            <input type="number" id="srp_mom" name="srp_mom" class="form-control" placeholder="ID de la mère" value="<?php echo htmlspecialchars($onerow[0]['srp_mom'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="srp_dad" class="form-label">ID du père</label>
            <input type="number" id="srp_dad" name="srp_dad" class="form-control" placeholder="ID du père" value="<?php echo htmlspecialchars($onerow[0]['srp_dad'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="srp_alive" class="form-label">Vivant</label>
            <select id="srp_alive" name="srp_alive" class="form-select">
                <option value="1" <?php echo ($onerow[0]['srp_alive'] == 1) ? 'selected' : ''; ?>>Oui</option>
                <option value="0" <?php echo ($onerow[0]['srp_alive'] == 0) ? 'selected' : ''; ?>>Non</option>
            </select>
        </div>

        <button type="submit" name="subbutton" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<!-- Importation de Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
