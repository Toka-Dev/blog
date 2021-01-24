<?php
#***************************************************************************************#


				/**
				*
				* @file view index-page
				* @author Thomas Schleder <t.schleder@web.de>
				* @copyright toka e. K.
				* @lastmodifydate 2020-08-19
				*
				*/
				
				
#***************************************************************************************#
			
			
				#***********************************#
				#********** CONFIGURATION **********#
				#***********************************#
				
				require_once("controller/index.controller.php");

			
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

		<!-- ---------------------------------- HEADER ---------------------------------- -->

		<header class="fright">
		
			<?php if(!isset($_SESSION['usr_id'])): ?>
				<h3 class="error"><?= $loginMessage ?></h3>
				
				<!-- ---------- LOGIN FORM START ---------- -->
				<form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
					<input type="hidden" name="formsentLogin">
					<input type="text" name="usr_email" placeholder="Email">
					<input type="password" name="loginPassword" placeholder="Password">
					<input type="submit" value="Login">
				</form>
				<!-- ---------- LOGIN FORM END ---------- -->
			<?php else: ?>
				<!-- -------- Links -------- -->
				<p>
					<a href="?action=logout">Logout</a><br>
					<a href='dashboard.php'>zum Dashboard >></a>
				</p>
			<?php endif ?>
		
		</header>
		
		<div class="clearer"></div>
		
		<br>
		<hr>
		<br>
		
		<!-- ------------------------------- HEADER ENDE --------------------------------- -->

		<h1>Mein IT-Blog</h1>
		<p class="specialP"><a href='<?= $_SERVER['SCRIPT_NAME'] ?>'>Alle Einträge anzeigen</a></p>

		<!-- ------------------------------- BLOG ENTRIES --------------------------------- -->
		
		<main>

			<?php if($blogsArray): ?>

				<div>
			
					<?php foreach($blogsArray AS $blogObject): ?>
						<?php $dateTime = isoToEuDateTime($blogObject->getBlog_date()) ?>
						
						<article>
							
							<p class='blogCategory'><a href='?action=filter&cat_id=<?= $blogObject->getCategory()->getCat_id() ?>'>Kategorie: <?= $blogObject->getCategory()->getCat_name() ?></a></p>						
							<h2 class='blogHeadline'><?= $blogObject->getBlog_headline() ?></h2>
							<p class='blogInfo'><?= $blogObject->getUser()->getFullname() ?> (<?= $blogObject->getUser()->getUsr_city() ?>) schrieb am <?= $dateTime['date'] ?> um <?= $dateTime['time'] ?> Uhr:</p>
							
							<p>
							
								<?php if($blogObject->getBlog_image()): ?>
									<img class='blogImage <?= $blogObject->getBlog_imageAlignment() ?>' src='<?= $blogObject->getBlog_image() ?>' alt='Bild gepostet von <?= $blogObject->getUser()->getUsr_firstname() ?>' title='Bild gepostet von <?= $blogObject->getUser()->getUsr_firstname() ?>'>
								<?php endif ?>
								
								<?= nl2br($blogObject->getBlog_content()) ?>
							</p>
							
							<br>
							<hr class="blogHr">
							
						</article>
						
					<?php endforeach ?>

				</div>
				
			<?php else: ?>
				<div>
					<p class="info">Noch keine Blogeinträge vorhanden.</p>
				</div>
			<?php endif ?>
		
			<!-- ---------------------------- BLOG ENTRIES ENDE ------------------------------- -->
			
			<!-- ----------------------------- CATEGORIES START ------------------------------------- -->
			
			<?php if($categoriesArray): ?>
				<div class="categoryEntrys">
					<h3>Einträge nach Kategorie filtern:</h3>
					<?php foreach($categoriesArray AS $categoryObject):?>
						<p><a href="?action=filter&cat_id=<?= $categoryObject->getCat_id() ?>"><?= $categoryObject->getCat_name() ?></a></p>
					<?php endforeach ?>
				</div>
			<?php else: ?>
				<div>
					<p class="info">Noch keine Kategorien vorhanden.</p>
				</div>
			<?php endif ?>
			
			<!-- --------------------------- CATEGORIES ENDE ---------------------------------- -->
			
		</main>

	</body>

</html>







