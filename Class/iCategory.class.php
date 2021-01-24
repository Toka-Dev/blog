<?php
#*******************************************************************************************#


				#*****************************************#
				#********** INTERFACE iCATEGORY **********#
				#*****************************************#

				/*
					So wie eine Klasse quasi eine Blaupause für alle später aus ihr zu erstellenden Objekte/Instanzen
					darstellt, kann man ein Interface quasi als eine Blaupause für eine später zu erstellende Klasse
					ansehen.	Hierzu wird ein Interface definiert, das später in die entsprechene Klasse implementiert 
					wird. Der Sinn des Interfaces besteht darin, dass innerhalb des Interfaces sämtliche später 
					innerhalb der Klasse zu erstellende Methoden bereits vordeklariert werden.
					Die Klasse muss dann zwingend sämtliche im Interface deklarierten Methoden enthalten.
					
					Ein Interface darf keinerlei Attribute beinhalten.
					Die im Interface definierten Methoden müssen public sein und dürfen über keinen 
					Methodenrumpf {...} verfügen.
					An die Methode zu übergebende Parameter müssen im Interface vordefiniert sein ($value).
				*/

				
#*******************************************************************************************#


				interface iCategory {
								
					/*
						Ein Interface darf keinerlei Attribute beinhalten.
					*/


					#***********************************************************#
					
									
					#*********************************#
					#********** KONSTRUKTOR **********#
					#*********************************#
					
					public function __construct($cat_name=NULL, $cat_id=NULL);
					
					
					#***********************************************************#

					
					#*************************************#
					#********** GETTER & SETTER **********#
					#*************************************#
					
					#********** CAT_NAME **********#
					public function getCat_name();
					public function setCat_name($value);
		
					#********** CAT_ID **********#
					public function getCat_id();
					public function setCat_id($value);
		
					
					#***********************************************************#
					

					#******************************#
					#********** METHODEN **********#
					#******************************#
					
					public function checkIfExists(PDO $pdo);
					
					public static function fetchAllFromDb(PDO $pdo);
					
					public function saveToDb(PDO $pdo);
					
					
					#***********************************************************#
					
				}
				
				
#*******************************************************************************************#
?>