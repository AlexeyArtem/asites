<form class="mx-3" method="POST" id="form" action="<?php echo $data['form']['action']; ?>">
    <p class="">Введите или скопируйте в поле список доменов и нажмите проверить:</p>
	<textarea class="form-control col-6" rows="5" type="text" name="sites" required></textarea>
    <button type="submit" class="btn btn-outline-secondary my-3"><?php echo $data['form']['button']; ?></button>
</form>