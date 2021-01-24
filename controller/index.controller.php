<?php
#***************************************************************************************#


				/**
				*
				* @file controller index-page
				* @author Thomas Schleder <t.schleder@web.de>
				* @copyright toka e. K.
				* @lastmodifydate 2020-08-18
				*
				*/
				
			
#***************************************************************************************#

				
				#***********************************#
				#********** CONFIGURATION **********#
				#***********************************#
				
				require_once("include/config.inc.php");
				require_once("include/db.inc.php");
				require_once("include/form.inc.php");
				require_once("include/dateTime.inc.php");


				#********** INCLUDE CLASSES **********#
				require_once("Class/iBlog.class.php");
				require_once("Class/Blog.class.php");
				require_once("Class/iUser.class.php");
				require_once("Class/User.class.php");
				require_once("Class/iCategory.class.php");
				require_once("Class/Category.class.php");
				

#***************************************************************************************#


				#**************************************#
				#********** OUTPUT BUFFERING **********#
				#**************************************#
				
				if(!ob_start()) {
					//Fehlerfall
if(DEBUG)		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Starten des Output Bufferings! <i>(" . basename(__FILE__) . ")</i></p>\r\n";				
					
				} else {
					//Erfolgsfall
if(DEBUG)		echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Output Buffering erfolgreich gestartet. <i>(" . basename(__FILE__) . ")</i></p>\r\n";									
				}


#***************************************************************************************#


				#*********************************#
				#********** SECURE PAGE **********#
				#*********************************#
				
				#********** INITIALIZE SESSION **********#
				session_name("blogProjectOop");
				session_start();
				
				#********** CHECK FOR VALID LOGIN **********#
				if(!isset($_SESSION['usr_id'])) {
					session_destroy();
				}

					
#***************************************************************************************#


				#*****************************************#
				#********** DATABASE CONNECTION **********#
				#*****************************************#
				
				//Schritt 1 DB: DB-Verbindung herstellen
				$pdo = dbConnect();


#***************************************************************************************#


				#***********************************#
				#********** CLASS TESTING **********#
				#***********************************#

				/*
				#********** CATEGORY **********#
				$category1 = new Category();
				$category1->setCat_name("Test");
				
if(DEBUG)	echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)	print_r($category1);					
if(DEBUG)	echo "</pre>";				
				
				//$cat_name=NULL, $cat_id=NULL
				$category2 = new Category("Neue Kategorie", "1");
				
				#********** USER **********#
				$user1 = new User();
				$user1->setUsr_firstname("Thomas");
				$user1->setUsr_lastname("Schleder");
				$user1->setUsr_email("t.schleder@web.de");
				$user1->setUsr_city("Goslar");
				
if(DEBUG)	echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)	print_r($user1);					
if(DEBUG)	echo "</pre>";					
				
				//$usr_firstname=NULL, $usr_lastname=NULL, $usr_email=NULL, $usr_city=NULL, $usr_password=NULL, $usr_id=NULL
				$user2 = new User("Kathrin", "W", "k@w.de", "Goslar", "1234", "1");
				
				#********** BLOG **********#
				$blog1 = new Blog();
				$blog1->setCategory($category2);
				$blog1->setUser($user2);
				$blog1->setblog_headline("Überschrift Blog");
				$blog1->setblog_imageAlignment("links");
				$blog1->setblog_content("Inhalt Blog");
				
if(DEBUG)	echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)	print_r($blog1);					
if(DEBUG)	echo "</pre>";	
				
				//$category=NULL, $user=NULL, $blog_headline=NULL, $blog_imageAlignment=NULL, $blog_content=NULL, $blog_image=NULL, $blog_date=NULL, $blog_id=NULL
				$blog2 = new Blog($category2, $user2, "Neue Überschrift Blog", "rechts", "Neuer Inhalt Blog", "Pfad vom Bild", "2020-08-14", "1");
				*/
								
#***************************************************************************************#


				#******************************************#
				#********** INITIALIZE VARIABLES **********#
				#******************************************#

				$user					= new User();
				
				$loginMessage 		= NULL;
				
				$categoryFilter	= false;


#***************************************************************************************#

			
				#***********************************************#
				#********** URL-PARAMETERVERARBEITUNG **********#
				#***********************************************#
			
				//Schritt 1 URL: Prüfen, ob Parameter übergeben wurde
				if(isset($_GET['action'])) {
if(DEBUG)		echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: URL-Parameter 'action' wurde übergeben... <i>(" . basename(__FILE__) . ")</i></p>\r\n";	
			
					//Schritt 2 URL: Werte auslesen, entschärfen, DEBUG-Ausgabe
					$action = cleanString($_GET['action']);
if(DEBUG)		echo "<p class='debug'><b>Line " . __LINE__ . "</b>: \$action = $action <i>(" . basename(__FILE__) . ")</i></p>\r\n";
		
					//Schritt 3 URL: ggf. Verzweigung
					
					#********** LOGOUT **********#
					if($action == "logout") {
if(DEBUG) 			echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Logout wird durchgeführt... <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						
						//Schritt 4 URL: Daten weiterverarbeiten
						
						#********** DELETE SESSION **********#
						session_destroy();
						
						#********** REDIRECT TO index.php **********#
						//Notwendig, damit beim Logout wieder das Login-Formular angezeigt wird,
						//wenn sich der User auf der index.php abmeldet
						header("Location: index.php");	
					
					#********** FILTER **********#
					} elseif($action == 'filter') {
if(DEBUG) 			echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Kategoriefilterung wird durchgeführt... <i>(" . basename(__FILE__) . ")</i></p>\r\n";						
						
						//Schritt 4 URL: Daten weiterverarbeiten
						//Zusätzliche URL Parameter auslesen, entschärfen, Debug-Ausgabe
						$category = new Category();
						$category->setCat_id($_GET['cat_id']);
						
						$categoryFilter = true;
						
					} //VERZWEIGUNG ENDE
					
				} //URL-PARAMETERVERARBEITUNG ENDE


