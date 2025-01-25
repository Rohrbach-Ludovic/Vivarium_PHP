<?php

class Database {
	
	private $host = "localhost";
	private $port = "";
	private $basename = "vivarium_php";
	private $user = "root";
	private $password = "";
	
	private $conn = "";
	
	public function __construct(){
		/*connection à la BDD*/
		$this->conn = new PDO("mysql:host=".$this->host.";port=".$this->port.";dbname=".$this->basename.";charset=utf8", $this->user, $this->password);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	}

    
	
	public function dbquery($req, $params = []) {
        try {
            $stmt = $this->conn->prepare($req);
            $stmt->execute($params);
            if (strpos(strtoupper($req), 'SELECT') === 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif (strpos(strtoupper($req), 'INSERT') === 0) {
                return $this->conn->lastInsertId();
            }
            return true;
        } catch (PDOException $e) {
            return false;  // Handle the error as needed
        }
    }
    
	
	public function create($table, $data) {
        // Créer une requête d'insertion avec des placeholders pour chaque valeur
        $req = "INSERT INTO `".$table."` (`srp_name`, `srp_weight`, `srp_life_expect`, `srp_birthdate`, `srp_race`, `srp_sexe`, `srp_mom`, `srp_dad`, `srp_alive`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Exécuter la requête avec les données passées en paramètre
        $stmt = $this->conn->prepare($req);
        
        // On passe les données du formulaire à la requête
        $stmt->execute([
            $data['srp_name'], 
            $data['srp_weight'], 
            $data['srp_life_expect'], 
            $data['srp_birthdate'], 
            $data['srp_race'], 
            $data['srp_sexe'], 
            $data['srp_mom'], 
            $data['srp_dad'], 
            $data['srp_alive']
        ]);
    
        // Retourner l'ID du serpent inséré
        return $this->conn->lastInsertId(); 
    }
    
    
    
	
	public function update($table, $col, $value, $id) {
        $sql = "UPDATE `".$table."` SET `".$col."` = :value WHERE `srp_id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
	
	public function listing($table, $id = 'noid'){
        try {
            $req = "SELECT * FROM `".$table."`";
            if ($id != 'noid') {
                $req .= " WHERE `srp_id` = :id"; // Utilisation de paramètres pour éviter les injections SQL
            }
            
            // Prépare et exécute la requête
            $stmt = $this->conn->prepare($req);
            
            // Si un ID est fourni, on lie le paramètre
            if ($id != 'noid') {
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            
            // Si la requête réussit, on récupère toutes les lignes
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur SQL: " . $e->getMessage();
            return false; // En cas d'erreur, on retourne false
        }
    }


    // Méthode pour supprimer une entrée d'une table
    public function delete($table, $id) {
        $sql = "DELETE FROM `".$table."` WHERE `srp_id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    

}
?>