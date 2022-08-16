<div id="loginForm">
	<h1 class="m-3 ">Авторизация администратора</h1>

	<!-- Вывод ошибки при авторизации -->
	<div style="color:red;">
	    <?php if($data != null): ?>
	        <?php echo $data['error']; ?> 
	    <?php endif; ?>
	</div>

	<form  method="POST" action="../login/input">   
		<div class="row mx-3 mb-2"> 
			<p class="col-4 my-auto">Логин:</p>
			<input class="form-control col-7" type="text" placeholder="Введите логин" name="login" autocomplete="on" value="<?php if($data != null) echo $data['login']; ?>" required>
		</div>
		<div class="row mx-3 mb-3">
			<p class="col-4 my-auto">Пароль:</p>
		    <input class="form-control col-7" type="password" placeholder="Введите пароль" name="password" autocomplete="on" value="<?php if($data != null) echo $data['password']; ?>" required>
		</div>
	    <button class="btn btn-outline-secondary mx-3 w-50" type="submit">Войти</button>
	</form>
</div>