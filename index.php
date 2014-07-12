<?php
	require_once('calendar.php');
	$calendar = new calendar();
?>

<html>
	<head>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<aside>
			<ul>
			<?php 
				$calendar->printMonths();
			?>
			</ul>
		</aside>
		<div id="main">
			<?php
				$calendar->getPage();
			?>
		</div>
	</body>
</html>
