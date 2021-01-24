<?php
#***************************************************************************************#


				/**
				*
				* @file controller dashboard-page
				* @author Thomas Schleder <t.schleder@web.de>
				* @copyright toka e. K.
				* @lastmodifydate 2020-08-19
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
					// Session löschen
					session_destroy();
					// Umleiten auf index.php
					header("Location: index.php");
					exit();
					
				}
			
			
#***************************************************************************************#	


				#*****************************************#
				#********** DATABASE CONNECTION **********#
				#*****************************************#
				
				//Schritt 1 DB: DB-Verbindung herstellen
				$pdo = dbConnect();


#***************************************************************************************#

			
				#******************************************#
				#********** INITIALIZE VARIABLES **********#
				#******************************************#

				//$usr_firstname=NULL, $usr_lastname=NULL, $usr_email=NULL, $usr_city=NULL, $usr_password=NULL, $usr_id=NULL
				$activeUser				= new User($_SESSION['usr_firstname'], $_SESSION['usr_lastname'], NULL, NULL, NULL, $_SESSION['usr_id']);
				$blog 					= new Blog(new Category(), $activeUser);
				
				$newCategory			= new Category();
				
				$blogSuccessMessage	= NULL;
				$catSuccessMessage	= NULL;
				
				$blogErrorMessage		= NULL;
				$errorCategoryId		= NULL;
				$errorHeadline 		= NULL;
				$errorImageUpload 	= NULL;
				$errorImageAlignment	= NULL;
				$errorContent			= NULL;
				
				$isImageUploaded		= false;
				
				$catErrorMessage		= NULL;
				$errorCatName			= NULL;
				

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
						header("Location: index.php");
						exit;	
					
					} //VERZWEIGUNG ENDE
					
				} //URL-PARAMETERVERARBEITUNG ENDE
				
				
#***************************************************************************************#			

	
				#*******************************************************#
				#********** FORMULARVERARBEITUNG NEW CATEGORY **********#
				#*******************************************************#
/*				
if(DEBUG)	echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)	print_r($_POST);					
if(DEBUG)	echo "</pre>";	
*/			
				//Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
				if(isset($_POST['formsentNewCategory'])) {
if(DEBUG)		echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Formular 'New Category' wurde abgeschickt... <i>(" . basename(__FILE__) . ")</i></p>\r\n";	
		
					//Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
					$newCategory->setCat_name($_POST['cat_name']);
					
if(DEBUG)		echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)		print_r($newCategory);					
if(DEBUG)		echo "</pre>";

					//Schritt 3 FORM: Werte validieren
if(DEBUG)		echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Felder werden validiert... <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					$errorCatName = checkInputString($newCategory->getCat_name());
					
					#********** FINAL FORM VALIDATION **********#
					if($errorCatName) {
if(DEBUG)			echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Formular enthält noch Fehler! <i>(" . basename(__FILE__) . ")</i></p>\r\n";						
						
					} else {
if(DEBUG)			echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Formular ist formal fehlerfrei und wird nun verarbeitet... <i>(" . basename(__FILE__) . ")</i></p>\r\n";						
						
						//Schritt 4 FORM: Daten weiterverarbeiten
if(DEBUG) 			echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Kategoriename wird validiert... <i>(" . basename(__FILE__) . ")</i></p>\r\n";


						#****************************************#
						#********** DATABASE OPERATION **********#
						#****************************************#
						
						#********** CHECK IF CATEGORY EXISTS IN DB **********#
						if($newCategory->checkIfExists($pdo)) {
							//Fehlerfall:
if(DEBUG)				echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Kategorie <b>'" . $newCategory->getCat_name() . "'</b> existiert bereits! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							$catErrorMessage = "Es existiert bereits eine Kategorie mit diesem Namen!";
						
						} else {
							//Erfolgsfall
if(DEBUG)				echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Neue Kategorie <b>'" . $newCategory->getCat_name() . "'</b> wird gespeichert... <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							
							#********** SAVE NEW CATEGORY IN DB **********#
							if(!$newCategory->saveToDb($pdo)) {
								//Fehlerfall:
if(DEBUG)					echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Speichern der neuen Kategorie! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
								$catErrorMessage = "Es ist ein Fehler aufgetreten! Bitte versuchen Sie es später noch einmal.";
						
							} else {
								//Erfolgsfall
if(DEBUG)					echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Kategorie <b>'" . $newCategory->getCat_name() . "'</b> wurde erfolgreich unter der ID " . $newCategory->getCat_id() . " in der DB gespeichert. <i>(" . basename(__FILE__) . ")</i></p>\r\n";								
								$catSuccessMessage = "Die neue Kategorie mit dem Namen <b>'" . $newCategory->getCat_name() . "'</b> wurde erfolgreich gespeichert.";
									
								// Felder aus Formular wieder leeren
								$newCategory = new Category(); 
						
							} //SAVE NEW CATEGORY IN DB END
							
						} //CHECK IF CATEGORY EXISTS IN DB END
						
					} //FINAL FORM VALIDATION END
					
				} //FORMULARVERARBEITUNG NEW CATEGORY ENDE
				
				
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


				#*********************************************************#
				#********** FORMULARVERARBEITUNG NEW BLOG ENTRY **********#
				#*********************************************************#
