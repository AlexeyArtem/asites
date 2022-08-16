<form class="mx-3" method="POST" id="form" action="<?php echo $data['form']['action']; ?>">
    <p>Введите или скопируйте в поле список ключевых слов и нажмите проверить:</p>
    <textarea class="form-control col-6" rows="5" type="text" rows="5" placeholder="Введите ключевые слова" name="keywords" required></textarea>
    <button class="btn btn-outline-secondary my-3" type="submit"><?php echo $data['form']['button']; ?></button>
</form>

<!-- Проверка количества введенных строк в textarea на допустимое количество -->
<script>
    $(function() {
        // Подписка на событие нажатия клавиши в textarea
    $('textarea').on('keypress', function(event) {
          var text = $('textarea').val();
          var lines = text.split("\n");
          var currentLine = this.value.substr(0, this.selectionStart).split("\n").length;
          if(event.keyCode == 13 && lines.length >= $(this).attr('rows')) return false;
        });
    });
</script>