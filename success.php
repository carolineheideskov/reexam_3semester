<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Success</title>
<link href="main.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>

<body>
	
	<img class="logo" src="images/logo.png" alt="logo" />
	<img class="sog" src="images/sog.png" alt="sog" />
	
	
	<ul>
		<li><a href="index.html"><b>Forside</b></a></li>
		<li class="navi"><a href="index.php"><b>Produkter</b></a></li>
	<li style="float:right"><a href="cart.php"><button class="hej">Se/ret indkøbskurv</button></a></li>
</ul>

<h2>Din ordre blev gemmenført!</h2>


<div class="container1">
<h4>Ordrebekræftelse:</h4>
<br>
<?php

require_once('db_con.php');
	
	// henter order_id fra vores session 
$order_id = $_SESSION['order_id'];
	
$sql = 
	'SELECT product.id, product.name, product.price, order_items.order_id, order_items.product_id, order_items.quantity
	FROM order_items, product 
	WHERE product.id = order_items.product_id AND '.$order_id.' = order_items.order_id';
	
$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($pid, $name, $price, $oioid, $oipid, $quantity);
	
while ($stmt->fetch()){
		echo '<b>Produkt: ';
		echo $name ;
		
		echo '</b><br> Antal: ';
		echo $quantity ;

	echo '<br> Pris pr. stk.: ';
		echo $price;
		echo ' DKK';
		echo '<br><br>';
	
		
}
?>

<?php 
	
	// ganger antal og pris
	$sql = 'SELECT sum(order_items.quantity * product.price)
			FROM order_items, product WHERE product.id = order_items.product_id AND '.$order_id.' = order_items.order_id';
	
	$stmt = $con->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($total);
	
while ($stmt->fetch()){
	echo '<b>Totalpris:<b> ';
	echo $total;
	echo ' DKK';
	echo '<br /><br />';
}
	?>

<a href="index.php"><button class="kob">Tilbage til produkter</button></a>
<br><br>
</div>
	<img class="footer" src="images/footer.png" alt="footer" />
	</body>
</body>
</html>