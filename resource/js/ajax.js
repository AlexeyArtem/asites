$(document).ready(
    function(){
        $('#form').submit(function(e) {
            $("#result").empty();
            displayLoading('spinner');
            var $form = $(this);

            $.ajax({
              type: $form.attr('method'), // Метод отправки
              url: $form.attr('action'), // url для отправки
              data: $form.serialize(), // Сериализация данных из формы
              success: function(response) { // //Функция обратного вызова при успешной отправке данных
                    handler(response);
                    stopLoading('spinner');
                },
              error: function(response) { // Данные не отправлены
                    //stopLoading('spinner');
                    $('#result').html("Ошибка в отправке данных.");
                    stopLoading('spinner');
                }
            });
            e.preventDefault(); //отмена действия по умолчанию для кнопки submit в форме
          })
    }
);