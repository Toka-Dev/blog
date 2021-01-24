<?php
#*******************************************************************************************#


				#************************************#
				#********** CLASS CATEGORY **********#
				#************************************#

				/*
					Die Klasse ist quasi der Bauplan/die Vorlage für alle Objekte, die aus ihr erstellt werden.
					Sie gibt die Eigenschaften/Attribute eines späteren Objekts vor (Variablen) sowie 
					die "Fähigkeiten" (Methoden/Funktionen), über die das spätere Objekt besitzt.

					Jedes Objekt einer Klasse ist nach dem gleichen Schema aufgebaut (gleiche Eigenschaften und Methoden), 
					besitzt aber i.d.R. unterschiedliche Attributswerte.
				*/

				
#*******************************************************************************************#


				/**
				*
				* Class representing a Category
				*
				*/
				class Category implements iCategory {
					
					#*******************************#
					#********** ATTRIBUTE **********#
					#*******************************#
					
					/* 
						Innerhalb der Klassendefinition müssen Attribute nicht zwingend initialisiert werden.
						Ein Weglassen der Initialisierung bewirkt das gleiche, wie eine Initialisierung mit NULL.
					*/
					private $cat_name;
					private $cat_id;

					
					#***********************************************************#
					
					
					#*********************************#
					#********** KONSTRUKTOR **********#
					#*********************************#
					
					/*
						Der Konstruktor ist eine magische Methode und wird automatisch aufgerufen,
						sobald mittels des new-Befehls ein neues Objekt erstellt wird.
						Der Konstruktor erstellt eine neue Klasseninstanz/Objekt.
						Soll ein Objekt beim Erstellen bereits mit Attributwerten versehen werden,
						muss ein eigener Konstruktor geschrieben werden. Dieser nimmt die Werte in 
						Form von Parametern (genau wie bei Funktionen) entgegen und ruft seinerseits 
						die entsprechenden Setter auf, um die Werte zuzuweisen.					
					*/
					
					/**
					*
					* @construct
					* @param [String $cat_name = NULL] Category name
					* @param [String $cat_id	= NULL] Record-Id given by database
					*
					*/
					public function __construct($cat_name=NULL, $cat_id=NULL) {
if(DEBUG_CC)		echo "<h3 class='debugClass'><b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</h3>\r\n";

						if($cat_name)					$this->setCat_name($cat_name);
						if($cat_id)						$this->setCat_id($cat_id);
						
if(DEBUG_CC)		echo "<pre class='debugClass'><b>Line " . __LINE__ .  "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG_CC)		print_r($this);					
if(DEBUG_CC)		echo "</pre>";	
					}
					
					
					
					
					#********** DESTRUCTOR **********#
					/*
						Der Destruktor ist eine eine magische Magische und wird automatisch aufgerufen,
						sobald ein Objekt mittels unset() gelöscht wird, oder sobald das Skript beendet ist.
						Der Destructor gibt den vom gelöschten Objekt belegten Speicherplatz wieder frei.
					*/
					public function __destruct() {
if(DEBUG_CC)		echo "<h3 class='debugClass'><b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</h3>\r\n";						
					}
					
					
					#***********************************************************#

					
					#*************************************#
					#********** GETTER & SETTER **********#
					#*************************************#
				
					#********** CAT_NAME **********#
					public function getCat_name() {
						return $this->cat_name;
					}
					public function setCat_name($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->cat_name = $cleanedValue; 
					}
		
					#********** CAT_ID **********#
					public function getCat_id() {
						return $this->cat_id;
					}
					public function setCat_id($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->cat_id = $cleanedValue; 
					}
					
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#

					/**
					*
					* Checks if cat_name already exists in Database
					*
					* @param PDO $pdo DB-connection object
					*
					* @return Int Number of matching DB entries
					*
					*/
					public function checkIfExists(PDO $pdo) {
if(DEBUG_C)			echo "<h3 class='debugClass'><b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>\r\n";

						$sql		= "SELECT COUNT(cat_name) FROM category
										WHERE cat_name = ?";
						
						$params	= array($this->getCat_name());
						
						//Schritt 2 DB: SQL-Statement vorbereiten
						$statement = $pdo->prepare($sql);
						
						//Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
						$statement->execute($params);
if(DEBUG_C)			if($statement->errorInfo()[2]) echo "<p class='debugClass err'><b>Line " . __LINE__ .  "</b>: " . $statement->errorInfo()[2] . " (<i>" . basename(__FILE__) . "</i>)</p>\r\n";
						
						//Schritt 4 DB: Daten weiterverarbeiten
						$anzahl = $statement->fetchColumn();
if(DEBUG_C)			echo "<p class='debugClass'><b>Line " . __LINE__ .  "</b>: \$anzahl: $anzahl (<i>" . basename(__FILE__) . "</i>)</p>\r\n";
						
						return $anzahl;
						
					}
					
					/**
					*
					* Fetches category objects data from DB
					*
					* @param PDO $pdo DB-connection object
					*
					* @return Array Array with category objects
					*
					*/
					public static function fetchAllFromDb(PDO $pdo) {
if(DEBUG_C)			echo "<h3 class='debugClass'><b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>\r\n";

						$categoriesArray = array();

						$sql 		= "SELECT * FROM category";
							
						$params 	= NULL;
						
						//Schritt 2 DB: SQL-Statement vorbereiten
						$statement = $pdo->prepare($sql);
						
						//Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
						$statement->execute($params);
if(DEBUG_C)			if($statement->errorInfo()[2]) echo "<p class='debugClass err'><b>Line " . __LINE__ .  "</b>: " . $statement->errorInfo()[2] . " (<i>" . basename(__FILE__) . "</i>)</p>\r\n";

						//Schritt 4 DB: Daten weiterverarbeiten
						//Bei SELECT: Datensätze abholen
						while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
							//Je Schleifendurchlauf ein Objekt aus dem Array $row erstellen
							//und das Objekt in ein Array schreiben
							
if(DEBUG_C)				echo "<p class='debugClass'><b>Line " . __LINE__ . "</b>: Category-Objekt wird erstellt... <i>(" . basename(__FILE__) . ")</i></p>\r\n";						
							//$cat_name=NULL, $cat_id=NULL
							$category = new Category($row['cat_name'], $row['cat_id']);			
							
							$categoriesArray[] = $category;
						}
						//Array mit Category-Objekten zurückgeben
						return $categoriesArray;
						
					}
					
					/**
					*
					* Saves new dataset of category object attributes data into DB
					* Writes the DB insert ID into calling category object
					*
					* @param PDO $pdo DB-connection object
					*
					* @return Boolean true if writing was successful, else false
					*
					*/
					public function saveToDb(PDO $pdo) {
if(DEBUG_C)			echo "<h3 class='debugClass'><b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>\r\n";

						$sql		= "INSERT INTO category
										(cat_name)
										VALUES
										(?)";
						
						$params	= array($this->getCat_name());
						
						//Schritt 2 DB: SQL-Statement vorbereiten
						$statement = $pdo->prepare($sql);
						
						//Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
						$statement->execute($params);
if(DEBUG_C)			if($statement->errorInfo()[2]) echo "<p class='debugClass err'><b>Line " . __LINE__ .  "</b>: " . $statement->errorInfo()[2] . " (<i>" . basename(__FILE__) . "</i>)</p>\r\n";
						
						//Schritt 4 DB: Daten weiterverarbeiten
						//Bei schreibendem Zugriff: Schreiberfolg prüfen
						$rowCount = $statement->rowCount();
if(DEBUG_C)			echo "<p class='debugClass'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						if(!$rowCount) {
							//Fehlerfall
							return false;
							
						} else {
							//Erfolgsfall
							$lastInsertId = $pdo->lastInsertId();
							//ID ins Objekt schreiben
							$this->setCat_id($lastInsertId);
							
							return true;
						}
					}


					#***********************************************************#
					
				}
				
				
#*******************************************************************************************#
?>


















