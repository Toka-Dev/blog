<?php
#************************************************************************************#

				
				/**
				*
				*	Entschärft und säubert einen String, falls er einen Wert besitzt
				*
				*	@param String $value 		- Der zu entschärfende und zu bereinigende String
				*
				*	@return String/NULL 			- der entschärfte und bereinigte String/ bei Leerstring NULL
				*
				*/
				function cleanString( $value ) {
if(DEBUG_F) echo "<p class='debug" . ucfirst(__FUNCTION__) . "'><b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$value') <i>(" . basename(__FILE__) . ")</i></p>\n";
					
					// htmlspecialchars() entschärft HTML-Steuerzeichen wie < > & ""
					// und ersetzt sie durch &lt;, &gt;, &amp;, &quot;
					// ENT_QUOTES | ENT_HTML5 ersetzt zusätzlich '' durch &apos;
					// Der letzte Parameter steuert, ob bereits vorhandene HTML-Entities noch einmal
					// entschärft werden sollen (nein=false)
					$value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, "UTF-8", false);
					
					// trim() entfernt am Anfang und am Ende eines Strings alle 
					// sog. Whitespaces (Leerzeichen, Tabulatoren, Zeilenumbrüche)
					$value = trim($value);
					
					// Um später in der DB keine NULL-Werte mit Leerstrings zu überschreiben, 
					// werden hier Leerstrings zentral in echte NULL-Werte umgewandelt
					if( $value === "" ) {
						$value = NULL;
					}
					
					return $value;					
					
				}


#************************************************************************************#


				/**
				*
				*	Prüft einen String auf Leerstring, Mindest- und Maxmimallänge
				*
				*	@param String $value 									- Der zu prüfende String
				*	@param [Integer $minLength=INPUT_MIN_LENGTH] 	- Die erforderliche Mindestlänge
				*	@param [Integer $maxLength=INPUT_MAX_LENGTH] 	- Die erlaubte Maximallänge
				*
				*	@return String/NULL - Ein String bei Fehler, ansonsten NULL
				*	
				*/
				function checkInputString($value, $minLength=INPUT_MIN_LENGTH, $maxLength=INPUT_MAX_LENGTH) {
if(DEBUG_F) 	echo "<p class='debug" . ucfirst(__FUNCTION__) . "'><b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$value' [$minLength | $maxLength]) <i>(" . basename(__FILE__) . ")</i></p>\n";					
					
					$errorMessage = NULL;
					
					// Prüfen auf leeres Feld
					/*
						WICHTIG: Die Prüfung auf Leerfeld muss zwingend den Datentyp Sting mitprüfen,
						da ansonsten bei einer Eingabe 0 (z.B. Anzahl der im Haushalt lebenden Kinder: 0)
						die 0 als false und somit als leeres Feld gewertet wird!
					*/
					if( $value === "" OR $value === NULL ) {
						$errorMessage = "Dies ist ein Pflichtfeld!";
					
					// Prüfen auf Mindestlänge
					} elseif( mb_strlen($value) < $minLength ) {
						$errorMessage = "Muss mindestens $minLength Zeichen lang sein!";
						
					// Prüfen auf Maximallänge	
					} elseif( mb_strlen($value) > $maxLength ) {
						$errorMessage = "Darf maximal $maxLength Zeichen lang sein!";						
					}
					return $errorMessage;
				}


#************************************************************************************#
				

				/**
				*
				*	Prüft eine Email-Adresse auf Leerstring und Validität
				*
				*	@param String $value - Die zu prüfende Email-Adresse
				*
				*	@return String/NULL - Ein String bei Fehler, ansonsten NULL
				*
				*/
				function checkEmail($value) {
if(DEBUG_F) 	echo "<p class='debug" . ucfirst(__FUNCTION__) . "'><b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$value') <i>(" . basename(__FILE__) . ")</i></p>\n";					
					
					$errorMessage = NULL;
					
					// Prüfen auf leeres Feld
					/*
						WICHTIG: Die Prüfung auf Leerfeld muss zwingend den Datentyp Sting mitprüfen,
						da ansonsten bei einer Eingabe 0 (z.B. Anzahl der im Haushalt lebenden Kinder: 0)
						die 0 als false und somit als leeres Feld gewertet wird!
					*/
					if( $value === "" OR $value === NULL ) {
						$errorMessage = "Dies ist ein Pflichtfeld!";
					
					// Email auf Validität prüfen
					} elseif( !filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
						$errorMessage = "Dies ist keine gültige Email-Adresse!";
					}
					return $errorMessage;
				}


