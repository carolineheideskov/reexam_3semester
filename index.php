<?php session_start(); ?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Harald Nyborg</title>
<link href="main.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
</head>

<body>
	
	<img class="logo" src="images/logo.png" alt="logo" />
	<img class="sog" src="images/sog.png" alt="sog" />
	
	
	<ul>
		<li><a href="index.html"><b>Forside</b></a></li>
		<li class="navi"><a href="index.php"><b>Produkter</b></a></li>
	<li style="float:right"><a href="cart.php"><button class="hej">Se/ret indkøbskurv</button></a></li>
</ul>

<h2>Produkter:</h2>
	
	<br>
	<!--
<a href="index.php?id=<?php echo $id; ?>&clear_cart=yes"><button name="clear_cart" type="submit" value="clear_cart">Tøm indkøbskurv</button></a>
<br><br> -->
	
	
<div class="container">

<?php 
require_once('db_con.php');

// select * fra product table 

$sql = 'SELECT id, name, thumbnail, price FROM product'; // Vælger hvad den skal vælge fra databasen
$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($id, $name, $thumbnail, $price); 
	
	while($stmt->fetch()){ 
?>


<table border="2">
<tr><th><?php echo $name; ?></th></tr>
<tr><th><img src="images/<?php echo $thumbnail; ?>" width="200" height="250" /></th></tr>
<tr><th><?php echo $price; ?> DKK</th></tr>

<tr><th><a href="index.php?id=<?php echo $id; ?>&add=yes"><button name="add" type="submit" value="add">Læg i indkøbskurv</button></th></tr> 


<?php
}
?>
<?php
	// hvis array er tomt = 0
if (empty($_SESSION['cart'])) $_SESSION['cart'] ='';


if(filter_input(INPUT_GET, 'add')) {
	
	// henter id fra url
	$_SESSION['cart'][$_GET['id']]['id'] = $_GET['id'];
	
	// tilføjer 1 til quantity
	if(!isset($_SESSION['cart'][$_GET['id']]['quantity'])) { 
		$_SESSION['cart'][$_GET['id']]['quantity'] = 1;
	} else {
		$_SESSION['cart'][$_GET['id']]['quantity'] += 1;
	}

}
	// reset session hvis clear cart
if(filter_input(INPUT_GET, 'clear_cart')) {
	$_SESSION['cart'] = null;
}
?>

<!--<div class="cart"><h4>Din indkøbskurv:</h4> -->

<?php
	/*

	// Kører $_SESSION array hvis det er et array 
	if(is_array($_SESSION['cart'])){
	// viser alle vare i session array 
    foreach ($_SESSION['cart'] as $val) 
	echo '<hr>' . 'Produkt: ' . $val['id'] . ' Antal: ' . $val['quantity'];
	echo '<hr>';
	
	}*/
?>
 
	


	</table>
	</div>
	<img class="footer" src="images/footer.png" alt="footer" />
</body>
</html>