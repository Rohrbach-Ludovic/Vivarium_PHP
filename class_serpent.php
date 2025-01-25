<?php

include_once("class_database.php");

class Serpent {

	private $conn = "";
	private $tblname = "serpent";
	private $id = "";
	
	public function __construct($myid){
		$this->conn = new Database();
		
		if($myid == "new"){
			$this->id = $this->conn->create($this->tblname);
		} else {
			$this->id = $myid;
		}
	}
	
	// SelectAll + filtres
	public function SelectAll($filters = [], $sort = null, $order = 'ASC') {
		$db = new Database();
	
		// Base query
		$query = "SELECT * FROM serpent";
		$params = [];
	
		// Ajouter des conditions dynamiques selon les filtres
		$conditions = [];
		if (!empty($filters['filter_gender'])) {
			$conditions[] = "srp_sexe = :gender";
			$params[':gender'] = $filters['filter_gender'];
		}
		
		if (!empty($filters['filter_race'])) {
			$conditions[] = "srp_race LIKE :race";
			$params[':race'] = "%" . $filters['filter_race'] . "%";
		}
	
		// Ajouter les conditions à la requête
		if (!empty($conditions)) {
			$query .= " WHERE " . implode(" AND ", $conditions);
		}
	
		// Ajouter le tri si spécifié
		if ($sort && in_array($sort, ['srp_name', 'srp_weight', 'srp_life_expect', 'srp_birthdate', 'srp_race', 'srp_sexe'])) {
			$query .= " ORDER BY " . $sort . " " . ($order === 'DESC' ? 'DESC' : 'ASC');
		}
	
		// Exécuter la requête
		return $db->dbquery($query, $params);
	}
	
	
	public function SelectParams(){
		return $this->conn->listing($this->tblname, $this->id);
	}
		
	public function SetOne($col, $value) {
		$this->conn->update($this->tblname, $col, $value, $this->id);
	}

	// Méthode pour supprimer un serpent
    public function delete() {
        return $this->conn->delete($this->tblname, $this->id);
    }

	// Compter les serpents par genres
	public function countByGender() {
		// Initialisation de la classe Database
		$db = new Database();
	
		// Requête pour compter les mâles et femelles
		$query = "SELECT srp_sexe, COUNT(*) as count FROM serpent GROUP BY srp_sexe";
	
		// Exécuter la requête
		$result = $db->dbquery($query);
	
		// Initialiser les compteurs
		$counts = [
			'Mâle' => 0,
			'Femelle' => 0,
		];
	
		// Traiter les résultats
		foreach ($result as $row) {
			if ($row['srp_sexe'] === 'Mâle') {
				$counts['Mâle'] = $row['count'];
			} elseif ($row['srp_sexe'] === 'Femelle') {
				$counts['Femelle'] = $row['count'];
			}
		}
	
		return $counts;
	}

	//liste races
	public function getUniqueRaces() {
        $db = new Database();
        $query = "SELECT DISTINCT srp_race FROM serpent WHERE srp_race IS NOT NULL AND srp_race != ''";
        return $db->dbquery($query);
    }

	// génération aléatoire serpents
	public function generateRandomSnakes($count = 10) {
        $db = new Database();
        $races = ['Python', 'Cobra', 'Vipère', 'Anaconda', 'Mamba'];
        $genders = ['Mâle', 'Femelle'];

        for ($i = 0; $i < $count; $i++) {
            $data = [
                'srp_name' => 'Serpent_' . uniqid(),
                'srp_weight' => round(rand(10, 200) / 10, 1),
                'srp_life_expect' => rand(10, 50),
                'srp_birthdate' => date('Y-m-d H:i:s', strtotime('-' . rand(0, 365) . ' days')),
                'srp_race' => $races[array_rand($races)],
                'srp_sexe' => $genders[array_rand($genders)],
                'srp_mom' => null,
                'srp_dad' => null,
                'srp_alive' => rand(0, 1)
            ];
            $db->create('serpent', $data);
        }
    }



	// Logique de reproduction
	public function getPotentialMates($race = null) {
        $db = new Database();
        $query = "SELECT srp_id, srp_name, srp_race, srp_sexe 
                  FROM serpent 
                  WHERE srp_sexe != '' AND srp_alive = 1";
        $params = [];

        if ($race) {
            $query .= " AND srp_race = :race";
            $params[':race'] = $race;
        }

        return $db->dbquery($query, $params);
    }

    public function breed($male_id, $female_id) {
		$db = new Database();
		
		// Fetch parent details
		$male = $this->SelectParams($male_id);
		$female = $this->SelectParams($female_id);
	
		if (empty($male) || empty($female)) {
			return false;
		}
	
		// Nombre aléatoire de petits entre 1 et 5
		$num_offspring = rand(1, 5);
	
		$offspring = [];
		for ($i = 0; $i < $num_offspring; $i++) {
			$data = [
				'srp_name' => 'Offspring_' . uniqid(),
				'srp_weight' => round(($male[0]['srp_weight'] + $female[0]['srp_weight']) / 2, 1),
				'srp_life_expect' => round(($male[0]['srp_life_expect'] + $female[0]['srp_life_expect']) / 2, 1),
				'srp_birthdate' => date('Y-m-d H:i:s'),
				'srp_race' => $male[0]['srp_race'], // Inherit race from parents
				'srp_sexe' => rand(0, 1) ? 'Mâle' : 'Femelle', // Random gender
				'srp_mom' => $female_id,
				'srp_dad' => $male_id,
				'srp_alive' => 1
			];
			
			$offspring_id = $db->create('serpent', $data);
			$offspring[] = $offspring_id;
		}
	
		return $offspring;
	}
	
	
	
}
?>