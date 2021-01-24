<?php
#************************************************************************************#	


				#******************************************#
				#********** GLOBAL CONFIGURATION **********#
				#******************************************#
				
				/*
					Konstanten werden in PHP mittels der Funktion define() definiert.
					Konstanten besitzen im Gegensatz zu Variablen kein $-Präfix
					Üblicherweise werden Konstanten komplett GROSS geschrieben.
				*/
				
				
				#********** DATABASE CONFIGURATION **********#
				define("DB_SYSTEM", 						"mysql");
				define("DB_HOST", 						"localhost");
				define("DB_NAME", 						"blog_oop");
				define("DB_USER", 						"root");
				define("DB_PWD", 							"");
				
				
				#********** FORMULAR CONFIGURATION **********#
				define("INPUT_MIN_LENGTH", 			2);
				define("INPUT_MAX_LENGTH", 			256);
				
				
				#********** IMAGE UPLOAD CONFIGURATION **********#
				define("IMAGE_MAX_WIDTH",				800);
				define("IMAGE_MAX_HEIGHT",				800);
				define("IMAGE_MAX_SIZE",				128*1024);
				define("IMAGE_ALLOWED_MIMETYPES",	array("image/jpg", "image/jpeg", "image/gif", "image/png"));
				
				
				#********** STANDARD PATHS CONFIGURATION **********#
				define("IMAGE_UPLOAD_PATH",			"uploads/blogimages/");
				
				
				#********** DEBUGGING **********#
				define("DEBUG", 						false);		// Debugging for main php document
				define("DEBUG_F", 						false);		// Debugging for functions
				define("DEBUG_DB", 						false);		// Debugging for DB-Functions
				define("DEBUG_C", 						false);		// Debugging for classes
				define("DEBUG_CC", 						false);		// Debugging for class constructors


#************************************************************************************#	
?>
