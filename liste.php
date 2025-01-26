<?php

include_once("class_serpent.php");
include_once("class_database.php");

// Vérifier si un serpent doit être supprimé
if (isset($_GET['delete_id'])) {
    $serpent_id = $_GET['delete_id'];
    $serpent = new Serpent($serpent_id);

    if ($serpent->delete()) {
        header('Location: liste.php');
        exit();
    } else {
        echo "Erreur lors de la suppression du serpent.";
    }
}

//génération serpents aléatoires
if (isset($_GET['generate_random'])) {
    $Objserpent = new Serpent("vide");
    $Objserpent->generateRandomSnakes(10);

    header('Location: liste.php');
    exit();
}


// Pagination
$items_per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 10;
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$Objserpent = new Serpent("vide");

// Récupération du nombre total de serpents
$filter_gender = isset($_GET['filter_gender']) ? $_GET['filter_gender'] : '';
$filter_race = isset($_GET['filter_race']) ? $_GET['filter_race'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$filters = [
    'filter_gender' => $filter_gender,
    'filter_race' => $filter_race
];

$all_serpents = $Objserpent->SelectAll($filters, $sort, $order);
$total_serpents = count($all_serpents);
$total_pages = ceil($total_serpents / $items_per_page);

// Récupération des serpents pour la page actuelle
$offset = ($current_page - 1) * $items_per_page;
$Objserpent = new Serpent("vide");
$tblallserpent = array_slice($all_serpents, $offset, $items_per_page);

// liste races
$unique_races = $Objserpent->getUniqueRaces();

// Récupérer le nombre de mâles et femelles
$counts = $Objserpent->countByGender();

// Fonction helper pour générer les liens de tri
function getSortLink($column, $currentSort, $currentOrder, $filter_gender, $filter_race, $items_per_page, $current_page)
{
    $newOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
    $params = [
        'sort' => $column,
        'order' => $newOrder,
        'per_page' => $items_per_page,
        'page' => $current_page
    ];
    if ($filter_gender !== '') $params['filter_gender'] = $filter_gender;
    if ($filter_race !== '') $params['filter_race'] = $filter_race;
    return '?' . http_build_query($params);
}

// Reproduction
if (isset($_GET['breed']) && isset($_GET['male']) && isset($_GET['female'])) {
    $Objserpent = new Serpent("vide");

    // Randomly generate number of offspring between 1 and 5
    $offspring_count = rand(1, 5);

    // Call breed method with male and female IDs
    $breeding_result = $Objserpent->breed($_GET['male'], $_GET['female']);

    // Redirect with the number of offspring as a parameter
    header('Location: liste.php?breeding_success=1&offspring_count=' . count($breeding_result));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Serpents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h1 class="text-center mb-4">Liste des Serpents</h1>

        <div class="alert alert-info">
            <strong>Statistiques des serpents :</strong>
            <ul>
                <li><strong>Mâles :</strong> <?= $counts['Mâle']; ?></li>
                <li><strong>Femelles :</strong> <?= $counts['Femelle']; ?></li>
            </ul>
        </div>

        <div class="table-responsive">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="filter_gender" class="form-label">Genre</label>
                    <select name="filter_gender" id="filter_gender" class="form-select">
                        <option value="" <?= $filter_gender === '' ? 'selected' : '' ?>>Tous</option>
                        <option value="Mâle" <?= $filter_gender === 'Mâle' ? 'selected' : '' ?>>Mâle</option>
                        <option value="Femelle" <?= $filter_gender === 'Femelle' ? 'selected' : '' ?>>Femelle</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter_race" class="form-label">Race</label>
                    <select name="filter_race" id="filter_race" class="form-select">
                        <option value="" <?= $filter_race === '' ? 'selected' : '' ?>>Toutes</option>
                        <?php foreach ($unique_races as $race): ?>
                            <option value="<?= htmlspecialchars($race['srp_race']) ?>"
                                <?= $filter_race === $race['srp_race'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($race['srp_race']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="per_page" class="form-label">Serpents/page</label>
                    <select name="per_page" id="per_page" class="form-select">
                        <?php foreach ([5, 10, 20, 50] as $num): ?>
                            <option value="<?= $num ?>" <?= $items_per_page == $num ? 'selected' : '' ?>><?= $num ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($sort): ?>
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                <?php endif; ?>
                <?php if ($order): ?>
                    <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
                <?php endif; ?>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-primary me-2">Filtrer</button>
                    <a href="liste.php" class="btn btn-secondary me-2">Réinitialiser</a>

                </div>
            </form>

            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Actions</th>
                        <th scope="col">
                            <a href="<?= getSortLink('srp_name', $sort, $order, $filter_gender, $filter_race, $items_per_page, $current_page) ?>" class="text-white text-decoration-none">
                                Nom <?= ($sort === 'srp_name') ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="<?= getSortLink('srp_weight', $sort, $order, $filter_gender, $filter_race, $items_per_page, $current_page) ?>" class="text-white text-decoration-none">
                                Poids (kg) <?= ($sort === 'srp_weight') ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="<?= getSortLink('srp_life_expect', $sort, $order, $filter_gender, $filter_race, $items_per_page, $current_page) ?>" class="text-white text-decoration-none">
                                Durée de vie (ans) <?= ($sort === 'srp_life_expect') ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="<?= getSortLink('srp_birthdate', $sort, $order, $filter_gender, $filter_race, $items_per_page, $current_page) ?>" class="text-white text-decoration-none">
                                Date et heure de naissance <?= ($sort === 'srp_birthdate') ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="<?= getSortLink('srp_race', $sort, $order, $filter_gender, $filter_race, $items_per_page, $current_page) ?>" class="text-white text-decoration-none">
                                Race <?= ($sort === 'srp_race') ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="<?= getSortLink('srp_sexe', $sort, $order, $filter_gender, $filter_race, $items_per_page, $current_page) ?>" class="text-white text-decoration-none">
                                Genre <?= ($sort === 'srp_sexe') ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th scope="col">Mère</th>
                        <th scope="col">Père</th>
                        <th scope="col">Vivant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($tblallserpent)) {
                        foreach ($tblallserpent as $row) {
                            echo "<tr>
                            <td>
                                <a href='change.php?id=" . $row['srp_id'] . "' class='btn btn-sm btn-warning'>Modifier</a>
                                <a href='family_tree.php?id=" . $row['srp_id'] . "' class='btn btn-sm btn-info'>Arbre Généalogique</a>
                                <a href='?delete_id=" . $row['srp_id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce serpent ?\")'>Supprimer</a>
                            </td>
                            <td>" . $row['srp_name'] . "</td>
                            <td>" . $row['srp_weight'] . "</td>
                            <td>" . $row['srp_life_expect'] . "</td>
                            <td>" . $row['srp_birthdate'] . "</td>
                            <td>" . $row['srp_race'] . "</td>
                            <td>" . $row['srp_sexe'] . "</td>
                            <td>" . $row['srp_mom'] . "</td>
                            <td>" . $row['srp_dad'] . "</td>
                            <td>" . ($row['srp_alive'] ? "Oui" : "Non") . "</td>
                        </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10' class='text-center'>Aucun serpent trouvé.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <!-- Reproduction -->
            <div class="container mt-4">
                <h3>Accouplement de Serpents</h3>
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="race_filter" class="form-label">Race</label>
                        <select id="race_filter" class="form-select" onchange="updateMates()">
                            <option value="">Sélectionner une race</option>
                            <?php
                            $races = $Objserpent->getUniqueRaces();
                            foreach ($races as $race): ?>
                                <option value="<?= htmlspecialchars($race['srp_race']) ?>">
                                    <?= htmlspecialchars($race['srp_race']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="male_select" class="form-label">Mâle</label>
                        <select id="male_select" name="male" class="form-select" required>
                            <option value="">Choisir un mâle</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="female_select" class="form-label">Femelle</label>
                        <select id="female_select" name="female" class="form-select" required>
                            <option value="">Choisir une femelle</option>
                        </select>
                    </div>

                    <div class="col-md-1 align-self-end">
                        <input type="hidden" name="breed" value="1">
                        <button type="submit" class="btn btn-success">Accoupler</button>
                    </div>
                </form>
            </div>
            <div class="mb-3">
                <h3 class="mb-3">Ajouter des Serpents</h3>
                <a href="change.php?id=new" class="btn btn-primary">Ajouter un nouveau serpent</a>
                <a href="?generate_random=1" class="btn btn-success">Générer 10 serpents</a>
            </div>
            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    for ($page = 1; $page <= $total_pages; $page++) {
                        $params = [
                            'page' => $page,
                            'per_page' => $items_per_page
                        ];
                        if ($filter_gender !== '') $params['filter_gender'] = $filter_gender;
                        if ($filter_race !== '') $params['filter_race'] = $filter_race;
                        if ($sort !== '') $params['sort'] = $sort;
                        if ($order !== '') $params['order'] = $order;

                        $link = '?' . http_build_query($params);
                        $active = $page == $current_page ? 'active' : '';
                        echo "<li class='page-item $active'><a class='page-link' href='$link'>$page</a></li>";
                    }
                    ?>
                </ul>
            </nav>


        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
    <script>
        function updateMates() {
            const raceSelect = document.getElementById('race_filter');
            const maleSelect = document.getElementById('male_select');
            const femaleSelect = document.getElementById('female_select');

            // Reset selects
            maleSelect.innerHTML = '<option value="">Choisir un mâle</option>';
            femaleSelect.innerHTML = '<option value="">Choisir une femelle</option>';

            if (!raceSelect.value) return;

            // Fetch mates via AJAX (you'll need to create an endpoint)
            fetch(`get_mates.php?race=${raceSelect.value}`)
                .then(response => response.json())
                .then(mates => {
                    const males = mates.filter(mate => mate.srp_sexe === 'Mâle');
                    const females = mates.filter(mate => mate.srp_sexe === 'Femelle');

                    males.forEach(male => {
                        const option = new Option(male.srp_name, male.srp_id);
                        maleSelect.add(option);
                    });

                    females.forEach(female => {
                        const option = new Option(female.srp_name, female.srp_id);
                        femaleSelect.add(option);
                    });
                });
        }
    </script>
</body>

</html>