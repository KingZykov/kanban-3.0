<!DOCTYPE html>
<html lang="en">

<head>
	<?php $title= "kanban & Kalendar"; ?>
	
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="../img/kanban_favicon.png">

<!-- BOOTSTRAP-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" media='all' integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<!-- FONTS-->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Comfortaa&display=swap" rel="stylesheet">

<!-- CSS STYLE-->
<link rel="stylesheet" href="../css/style.css">
<title><?php $title ?></title>
	
</head>


<body class="bg">

<!-- -------------------------------------- MENU -------------------------------------------- -->
<header class="m-0 p-0">
</header>


<!-- ----------------------- MAIN CONTENT --------------------------------------- -->
<div class="container">
	<div class="row m-0 p-0">	
		<div class="col-6 p-5 justify-content-center">
			<p class="text-center h1 fw-bold m-5">Вход</p>
			<form class="px-5" name="login" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
				<div class="mb-4">
					<div>
					 <p class="text-black h5">Логин:</p>
					</div>					
					<div class="input-group">
																	
						<input class="form-control" type="text" name="user" placeholder="login" required>
					</div>
				</div>
				<div class="mb-4">		
				    <div>
					   <p class="text-black h5">Пароль:</p>
					</div>						
					<div class="input-group">				
						<input class="form-control" type="password" name="password" placeholder="password" required>						
					</div>
				</div>			


				<div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
				<button type="button" class="btn btn-primary" onclick="login.submit()">Войти</button>
				</div>

				<?php if(!empty($errors)): ?>
					<div class="err">
						<ul>
							<?php echo $errors; ?>
						</ul>
					</div>
				<?php endif; ?>
			</form>
			<span class="d-flex reg justify-content-center">Нет аккаунта?<a class="nav-link text-primary m-0 p-0 pl-2" href="../login/register.php">Регистрация</a></span>			
		</div>
		
	</div>
</div>


<!-- --------------------- JS SCRIPTS JQUERY + POPPER + BOOTSTRAP ------------------------- -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>