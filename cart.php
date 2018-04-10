<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="main.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<title>Indkøbskurv</title>
</head>

<body>
	
	<img class="logo" src="images/logo.png" alt="logo" />
	<img class="sog" src="images/sog.png" alt="sog" />
	
	
	<ul>
		<li><a href="index.html"><b>Forside</b></a></li>
		<li class="navi"><a href="index.php"><b>Produkter</b></a></li>
	<li style="float:right"><a href="cart.php"><button class="hej">Se/ret indkøbskurv</button></a></li>
</ul>


<h2>Din indkøbskurv:</h2>

<div class="container1" style="border-width: 0px;border-style: solid;border-color:black;">
	
<?php
require_once('db_con.php');
	// Hvis session er tom -> return false
if($_SESSION['cart'] == null)
{
	
	echo '<br />';
	echo 'Kurven er tom';
	echo '<br /><br />';
	echo '<a href="index.php"><button class="kob">Tilbage til produkter</button></a>';
	return FALSE;
	
}	
if(filter_input(INPUT_GET, 'new_order')) {
		
	$sql = 'INSERT INTO orders (id) VALUES (NULL)';
	$stmt = $con->prepare($sql);
	$stmt->execute();
	$order_id = $con->insert_id;
	
	// hvis order_id er set
	if(isset($order_id)) {
		// kører vores session igennem foreach løkke
		foreach($_SESSION['cart'] as $val) {
			
			$sql = 'INSERT INTO order_items (order_id, product_id, quantity) VALUES (?,?,?)';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('iii', $order_id, $val['id'], $val['quantity']);
			$stmt->execute();
			
			
		}
		
		// redirecter til success.php
		
		echo "<script type=\"text/javascript\"> setTimeout(function () {
   				window.location.href= 'success.php';
				}, 0); </script>"; 
		
		$_SESSION['cart'] = null;
		$_SESSION['order_id'] = $order_id;
	}
		
	
}
	
  // hvis delete -> unset valgt id fra session
if(filter_input(INPUT_GET, 'delete')) {
		unset($_SESSION['cart'][$_GET['id']]);
}
						  
if(filter_input(INPUT_GET, 'update')) {
	
		$quantity = 'quantity' . $_GET['id'];
		$_SESSION['cart'][$_GET['id']]['quantity'] = $_POST[$quantity];
};
    
    foreach ($_SESSION['cart'] as $val){
			$id = $val['id'];
		
			$sql = "SELECT name, price FROM product WHERE id='$id'";
			$stmt = $con->prepare($sql);
			$stmt->execute();
			$stmt->bind_result($name, $price);
	
			while($stmt->fetch());
		
			echo '<form method="POST" action="cart.php?id='.$id.'&update=yes"> <br>';
			echo '<br /> <b>Produkt: '  . $name  . '</b><br><br> Antal: ' . '<input name="quantity' . $id . '" type="text" value="' . $val['quantity'] . '"/>';
			echo ' <br> <button class="kob" name="update" type="submit" value="update">Opdater</button>';
			echo '<a href="cart.php?id='.$id.'&delete=yes"><button class="kob" name="Delete" type="button" value="delete">Fjern</button></a>';
			echo '<br><br> Pris: ';
			$sum_total =  $price * $val ['quantity'];
			echo $sum_total;
		echo ' DKK';
			echo '<br />';
			echo '<br />';
			echo '</form>';
			
	}	
?>
	
<?php 
	
	// hvis price og antal = 0 -> sæt 0
	if (empty($price)) $price =''; 
	if (empty($val['quantity'])) $val['quantity'] ='';  
?>


 
<a href="cart.php?new_order=yes"><button class="kob1" name="submit" id="indsend" value="indsend">Gennemfør køb</button></a> <br>
<a href="index.php"><button class="kob">Tilbage til produkter</button></a>
	<a href="index.php?id=<?php echo $id; ?>&clear_cart=yes"><button class="kob" name="clear_cart" type="submit" value="clear_cart">Tøm indkøbskurv</button></a>

<br>
<br>
	
</div>
		
	<img class="footer" src="images/footer.png" alt="footer" />
	</body>
</html>