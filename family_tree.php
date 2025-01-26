<?php
include_once("class_serpent.php");

// Ensure an ID is provided
if (!isset($_GET['id'])) {
    die("Aucun identifiant de serpent fourni.");
}

$Objserpent = new Serpent($_GET['id']);
$family = $Objserpent->getFamilyTree();

if (!$family) {
    die("Impossible de trouver les informations familiales.");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Arbre Généalogique du Serpent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Arbre Généalogique de <?php echo htmlspecialchars($family['current']['srp_name']); ?></h1>

        <div class="row">
            <div class="col-md-12">
                <h2>Serpent Actuel</h2>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($family['current']['srp_name']); ?></h5>
                        <p>Race: <?php echo htmlspecialchars($family['current']['srp_race']); ?></p>
                        <p>Genre: <?php echo htmlspecialchars($family['current']['srp_sexe']); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <h2>Parents</h2>
                <?php if (!empty($family['parents'])): ?>
                    <?php foreach ($family['parents'] as $type => $parent): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo ucfirst($type); ?></h5>
                                <p>Nom: <?php echo htmlspecialchars($parent[0]['srp_name']); ?></p>
                                <p>Race: <?php echo htmlspecialchars($parent[0]['srp_race']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun parent connu</p>
                <?php endif; ?>
            </div>

            <div class="col-md-12">
                <h2>Grands-parents</h2>
                <div class="row">
                    <div class="col-md-6">
                        <h3>Côté Maternel</h3>
                        <?php if (!empty($family['grandparents']['maternal'])): ?>
                            <?php foreach ($family['grandparents']['maternal'] as $type => $grandparent): ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo ucfirst($type); ?></h5>
                                        <p>Nom: <?php echo htmlspecialchars($grandparent[0]['srp_name']); ?></p>
                                        <p>Race: <?php echo htmlspecialchars($grandparent[0]['srp_race']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun grand-parent maternel connu</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h3>Côté Paternel</h3>
                        <?php if (!empty($family['grandparents']['paternal'])): ?>
                            <?php foreach ($family['grandparents']['paternal'] as $type => $grandparent): ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo ucfirst($type); ?></h5>
                                        <p>Nom: <?php echo htmlspecialchars($grandparent[0]['srp_name']); ?></p>
                                        <p>Race: <?php echo htmlspecialchars($grandparent[0]['srp_race']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun grand-parent paternel connu</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <h2>Descendants</h2>
                <?php if (!empty($family['descendants'])): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Race</th>
                                <th>Genre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($family['descendants'] as $descendant): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($descendant['srp_name']); ?></td>
                                    <td><?php echo htmlspecialchars($descendant['srp_race']); ?></td>
                                    <td><?php echo htmlspecialchars($descendant['srp_sexe']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucun descendant</p>
                <?php endif; ?>
            </div>
        </div>

        <a href="liste.php" class="btn btn-primary">Retour à la liste</a>
    </div>
</body>

</html>