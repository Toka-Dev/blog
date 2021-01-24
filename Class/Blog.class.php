<?php
#*******************************************************************************************#


				#********************************#
				#********** CLASS BLOG **********#
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
				* Class representing a blog including a category object and an user object. 
				*
				*/
				class Blog implements iBlog {
					
					#*******************************#
					#********** ATTRIBUTE **********#
					#*******************************#
					
					/* 
						Innerhalb der Klassendefinition müssen Attribute nicht zwingend initialisiert werden.
						Ein Weglassen der Initialisierung bewirkt das gleiche, wie eine Initialisierung mit NULL.
					*/
					//Category-Object
					private $category;
					//User-Object
					private $user;
					private $blog_headline;
					private $blog_imageAlignment;
					private $blog_content;
					private $blog_image;
					private $blog_date;
					private $blog_id;					

					
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
					* @param [Category $category				= NULL] Category object owned by blog object
					* @param [User $user							= NULL] User object owned by blog object
					* @param [String $blog_headline			= NULL] Blog headline
					* @param [String $blog_imageAlignment	= NULL] Blog imageAlignment
					* @param [String $blog_content			= NULL] Blog content
					* @param [String $blog_image				= NULL] Path for the blog image
					* @param [String $blog_date				= NULL] Creating date for the blog entry given by database
					* @param [String $blog_id					= NULL] Record-Id given by database
					*
					*/
					public function __construct($category=NULL, $user=NULL, $blog_headline=NULL, $blog_imageAlignment=NULL, $blog_content=NULL, $blog_image=NULL, $blog_date=NULL, $blog_id=NULL) {
if(DEBUG_CC)		echo "<h3 class='debugClass'><b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</h3>\r\n";

						if($category)					$this->setCategory($category);
						if($user)						$this->setUser($user);
						if($blog_headline)			$this->setBlog_headline($blog_headline);
						if($blog_imageAlignment)	$this->setBlog_imageAlignment($blog_imageAlignment);
						if($blog_content)				$this->setBlog_content($blog_content);
						if($blog_image)				$this->setBlog_image($blog_image);
						if($blog_date)					$this->setBlog_date($blog_date);
						if($blog_id)					$this->setBlog_id($blog_id);
						
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
				
					#********** CATEGORY **********#
					public function getCategory() {
						return $this->category;
					}
					public function setCategory($value) {
						if(!$value instanceof Category) {
							//Fehlerfall
if(DEBUG_C) 			echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: " . __METHOD__ . "(): Must be an object of Type Category! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						} else {
							//Erfolgsfall
							$this->category = $value;
						}
					}
				
					#********** USER **********#
					public function getUser() {
						return $this->user;
					}
					public function setUser($value) {
						if(!$value instanceof User) {
							//Fehlerfall
if(DEBUG_C) 			echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: " . __METHOD__ . "(): Must be an object of Type User! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						} else {
							//Erfolgsfall
							$this->user = $value;
						}
					}
					
					#********** BLOG_HEADLINE **********#
					public function getBlog_headline() {
						return $this->blog_headline;
					}
					public function setBlog_headline($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->blog_headline = $cleanedValue; 
					}
					
					#********** BLOG_IMAGEALIGNMENT **********#
					public function getBlog_imageAlignment() {
						return $this->blog_imageAlignment;
					}
					public function setBlog_imageAlignment($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->blog_imageAlignment = $cleanedValue; 
					}
					
					#********** BLOG_CONTENT **********#
					public function getBlog_content() {
						return $this->blog_content;
					}
					public function setBlog_content($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->blog_content = $cleanedValue; 
					}
					
					#********** BLOG_IMAGE **********#
					public function getBlog_image() {
						return $this->blog_image;
					}
					public function setBlog_image($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->blog_image = $cleanedValue; 
					}
					
					#********** BLOG_DATE **********#
					public function getBlog_date() {
						return $this->blog_date;
					}
					public function setBlog_date($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->blog_date = $cleanedValue; 
					}
		
					#********** BLOG_ID **********#
					public function getBlog_id() {
						return $this->blog_id;
					}
					public function setBlog_id($value) {
						//Entschärfen
						$cleanedValue = cleanString($value);
						$this->blog_id = $cleanedValue; 
					}
					
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#

					/**
					*
					* Fetches blog objects data and related objects data from DB
					* Writes all objects data into corresponding objects
					*
					* @param PDO $pdo DB-connection object
					* @param [String $cat_id = NULL] Category id to filter the blog entries
					*
					* @return Array Array with blog objects
					*
					*/
					public static function fetchAllFromDb(PDO $pdo, $cat_id=NULL) {
if(DEBUG_C)			echo "<h3 class='debugClass'><b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>\r\n";

						$blogsArray = array();

						if($cat_id) {
							$sql 		= "SELECT * FROM blog
											INNER JOIN category USING(cat_id)
											INNER JOIN user USING(usr_id)
											WHERE cat_id = ?
											ORDER BY blog_date DESC";
							
							$params 	= array($cat_id);
						
						} else {
							$sql 		= "SELECT * FROM blog
											INNER JOIN category USING(cat_id)
											INNER JOIN user USING(usr_id)
											ORDER BY blog_date DESC";
							
							$params 	= NULL;
							
						}
						
						//Schritt 2 DB: SQL-Statement vorbereiten
						$statement = $pdo->prepare($sql);
						
						//Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
						$statement->execute($params);
if(DEBUG_C)			if($statement->errorInfo()[2]) echo "<p class='debugClass err'><b>Line " . __LINE__ .  "</b>: " . $statement->errorInfo()[2] . " (<i>" . basename(__FILE__) . "</i>)</p>\r\n";

						//Schritt 4 DB: Daten weiterverarbeiten
						//Bei SELECT: Datensätze abholen
						while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
							// Je Schleifendurchlauf ein Objekt aus dem Array $row erstellen
							// und das Objekt in ein Array schreiben
							
if(DEBUG_C)				echo "<p class='debugClass'><b>Line " . __LINE__ . "</b>: Blog-Objekt wird erstellt... <i>(" . basename(__FILE__) . ")</i></p>\r\n";						
							//Category-Objekt
							//$cat_name=NULL, $cat_id=NULL
							$category = new Category($row['cat_name'], $row['cat_id']);
							
							//User-Objekt
							//$usr_firstname=NULL, $usr_lastname=NULL, $usr_email=NULL, $usr_city=NULL, $usr_password=NULL, $usr_id=NULL
							$user = new User($row['usr_firstname'], $row['usr_lastname'], $row['usr_email'], $row['usr_city'], NULL, $row['usr_id']);
							
							//Blog-Objekt
							//$category=NULL, $user=NULL, $blog_headline=NULL, $blog_imageAlignment=NULL, $blog_content=NULL, $blog_image=NULL, $blog_date=NULL, $blog_id=NULL
							$blog = new Blog($category, $user, $row['blog_headline'], $row['blog_imageAlignment'], $row['blog_content'], $row['blog_image'], $row['blog_date'], $row['blog_id']);			
							
							$blogsArray[] = $blog;
						}
						//Array mit Blog-Objekten zurückgeben
						return $blogsArray;
						
					}
					
					/**
					*
					* Saves new dataset of blog object attributes data into DB
					* Writes the DB insert ID into calling blog object
					*
					* @param PDO $pdo DB-connection object
					*
					* @return Boolean true if writing was successful, else false
					*
					*/
					public function saveToDb(PDO $pdo) {
if(DEBUG_C)			echo "<h3 class='debugClass'><b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>\r\n";

						$sql		= "INSERT INTO blog
										(blog_headline, blog_image, blog_imageAlignment, blog_content, cat_id, usr_id)
										VALUES
										(?, ?, ?, ?, ?, ?)";
						
						$params	= array($this->getBlog_headline(), $this->getBlog_image(), $this->getBlog_imageAlignment(), $this->getBlog_content(), $this->getCategory()->getCat_id(), $this->getUser()->getUsr_id());
						
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
							$this->setBlog_id($lastInsertId);
							
							return true;
						}
					
					}

					
					#***********************************************************#
					
				}
				
				
#*******************************************************************************************#
?>


















