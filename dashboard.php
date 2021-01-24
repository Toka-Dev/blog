<?php
#***************************************************************************************#


				/**
				*
				* @file view dashboard-page
				* @author Thomas Schleder <t.schleder@web.de>
				* @copyright toka e. K.
				* @lastmodifydate 2020-08-19
				*
				*/
				
	
#***************************************************************************************#
			
			
				#***********************************#
				#********** CONFIGURATION **********#
				#***********************************#
				
				require_once("controller/dashboard.controller.php");

			
#***************************************************************************************#

?>

<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>Mein IT-Blog</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/debug.css">
	</head>

	<body>

		<!--------------------------------- HEADER START ----------------------------------------->
	
		<header class="fright">
			<p>
				<a href="?action=logout">Logout</a><br>
				<a href="index.php"><< zum Frontend</a>
			</p>
		</header>
		<div class="clearer"></div>

		<br>
		<hr>
		<br>
		
		<!--------------------------------- HEADER END ----------------------------------------->
		
		<h1>Mein IT-Blog - Dashboard</h1>
		<p class="specialP">Aktiver Benutzer: <?= $activeUser->getFullname() ?></p>

		<?php if($blogSuccessMessage || $catSuccessMessage): ?>
			<popupBox>
				<p class="success"><?= $blogSuccessMessage ?></p>
				<p class="success"><?= $catSuccessMessage ?></p>
				<a class="button" onclick="document.getElementsByTagName('popupBox')[0].style.display = 'none'">Schließen</a>
			</popupBox>		
		<?php endif ?>

		<main>
			
			<!-- Hinweis: Das Formular soll erst Sichtbar sein, wenn mindestens eine Kategorie existiert -->
			<?php if($categoriesArray): ?>
			
				<div>
					
					<h2>Neuen Blog-Eintrag verfassen</h2>
					<h3 class="error"><?= $blogErrorMessage ?></h3>
					
					<!--------------------------- NEW BLOG ENTRY FORM START -------------------------------->
					
					<form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST" enctype="multipart/form-data">
						
						<!--  INPUT HIDDENFIELD -->
						<input type="hidden" name="formsentNewBlogEntry">
						
						<br>
						<!-- SELECT BOX CATEGORIES -->
						<span class="error"><?= $errorCategoryId ?></span><br>
						<select name="cat_id">
							<?php foreach($categoriesArray AS $categoryObject): ?>
								<?php if($blog->getCategory()->getCat_id() == $categoryObject->getCat_id()): ?> 
									<option value='<?= $categoryObject->getCat_id() ?>' selected><?= $categoryObject->getCat_name() ?></option>
								<?php else: ?>
									<option value='<?= $categoryObject->getCat_id() ?>'><?= $categoryObject->getCat_name() ?></option>
								<?php endif ?>
							<?php endforeach ?>
						</select><span class="marker">*</span><br>
						
						<br>
						
						<!-- INPUT HEADLINE -->
						<span class="error"><?= $errorHeadline ?></span><br>
						<input type="text" name="blog_headline" placeholder="Überschrift" value="<?= $blog->getBlog_headline() ?>"><span class="marker">*</span><br>
						
						<!-- INPUT IMAGE UPLOAD -->
						<label>Bild hochladen:</label><br>
						<span class="error"><?= $errorImageUpload ?></span><br>
						<input type="file" name="blog_image"><br>
						
						<!-- SELECT BOX IMAGE ALIGNMENT -->
						<label>Bildausrichtung:</label><br>
						<span class="error"><?= $errorImageAlignment ?></span><br>
						<select name="blog_imageAlignment">
							<option value="fleft" <?php if($blog->getBlog_imageAlignment() == "fleft") echo "selected"?>>align left</option>
							<option value="fright" <?php if($blog->getBlog_imageAlignment() == "fright") echo "selected"?>>align right</option>
						</select>
						
						<br>
						<br>
						
						<!-- TEXTAREA CONTENT -->
						<span class="error"><?= $errorContent ?></span><br>
						<textarea name="blog_content" placeholder="Text..."><?= $blog->getBlog_content() ?></textarea><span class="marker">*</span><br>
						
						<!-- INPUT SUBMIT BUTTON -->
						<input type="submit" value="Veröffentlichen">
					</form>
					
					<!--------------------------- NEW BLOG ENTRY FORM END -------------------------------->
					
				</div>
			<?php else: ?>
				<div>
					<p class="info">Noch keine Kategorie vorhanden.</p>
				</div>
			<?php endif ?>
			
			<div>
			
				<h2>Neue Kategorie anlegen</h2>
				<h3 class="error"><?= $catErrorMessage ?></h3>
				
				<!------------------------------ NEW CATEGORY FORM START --------------------------------->
				
				<form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
					
					<!--  INPUT HIDDENFIELD -->
					<input type="hidden" name="formsentNewCategory">
					
					<!-- INPUT NEW CATEGORY -->
					<span class="error"><?= $errorCatName ?></span><br>
					<input type="text" name="cat_name" placeholder="Name der Kategorie" value="<?= $newCategory->getCat_name() ?>"><span class="marker">*</span><br>

					<!-- INPUT SUBMIT BUTTON -->
					<input type="submit" value="Anlegen">
					
				</form>
			
				<!------------------------------ NEW CATEGORY FORM END --------------------------------->
			
			</div>

		</main>
		
	</body>
</html>






