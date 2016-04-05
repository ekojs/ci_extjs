<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>CSS Tables</title>
	
	<style type="text/css">
	@media screen{
		body {
			font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
			color: #4f6b72;
			background: #E6EAE9;
		}
	}
	@media print{
		body {
			font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
		}
	}
	
	a {
		color: #c75f3e;
	}
	
	#mytable {
		width: 700px;
		padding: 0;
		margin: 0;
	}
	
	caption {
		padding: 0 0 5px 0;
		width: 700px;	 
		font: italic 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
		text-align: right;
	}
	
	th {
		font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
		color: #4f6b72;
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		border-top: 1px solid #C1DAD7;
		letter-spacing: 2px;
		text-transform: uppercase;
		text-align: left;
		padding: 6px 6px 6px 12px;
		background: #CAE8EA url(./assets/images/bg_header.jpg) no-repeat;
	}
	
	th.nobg {
		border-top: 0;
		border-left: 0;
		border-right: 1px solid #C1DAD7;
		background: none;
	}
	
	td {
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		background: #fff;
		padding: 6px 6px 6px 12px;
		color: #4f6b72;
		font: normal 12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	}
	
	td.alt {
		background: #F5FAFA;
		color: #797268;
	}
	
	th.spec {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #fff url(./assets/images/bullet1.gif) no-repeat;
		font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	}
	
	th.specalt {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #f5fafa url(./assets/images/bullet2.gif) no-repeat;
		font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
		color: #797268;
	}
	</style>
</head>

<body>
<table id="mytable" cellspacing="0" summary="EJS - pengarang">
<caption>Table: pengarang </caption>
  <tr>
	<?php 
	$i = 0;
	foreach ($records[0] as $key => $value){
		if($i==0){
			echo '<th scope="col" abbr="'.$key.'" class="nobg">'.$key.'</th>';
		}else {
			echo '<th scope="col" abbr="'.$key.'">'.$key.'</th>';
		}
		
		$i++;
	}
	?>
  </tr>
  <?php 
	$i = 0;
	for($i=0; $i<(sizeof($records)); $i++){
		echo '<tr>';
		$j = 0;
		foreach ($records[$i] as $key => $value){
			if(($j==0) && ($i%2 == 0)){
				echo '<th scope="row" abbr="'.$value.'" class="specalt">'.$value.'</th>';
			}elseif(($j==0) && ($i%2 != 0)){
				echo '<th scope="row" abbr="'.$value.'" class="spec">'.$value.'</th>';
			}else{
				if($i%2 == 0){
					echo '<td class="alt">'.$value.'</td>';
				}else{
					echo '<td>'.$value.'</td>';
				}
			}
			$j++;
		}
		echo '<tr/>';
	}
  ?>
</table>
</body>
</html>
