<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
		<link rel="stylesheet" href="/style/style.css" type="text/css">
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="/resource/js/loading.js"></script>
		<link rel="icon" href="/resource/images/favicon.png" type="image/x-icon">
		<title>Asites</title>
	</head>
	<body class="bg-light min-vh-100">
		<header class="border-bottom border-dark">
			<nav class="navbar navbar-expand navbar-light ">
				<ul class="navbar-nav w-100">
					<li class="nav-item"><a class="navbar-brand text-uppercase" href="/">ASITES</a></li>
					<li class="nav-item"><a class="nav-link" href="/">Главная</a></li>
					<li class="nav-item"><a class="nav-link" href="/tools">Инструменты</a></li>
					<li class="nav-item"><a class="nav-link" href="/blog">Блог</a></li>
					<li class="nav-item">
						<a class="nav-link" href="/login">
							<?php 
								if(isset($_SESSION['logged_user'])) echo "Админ-панель";
								else echo "Вход";
							?>
						</a>
					</li>
	    		</ul>  				
			</nav>
		</header>

		<!-- Основной контент, куда будут встраиваться разные view -->
		<main class="header-h2 header-h2-left header-h3 mb-5">
			<?php include 'views/'.$content_view; ?>
		</main>

		<footer class="bg-dark text-white-50">
			<p>Product by 18-IAS</p>
		</footer>
	</body>
</html>