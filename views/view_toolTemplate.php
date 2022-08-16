<!-- Подключение функции обработки ответа ajax запроса -->
<script src="<?php echo $data['scriptSRC']; ?>"></script>

<!-- Функция отправки ajax запроса -->
<script src="../resource/js/ajax.js"></script>

<h2 class="m-4"><?php echo $data['h2']; ?></h2>
<p class="mx-3"><?php echo $data['p']; ?></p>

<!-- Подключение html-кода формы -->
<?php include_once $data['form']['path']; ?>

<div class="row my-2" id="spinner" style="display: none;">
    <div class="spinner-border mx-auto " role="status">
  		<span class="sr-only">Loading...</span>
	</div>
	<p class="col-11 my-auto pl-0">Подождите, идет загрузка...</p>
</div>

<!-- Вывод результата анализа -->
<div class="mx-3" id="result"></div>