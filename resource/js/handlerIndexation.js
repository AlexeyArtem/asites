function handler(response) {
    $("#result").empty();
    if(response.type == 'captcha') addFormCaptcha('#result', response);
    else if (response.type == 'result') addTableResult('#result', response);

}

//функция вывода формы для ввода капчи
function addFormCaptcha(idResElem, response) {
    $(idResElem).append (
        $('<form>', {
            id: 'formCaptcha',
            action: response.action,
            method: 'POST'
        })
            .append (
                $('<img>', {
                    class: 'mb-2',
                    src: response['url_img'],
                    alt: 'картинка проверки captcha'
                })
            )
            .append (
                $('<input>', {
                    type: 'hidden',
                    name: 'url_check',
                    value: response['url_checkcaptcha']
                })
            )
            .append (
                $('<input>', {
                    class: 'form-control col-2',
                    type: 'text',
                    name: 'rep',
                    placeholder: 'Введите текст с картинки',
                    required: true,
                })
            )
            .append (
                $('<button>', {
                    class: 'btn btn-outline-secondary my-2 col-2',
                    type: 'submit',
                    text: 'Отправить'
                })
            )
    );

    //подписка на отправку формы
    $('#formCaptcha').submit(function(e) {
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
                $('#result').html("Ошибка в отправке данных.");
                stopLoading('spinner');
            }
        });
        e.preventDefault(); //отмена действия по умолчанию для кнопки submit в форме
      })
}

//функция вывода таблицы с результатами
function addTableResult(idResElem, response) {
    $(idResElem).append(
        $('<table class="statTable table table-dark table-striped table-hover table-sm  w-50 mx-auto">')
            .append($('<tr>').append('<th>Поисковая система</th>').append('<th>Результат</th>'))
            .append($('<tr>')
                .append($('<td>', { text: 'Яндекс' }))
                .append($('<td>')
                    .append($('<a>', {
                            text: response.value,
                            href: response.url,
                            target: '_blank'
                        })
                    )
                )
            )
    );
}