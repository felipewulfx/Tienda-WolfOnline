<?php
include "configs/config.php";
include "configs/funciones.php";

if(!isset($p)){
	$p = "principal";
}else{
	$p = $p;
}

?>
<!DOCTYPE html>
<html>
<head>		
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap-reboot.min.css">
	<link rel="stylesheet" href="fontawesome/css/all.css">
	<link rel="stylesheet" href="fontawesome/js/all.js">
	<meta charset="utf-8"/>
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="css/estilo.css">


	<title>Tienda WOLF Online</title>
</head>

<body>
	<div class="header" type="imagen">
		<img src="admin/avatars/logotipowolfmetalico.png" class="w-25">
		<!-- <img src="admin/avatars/logotipowolfmetalico.png" class="w-25"> -->
	</div>
	<div align="center">
		<div class="menu">
			<a href="?p=principal">Principal</a>
			<a href="?p=productos">Productos</a>
			<!--<a href="?p=ofertas">Ofertas</a>-->
			<?php
			if(isset($_SESSION['id_cliente'])){
				?>
				<a href="?p=carrito">Mi Carrito</a>
				<a href="?p=miscompras">Mis Compras</a>
				<?php
			}else{
				?>
				<a href="?p=login">Iniciar Sesion</a>
				<a href="?p=registro">Registrate</a>
				<?php
			}
			?>


			<!-- <a href="admin/">Panel Admin</a>  -->
		<!--
		<a href="?p=admin">Administrador</a>
	-->



	<?php
	if(isset($_SESSION['id_cliente'])){
		?>

		<a class="pull-right subir" href="?p=salir">Salir</a>
		<a class="pull-right subir" href="#"><?=nombre_cliente($_SESSION['id_cliente'])?></a>

		<?php
	}
	?>
</div>
</div>
<div class="cuerpo">
	<?php
	if(file_exists("modulos/".$p.".php")){
		include "modulos/".$p.".php";
	}else{
		echo "<i>No se ha encontrado el modulo <b>".$p."</b> <a href='./'>Regresar</a></i>";
	}
	?>
</div>



<?php
if(isset($_SESSION['id_cliente'])){
	?>
	<div class="carritot" onclick="minimizer()">
		Carrito de compra
		<input type="hidden" id="minimized" value="0"/>
	</div>
	<?php
}
?>




<div class="carritob">

	<table class="table table-striped">
		<tr>
			<th>Nombre del producto</th>
			<th>Cantidad</th>
			<th>Precio </th>
		</tr>
		<?php
		$id_cliente = clear($_SESSION['id_cliente']);
		$q = $mysqli->query("SELECT * FROM carro WHERE id_cliente = '$id_cliente'");
		$monto_total = 0;
		while($r = mysqli_fetch_array($q)){
			$q2 = $mysqli->query("SELECT * FROM productos WHERE id = '".$r['id_producto']."'");
			$r2 = mysqli_fetch_array($q2);

			$preciototal = 0;
			if($r2['oferta']>0){
				if(strlen($r2['oferta'])==1){
					$desc = "0.0".$r2['oferta'];
				}else{
					$desc = "0.".$r2['oferta'];
				}

				$preciototal = $r2['price'] -($r2['price'] * $desc);
			}else{
				$preciototal = $r2['price'];
			} 

			$nombre_producto = $r2['name'];

			$cantidad = $r['cant'];

			$precio_unidad = $r2['price'];
			$precio_total = $cantidad * $preciototal;
			$imagen_producto = $r2['imagen'];

			$monto_total = $monto_total + $precio_total;



			?>
			<tr>
				<td><?=$nombre_producto?></td>
				<td><?=$cantidad?></td>
				<td><?=$precio_unidad?> <?=$divisa?></td>
			</tr>
			<?php
		}
		?>
	</table>
	<br>
	<span>Monto Total: <b class="text-green"><?=$monto_total?> <?=$divisa?></b></span>

	<br><br>
	<form method="post" action="?p=carrito">
		<input type="hidden" name="monto_total" value="<?=$monto_total?>"/>
		<button class="btn btn-primary" type="submit" name="finalizar"><i class="fa fa-check"></i> Finalizar Compra</button>
	</form>

</div>

<div class="footer">
	Copyright WOLF &copy; <?=date("Y")?>
</div>




<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="fontawesome/js/all.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/app.js"></script>
<script type="text/javascript" src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>

<!-- Carrito Minimizado -->
<?php
if(isset($_SESSION['id_cliente'])){
	?>

	<script type="text/javascript">

		function minimizer(){

			var minimized = $("#minimized").val();

			if(minimized == 0){
		//mostrar
		$(".carritot").css("bottom","350px");
		$(".carritob").css("bottom","0px");
		$("#minimized").val('1');
	}else{
		//minimizar

		$(".carritot").css("bottom","0px");
		$(".carritob").css("bottom","-350px");
		$("#minimized").val('0');
	}
}
</script> 

<?php
}
?>