/*					
if(DEBUG)	echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)	print_r($_POST);					
if(DEBUG)	echo "</pre>";
*/
				//Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
				if(isset($_POST['formsentNewBlogEntry'])) {
if(DEBUG)		echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Formular 'formsentNewBlogEntry' wurde abgeschickt. <i>(" . basename(__FILE__) . ")</i></p>\r\n";					
					
					//Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
					$blog->getCategory()->setCat_id($_POST['cat_id']);
					$blog->setBlog_headline($_POST['blog_headline']);
					$blog->setBlog_imageAlignment($_POST['blog_imageAlignment']);
					$blog->setBlog_content($_POST['blog_content']);
			
					//Schritt 3 FORM: Werte validieren
if(DEBUG)		echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Felder werden validiert... <i>(" . basename(__FILE__) . ")</i></p>\r\n";
	
					$errorHeadline 		= checkInputString($blog->getBlog_headline());
					$errorContent 			= checkInputString($blog->getBlog_content(), 2, 2000);
					
					#********** CHECK IF CATEGORY IS VALID **********#
if(DEBUG) 		echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Sonderprüfung für Kategorie... <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					$errorCategoryId 		= checkInputString($blog->getCategory()->getCat_id(), 1);

					$isCategoryValid = false;
					foreach($categoriesArray AS $category) {
						if($blog->getCategory()->getCat_id() == $category->getCat_id()) {
							$isCategoryValid = true;
						}
					}
					
					if(!$isCategoryValid) {
						//Fehlerfall
if(DEBUG)			echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Kategorie mit der ID '" . $blog->getCategory()->getCat_id() . "' existiert nicht in der DB! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						$errorCategoryId = "Die Kategorie existiert nicht in der DB!";
							
					} else {
						//Erfolgsfall
if(DEBUG)			echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Kategorie mit der ID '" . $blog->getCategory()->getCat_id() . "' existiert in der DB! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
					
					} //CHECK IF CATEGORY IS VALID END
					
					#********** VERIFY IMAGE ALIGNMENT **********#
if(DEBUG) 		echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Sonderprüfung für Bildausrichtung... <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					$errorImageAlignment	= checkInputString($blog->getBlog_imageAlignment(), 5, 6);
						
					//Prüfen, ob die Eingabe manipuliert wurde
					//Bei der Prüfung mit checkInputString tritt z. B. beim Wert "links" kein Fehler auf
					//Das darf nicht sein. Es muss zum Abbruch kommen
					if(!in_array($blog->getBlog_imageAlignment(), array("fleft", "fright"))) {
						//Fehlerfall
if(DEBUG)			echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Bildausrichtung ungültig! <i>(" . basename(__FILE__) . ")</i></p>\r\n";	
						$errorImageAlignment = "Bildausrichtung ungültig!";
							
					} else {
						//Erfolgsfall
if(DEBUG)			echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Bildausrichtung ist gültig <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					} //VERIFY IMAGE ALIGNMENT END
					
					#********** FINAL FORM VALIDATION **********#
					if($errorCategoryId || $errorHeadline || $errorContent || $errorImageAlignment) {
						//Fehlerfall
if(DEBUG)			echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Das Formular enthält noch Fehler! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						
					} else {
						//Erfolgsfall
if(DEBUG)			echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Das Formular ist formal fehlerfrei und wird nun verarbeitet... <i>(" . basename(__FILE__) . ")</i></p>\r\n";
													
						//Schritt 4 FORM: Daten weiterverarbeiten	
/*					
if(DEBUG)			echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)			print_r($_FILES);					
if(DEBUG)			echo "</pre>\r\n";
*/
						#********** IMAGE UPLOAD **********#
						//1. Prüfen, ob ein Bild hochgeladen wurde
						if($_FILES['blog_image']['tmp_name']) {
if(DEBUG)				echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Bildupload ist aktiv... <i>(" . basename(__FILE__) . ")</i></p>\r\n";

							$imageUploadReturnArray = imageUpload($_FILES['blog_image']);
						
							#********** CHECK ERROR IMAGE UPLOAD **********#
							if($imageUploadReturnArray['imageError']) {
								//Fehlerfall
if(DEBUG)					echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: $imageUploadReturnArray[imageError] <i>(" . basename(__FILE__) . ")</i></p>\r\n";
								$errorImageUpload = $imageUploadReturnArray['imageError'];
											
							} else {
								//Erfolgsfall
								
								//Neuen Bildpfad ins Objekt speichern
								$blog->setBlog_image($imageUploadReturnArray['imagePath']);
								
if(DEBUG)					echo	"<p class='debug ok'><b>Line " . __LINE__ . "</b>: Bild wurde erfolgreich unter<br>
										'<i>" . $blog->getBlog_image() ."</i>'<br>
										auf dem Server gespeichert. <i>(" . basename(__FILE__) . ")</i></p>\r\n";	
										
								//Flag für ein hochgeladenes Bild setzen
								$isImageUploaded = true;
									
							} //CHECK ERROR IMAGE UPLOAD END

						} //IMAGE UPLOAD END
								
						#********** FINAL IMAGE UPLOAD VALIDATION **********#
						if($errorImageUpload) {
							//Fehlerfall
if(DEBUG)				echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Fehler beim image upload. Neuer Blogeintrag wird nicht in DB gespeichert! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						
						} else {
							//Erfolgsfall
if(DEBUG)				echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Das Formular ist fehlerfrei.<i>(" . basename(__FILE__) . ")</i></p>\r\n";

if(DEBUG)				echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG)				print_r($blog);					
if(DEBUG)				echo "</pre>";


							#**********************************#
							#********** DB OPERATION **********#
							#**********************************#
							
							#********** SAVE NEW BLOG ENTRY IN DB **********#
if(DEBUG)				echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Neuer Blogeintrag wird in DB gespeichert... <i>(" . basename(__FILE__) . ")</i></p>\r\n";

							if(!$blog->saveToDb($pdo)) {
								//Fehlerfall:
if(DEBUG)					echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Speichern des neuen Blogeintrages! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
								$blogErrorMessage = "Es ist ein Fehler aufgetreten! Bitte versuchen Sie es später noch einmal.";
								
								#********** DELETE IMAGE FROM SERVER **********#
								if($isImageUploaded) {
if(DEBUG) 						echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Das Bild wird vom Server gelöscht... <i>(" . basename(__FILE__) . ")</i></p>\r\n";										
								
									if(!@unlink($blog->getBlog_image())) {
										//Fehlerfall
if(DEBUG)							echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Löschen des Bildes für den Blogeintrag<br>
												'<i>" . $blog->getBlog_image() ."</i>'! <i>(" . basename(__FILE__) . ")</i></p>\r\n";									
									} else {
										//Erfolgsfall
if(DEBUG)							echo	"<p class='debug ok'><b>Line " . __LINE__ . "</b>: Bild für den Blogeintrag erfolgreich unter<br>
												'<i>" . $blog->getBlog_image() ."</i>' gelöscht. <i>(" . basename(__FILE__) . ")</i></p>\r\n";									
									} 
								} //DELETE IMAGE FROM SERVER END
						
							} else {
								//Erfolgsfall
if(DEBUG)					echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Neuen Blogeintrag erfolgreich unter ID" . $blog->getBlog_id() . " gespeichert. <i>(" . basename(__FILE__) . ")</i></p>\r\n";								
								$blogSuccessMessage = "Neuen Blogeintrag erfolgreich gespeichert.";
									
								// Felder aus Formular wieder leeren
								$blog = new Blog(new Category(), $activeUser); 
						
							} //SAVE NEW BLOG ENTRY IN DB END

						} //FINAL IMAGE UPLOAD VALIDATION END

					} //FINAL FORM VALIDATION END

				} //FORMULARVERARBEITUNG NEW BLOG ENTRY END			
				
				
#***************************************************************************************#	
?>