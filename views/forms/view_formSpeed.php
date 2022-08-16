<form class="mx-3" method="POST" id="form" action="<?php echo $data['form']['action']; ?>">
	<input class="form-control col-4 mb-3" type="text" placeholder="Введите URL сайта" name="url">
	<div class="input-group my-3">
		<select name="strategy" class="custom-select col-3 mr-0" id="inputGroupSelect04" aria-label="Example select with button addon">
			<option value="DESKTOP">Десктопная</option>
            <option value="MOBILE">Мобильная</option>
            <option value="all">Десктопная и мобильная</option>
		</select>
		<div class="input-group-append col-3 pl-0 ">
    		<button class="btn btn-outline-secondary" type="submit"><?php echo $data['form']['button']; ?></button>
		</div>
	</div>
</form>