#***************************************************************************************#


				#******************************************#
				#********** FORMULARVERARBEITUNG **********#
				#******************************************#
/*
if(DEBUG)	echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)	print_r($_POST);					
if(DEBUG)	echo "</pre>";
*/
				#********** LOGIN **********#				
				//Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
				if(isset($_POST['formsentLogin'])) {
if(DEBUG)		echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Formular 'Login' wurde abgeschickt... <i>(" . basename(__FILE__) . ")</i></p>\r\n";	

					//Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
					$user->setUsr_email($_POST['usr_email']);
					$password = cleanString($_POST['loginPassword']);
if(DEBUG)		echo "<p class='debug'><b>Line " . __LINE__ . "</b>: \$password: $password <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					//Schritt 3 FORM: ggf. Werte validieren
					$errorEmail 			= checkEmail($user->getUsr_email());
					$errorLoginPassword 	= checkInputString($password, 4);
					
					#********** FINAL FORM VALIDATION **********#					
					if($errorEmail || $errorLoginPassword) {
						//Fehlerfall
if(DEBUG)			echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Formular enthält noch Fehler! <i>(" . basename(__FILE__) . ")</i></p>\r\n";						
						$loginMessage = "Benutzername oder Passwort falsch!";
						
					} else {
						//Erfolgsfall
if(DEBUG)			echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Formular ist formal fehlerfrei und wird nun verarbeitet... <i>(" . basename(__FILE__) . ")</i></p>\r\n";						
									
						//Schritt 4 FORM: Daten weiterverarbeiten
if(DEBUG) 			echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Logindaten werden validiert... <i>(" . basename(__FILE__) . ")</i></p>\r\n";


						#****************************************#
						#********** DATABASE OPERATION **********#
						#****************************************#
						
						#********** 1. VERIFY EMAIL **********#						
						if(!$user->fetchFromDb($pdo)) {
							//Fehlerfall:
if(DEBUG)				echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: Email '" . $user->getUsr_email() . "' existiert nicht in der DB! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							$loginMessage = "Benutzername oder Passwort falsch!";
						
						} else {
							// Erfolgsfall
if(DEBUG)				echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Email '" . $user->getUsr_email() . "' wurde in der DB gefunden. <i>(" . basename(__FILE__) . ")</i></p>\r\n";	
						
if(DEBUG)				echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)				print_r($user);					
if(DEBUG)				echo "</pre>";

							#********** 2. VERIFY PASSWORD **********#							
							if(!password_verify($password, $user->getUsr_password())) {
								//Fehlerfall
if(DEBUG)					echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: Passwort stimmt nicht mit DB überein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
								$loginMessage = "Benutzername oder Passwort falsch!";
							
							} else {
								//Erfolgsfall
if(DEBUG)					echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Passwort stimmt mit DB überein. LOGIN OK. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG)					echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Userdaten werden in Session geschrieben... <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							
								
								#********** USERDATEN IN SESSION SCHREIBEN **********#
								session_name("blogProjectOop");
								session_start();
								
								$_SESSION['usr_id'] 			= $user->getUsr_id();
								$_SESSION['usr_firstname'] = $user->getUsr_firstname();
								$_SESSION['usr_lastname'] 	= $user->getUsr_lastname();
/*								
if(DEBUG)					echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)					print_r($_SESSION);					
if(DEBUG)					echo "</pre>";
*/								
								#********** REDIRECT TO dashboard.php **********#
								header("Location: dashboard.php");
								exit;
								
							} //2. VERIFY PASSWORD END

						} //1. VERIFY EMAIL END

					} //FINAL FORM VALIDATION END

				} //FORMULARVERARBEITUNG ENDE


#***************************************************************************************#


				#************************************************#
				#********** FETCH BLOG ENTRIES FORM DB **********#
				#************************************************#

				if($categoryFilter) {
if(DEBUG)		echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Blogeinträge werden gefiltert nach Kategorie-ID" . $category->getCat_id() . " aus der DB gelesen... <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					$blogsArray = Blog::fetchAllFromDb($pdo, $category->getCat_id());

				} else {
if(DEBUG)		echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Alle Blogeinträge werden aus der DB gelesen... <i>(" . basename(__FILE__) . ")</i></p>\r\n";
					
					$blogsArray = Blog::fetchAllFromDb($pdo);
					
				}


/*				
if(DEBUG)	echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)	print_r($blogsArray);					
if(DEBUG)	echo "</pre>";
*/
			
#***************************************************************************************#


				#**********************************************#
				#********** FETCH CATEGORIES FROM DB **********#
				#**********************************************#

if(DEBUG)	echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Alle Kategorien werden aus der DB gelesen... <i>(" . basename(__FILE__) . ")</i></p>\r\n";
				$categoriesArray = Category::fetchAllFromDb($pdo);

/*				
if(DEBUG)	echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)	print_r($categoriesArray);					
if(DEBUG)	echo "</pre>";
*/
			
#***************************************************************************************#
?>