#************************************************************************************#

				
				
				function imageUpload( 
											$uploadedImage,
											$maxWidth 			= IMAGE_MAX_WIDTH,
											$maxHeight 			= IMAGE_MAX_HEIGHT,
											$maxSize 			= IMAGE_MAX_SIZE,
											$allowedMimeTypes = IMAGE_ALLOWED_MIMETYPES,
											$uploadPath 		= IMAGE_UPLOAD_PATH
											) {
if(DEBUG_F) 	echo "<p class='debug" . ucfirst(__FUNCTION__) . "'><b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "() <i>(" . basename(__FILE__) . ")</i></p>\n";	

					/*
						Das Array $_FILES['avatar'] bzw. $uploadedImage enthält:
						Den Dateinamen [name]
						Den generierten (also ungeprüften) MIME-Type [type]
						Den temporären Pfad auf dem Server [tmp_name]
						Die Dateigröße in Bytes [size]
					*/
					
					#********** BILDINFORMATIONEN SAMMELN **********#
					
					// Dateiname
					$fileName = cleanString($uploadedImage['name']);
					// ggf. vorhandene Leerzeichen durch _ ersetzen
					$fileName = str_replace(" ", "_", $fileName);
					// Dateinamen in Kleinbuchstaben umwandeln
					$fileName = mb_strtolower($fileName);
					// Umlaute ersetzen
					$fileName = str_replace( array("ä","ö","ü","ß"), array("ae","oe","ue","ss"), $fileName );
					// Nicht erlaubte Zeichen löschen
					$fileName = str_replace( array("'","#","?","!",'"',"@"), "", $fileName );
					
					// zufälligen Dateinamen generieren
					$randomPrefix = rand(1,999999). str_shuffle("abcdefghijklmnopqrstuvwxyz") . time();				
					$fileTarget = $uploadPath . $randomPrefix . "_" . $fileName;
					
					// Dateigröße
					$fileSize = $uploadedImage['size'];
					
					// temporärer Pfad auf dem Server
					$fileTemp = $uploadedImage['tmp_name'];
										
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . ":</b> \$fileName: $fileName <i>(" . basename(__FILE__) . ")</i></p>\r\n";	
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . ":</b> \$fileSize: " . round($fileSize/1024,2) . " kB <i>(" . basename(__FILE__) . ")</i></p>\r\n";	
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . ":</b> \$fileTemp: $fileTemp <i>(" . basename(__FILE__) . ")</i></p>\r\n";	
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . ":</b> \$fileTarget: $fileTarget <i>(" . basename(__FILE__) . ")</i></p>\r\n";	
					
					// genauerer Informationen zum Bild holen
					$imageData = getimagesize($fileTemp);
					
					/*
						Die Funktion getimagesize() liefert bei gültigen Bildern ein Array zurück:
						Die Bildbreite in PX [0]
						Die Bildhöhe in PX [1]
						Einen für die HTML-Ausgabe vorbereiteten String für das IMG-Tag
						(width="480" height="532") [3]
						Die Anzahl der Bits pro Kanal ['bits']
						Die Anzahl der Farbkanäle (somit auch das Farbmodell: RGB=3, CMYK=4) ['channels']
						Den echten(!) MIME-Type ['mime']
					*/
					
					$imageWidth 	= $imageData[0];
					$imageHeight 	= $imageData[1];
					$imageMimeType = $imageData['mime'];
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . ":</b> \$imageWidth: $imageWidth px <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . ":</b> \$imageHeight: $imageHeight px <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line " . __LINE__ . ":</b> \$imageMimeType: $imageMimeType <i>(" . basename(__FILE__) . ")</i></p>\r\n";
					
					
					#********** BILD PRÜFEN **********#
					
					// MIME-Type prüfen
					// Whitelist mit erlaubten Bildtypen
					// $allowedMimeTypes = array("image/jpeg", "image/jpg", "image/gif", "image/png");
					if( !in_array($imageMimeType, $allowedMimeTypes) ) {
						$errorMessage = "Dies ist kein erlaubter Bildtyp!";
					
					// maximal erlaubte Bildhöhe
					} elseif( $imageHeight > $maxHeight ) {
						$errorMessage = "Die Bildhöhe darf maximal $maxHeight Pixel betragen!";
						
					// maximal erlaubte Bildbreite
					} elseif( $imageWidth > $maxWidth ) {
						$errorMessage = "Die Bildbreite darf maximal $maxWidth Pixel betragen!";
						
					// maximal erlaubte Dateigröße
					} elseif( $fileSize > $maxSize ) {
						$errorMessage = "Die Dateigröße darf maximal " . $maxSize/1024 . "kB betragen!";
					
					// wenn es keinen Fehler gab
					} else {
						$errorMessage = NULL;
					}
					
					#********** ABSCHLIESSENDE BILDPRÜFUNG **********#
					if( $errorMessage ) {
						// Fehlerfall
if(DEBUG_F)			echo "<p class='debugImageUpload err'><b>Line " . __LINE__ . ":</b> $errorMessage <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						$fileTarget = NULL;
						
					} else {
						// Erfolgsfall
if(DEBUG_F)			echo "<p class='debugImageUpload ok'><b>Line " . __LINE__ . ":</b> Die Bildprüfung ergab keine Fehler. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						

						#********** BILD SPEICHERN **********#
						if( !@move_uploaded_file($fileTemp, $fileTarget) ) {
							// Fehlerfall
if(DEBUG_F)				echo "<p class='debugImageUpload err'><b>Line " . __LINE__ . ":</b> Fehler beim Speichern des Bildes auf dem Server! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							$errorMessage 	= "Fehler beim Speichern des Bildes! Bitte versuchen Sie es später noch einmal.";
							$fileTarget = NULL;
							
						} else {
							// Erfolgsfall
if(DEBUG_F)				echo "<p class='debugImageUpload ok'><b>Line " . __LINE__ . ":</b> Bild wurde erfolgreich auf dem Server gespeichert. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							
						} // BILD SPEICHERN ENDE

					} // ABSCHLIESSENDE BILDPRÜFUNG ENDE
					
					#********** GGF. FEHLERMELDUNG UND BILDPFAD ZURÜCKGEBEN **********#
					return array("imageError" => $errorMessage, "imagePath" => $fileTarget);

				} // IMAGE UPLOAD ENDE


#************************************************************************************#
?>

















