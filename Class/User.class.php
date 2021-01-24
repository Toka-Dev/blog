<?php
#*******************************************************************************************#


				#********************************#
				#********** CLASS USER **********#
				#********************************#

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
				* Class representing an User
				*
				*/
				class User implements iUser {
					
					#*******************************#
					#********** ATTRIBUTE **********#
					#*******************************#
					
					/* 
						Innerhalb der Klassendefinition müssen Attribute nicht zwingend initialisiert werden.
						Ein Weglassen der Initialisierung bewirkt das gleiche, wie eine Initialisierung mit NULL.
					*/
					private $usr_firstname;
					private $usr_lastname;
					private $usr_email;
					private $usr_city;
					private $usr_password;
					private $usr_id;					

					
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
					* @param [String usr_firstname	= NULL] User firstname
					* @param [String usr_lastname		= NULL] User lastname
					* @param [String usr_email			= NULL] User email
					* @param [String usr_city			= NULL] User city
					* @param [String usr_password		= NULL] User password
					* @param [String usr_id				= NULL] Record-Id given by database
					*
					*/
					public function __construct($usr_firstname=NULL, $usr_lastname=NULL, $usr_email=NULL, $usr_city=NULL, $usr_password=NULL, $usr_id=NULL) {
if(DEBUG_CC)		echo "<h3 class='debugClass'><b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</h3>\r\n";

						if($usr_firstname)	$this->setUsr_firstname($usr_firstname);
						if($usr_lastname)		$this->setUsr_lastname($usr_lastname);
						if($usr_email)			$this->setUsr_email($usr_email);
						if($usr_city)			$this->setUsr_city($usr_city);
						if($usr_password)		$this->setUsr_password($usr_password);
						if($usr_id)				$this->setUsr_id($usr_id);
						
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
				
					#********** USR_FIRSTNAME **********#
					public function getUsr_firstname() {
						return $this->usr_firstname;
					}
					public function setUsr_firstname($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->usr_firstname = $cleanedValue; 
					}
				
					#********** USR_LASTNAME **********#
					public function getUsr_lastname() {
						return $this->usr_lastname;
					}
					public function setUsr_lastname($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->usr_lastname = $cleanedValue; 
					}
					
					#********** USR_EMAIL **********#
					public function getUsr_email() {
						return $this->usr_email;
					}
					public function setUsr_email($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->usr_email = $cleanedValue; 
					}
					
					#********** USR_CITY **********#
					public function getUsr_city() {
						return $this->usr_city;
					}
					public function setUsr_city($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->usr_city = $cleanedValue; 
					}
					
					#********** USR_PASSWORD **********#
					public function getUsr_password() {
						return $this->usr_password;
					}
					public function setUsr_password($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->usr_password = $cleanedValue; 
					}
		
					#********** USR_ID **********#
					public function getUsr_id() {
						return $this->usr_id;
					}
					public function setUsr_id($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->usr_id = $cleanedValue; 
					}
					
					/**
					*
					* Get the fullname of the user
					*
					* @return String Firstname and lastname of the user
					*
					*/
					#********** VIRTUAL ATTRIBUTES **********#
					public function getFullname() {
						return $this->getUsr_firstname() . " " . $this->getUsr_lastname();
					}
					
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#

					/**
					*
					* Fetches user object data from DB
					* Writes all objects data into user object
					*
					* @param PDO $pdo DB-connection object
					*
					* @return Boolean true if dataset was found, else false
					*
					*/
					public function fetchFromDb(PDO $pdo) {
if(DEBUG_C)			echo "<h3 class='debugClass'><b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>\r\n";						
					
						$sql		= "SELECT * FROM user
										WHERE usr_email = ?";
						
						$params	= array($this->getUsr_email());
						
						//Schritt 2 DB: SQL-Statement vorbereiten
						$statement = $pdo->prepare($sql);
						
						//Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
						$statement->execute($params);
if(DEBUG_C)			if($statement->errorInfo()[2]) echo "<p class='debugClass err'><b>Line " . __LINE__ .  "</b>: " . $statement->errorInfo()[2] . " (<i>" . basename(__FILE__) . "</i>)</p>\r\n";
						
						//Schritt 4 DB: Daten weiterverarbeiten
						if(!$row = $statement->fetch(PDO::FETCH_ASSOC)) {
							//Fehlerfall
							return false;
							
						} else {
							//Erfolgsfall
							
							#********** WRITE DATA INTO USER OBJECT **********#
							
							$this->setUsr_id($row['usr_id']);
							$this->setUsr_firstname($row['usr_firstname']);
							$this->setUsr_lastname($row['usr_lastname']);
							$this->setUsr_city($row['usr_city']);
							$this->setUsr_password($row['usr_password']);
							
							return true;
						}
					
					}
					
					
					#***********************************************************#
					
				}
				
				
#*******************************************************************************************#
?>


















