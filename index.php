<?php

include('classes\color.class.php');
session_start();

// unset($_SESSION['color']);
$test = false;
if(!isset($_SESSION['color'])){
	$color = new Color();
	$_SESSION['color'] = $color;
}else{
	$color = $_SESSION['color'];
}
if(isset($_POST['colorSubmit'])){
	$color->setHexColor($_POST['hexColor']);
	$_SESSION['color'] = $color;
}
if(isset($_POST['colorRandom'])){
	$color->setRandomColor();
	$_SESSION['color'] = $color;
}
if(isset($_POST['colorRandom'])){
	$color->setRandomColor();
	$_SESSION['color'] = $color;
}
if(isset($_POST['colorStart'])){
	$test = true;
}
if(isset($_POST['colorEnd'])){
	$test = false;
}

//var_dump($color);
//var_dump($color->hexToHsl('186276'));

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Color tester</title>
  <meta name="description" content="Kolor tester">
  <meta name="author" content="Agata Rogoń">

  <link rel="stylesheet" href="assets/css/styles.css?v=1.0">

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>

<body>
	<header class="section page_header">
		<h1 class="title page_title">Color tester</h1>
		<h2 class="description">Select your own color or use random and test your eyesight.</h2>
	</header>
	<main class="main">
		<section style="background-color:<?php print $color->getHexColor(); ?>" class="section current_color">
			<h3 class="title">Current color: <?php print $color->getHexColor(); ?> | <?php print $color->getHslColor(); ?> </h3>
		</section>
		
		
		<?php if(!$test){ ?>
		<section class="section select_color">
			<h3 class="title">Change current color</h3>		
			<form class="form" method="post">
				<label class="item label" for="colorPicker">Select new color:</label>
				<input class="item picker" type="color" id="colorPicker" name="hexColor" value="<?php print $color->getHexColor(); ?>">
				<button class="item btn" type="submit" name="colorSubmit">Save color</button>
				<button class="item btn" type="submit" name="colorRandom">Random</button>
				<button class="item btn btn_start" type="submit" name="colorStart">Start test</button>
			</form>
		</section>
		<?php }else{ ?>
		<section class="section test">
			<h3 class="title">Queue color tons in proper way</h3>
			<div class="tons">
				<?php print $color->getTones()['print']; ?>
			</div>
			<form class="form" method="post">
				<button class="item btn" type="submit" name="colorRandom">Submit colors</button>
				<button class="item btn btn_start" type="submit" name="colorEnd">Back to color select</button>
			</form>
		</section>			
		<?php }?>
	</main>
	
	
	<footer class="section page_footer">
		<p> © 2018 | All rights reserved.</p>
	</footer>
	
  <!-- <script src="js/scripts.js"></script> -->
</body>
</html>