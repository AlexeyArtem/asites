function handler(response) {
    $("#result").empty();
    
    if(response.type == 'captcha') {
        addFormCaptcha('#result', response);
    }
    else if (response.type == 'result') {
        createTableResult('#result', response);
        addFormGrouping('#result', response);
    }
    else {
        $('#result').html("Ошибка при запросе. Для продолжения перезагрузите страницу и сделайте запрос через 1-2 минуты.");
    }
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
function createTableResult(idResElem, response) {
    let links = response.links;
    $(idResElem).append($('<table class="table table-borderless table-sm w-50 mx-auto" id="resultTable">').append('<tr class="bg-info">'));
    
    //Добавление заголовков к таблице
    for(keyword in links) {
        let name = keyword.replace(/%20/g, ' ');
        $('#resultTable > tr').append($('<th>', { text: name }));
    }

    //Добавление строк со ссылками к таблице
    let dictionary = getDictionaryCssClasses(links);
    console.log(dictionary);

    let numRow = 1;
    for(let i = 0; i < 10; i++) {
        $('#resultTable').append('<tr class="table-active">');
        numRow++;
        for(keyword in links) {
            let nameClass = '';
            if(dictionary.has(links[keyword][i])) nameClass = dictionary.get(links[keyword][i]).get('nameClass');
            $('tr:nth-child(' + numRow + ')').append($('<td>', { class: nameClass, text: cutString(links[keyword][i]) }));
        }
    }

    for(let key of dictionary.keys()) {
        let nameClass = '.' + dictionary.get(key).get('nameClass');
        let r = Math.floor(Math.random() * (256));
        let g = Math.floor(Math.random() * (256));
        let b = Math.floor(Math.random() * (256));
        let color = '#' + r.toString(16) + g.toString(16) + b.toString(16);

        $(nameClass).css('background-color', color).hover(
            function() {
                $(nameClass).toggleClass("td_class_hover")
            }
        );
    }
    
}

//метод, который возвращает словарь с название Css-класов для тегов td
function getDictionaryCssClasses(links) {
    //названия кючей для вложенного словаря
    let nameKeyClass = 'nameClass';
    let nameKeyCount = 'count';
    
    let dictionary = new Map();

    let countClasses = 0;
    for(keyword in links) {
        for(index in links[keyword]) {
            let link = links[keyword][index];
            
            //проверка на то, встречается ли в предыдущих пройденных ссылках или нет
            if(dictionary.has(link)) {
                let element = dictionary.get(link);
                element.set(nameKeyCount, element.get(nameKeyCount) + 1);
                
                dictionary.set(link, element);
            }
            else {
                countClasses += 1;
                let element = new Map();
                element.set(nameKeyClass, 'td_class_' + countClasses);
                element.set(nameKeyCount, 1);

                dictionary.set(link, element);

            }
        }
    }

    //удаление ссылок, которые встречались 1 раз
    for(let key of dictionary.keys()) {
        if(dictionary.get(key).get(nameKeyCount) == 1) {
            dictionary.delete(key);
        }
    }

    return dictionary;
}

function setClass_Callback(nameClass, color) {
    $(nameClass).css('background-color', color).hover(
        function() {
            $(nameClass).toggleClass("td_class_hover")
        }
    );
}

function generateColor(callback, nameClass) {
    let r = Math.floor(Math.random() * (256));
    let g = Math.floor(Math.random() * (256));
    let b = Math.floor(Math.random() * (256));
    let color = '#' + r.toString(16) + g.toString(16) + b.toString(16);
    callback(nameClass, color);
}

function cutString(str) {
    let maxCount = 35;
    if(str.length > maxCount) {
        let result = str.substr(0, maxCount);
        result += '...';
        return result;
    }
    else return str;
}

//функция добавления формы для группировки
function addFormGrouping(idResElem, response) {

    let countLvl = Object.keys(response.links).length;
    if(countLvl == 1) return;
    
    $(idResElem).append (
        $('<form>', {class: 'mx-auto col-6', id: 'formGrouping', action: ''})
            .append (
                $('<select class="custom-select col-6 mr-0" name="selectLevel" />')
            )
            .append (
                $('<button>', {
                    class: 'col-6 btn btn-outline-secondary my-3',
                    type: 'submit',
                    text: 'Сгруппировать'
                })
            )
    );

    for(let i = 2; i <= countLvl; i++) {
        $('select[name=selectLevel]').append($("<option/>", {value: i, text: i}));
    }

    $(idResElem).append('<table class="table table-borderless table-sm w-50 mx-auto" id="groupingResult">');
    
    //подписка на отправку формы
    $('#formGrouping').submit(function(e) {
        e.preventDefault();

        $("#groupingResult").empty();
        displayLoading('spinner');
        
        let groupingLvl = $('select[name=selectLevel]').val();
        let dictionary = getDictionaryCssClasses(response.links);
        for(let key of dictionary.keys()) {
            let nameClass = dictionary.get(key).get('nameClass');
            if(dictionary.get(key).get('count') == groupingLvl) {
                $("#groupingResult").append($('<tr class="table-active">')
                    .append($('<td>', { class: nameClass, text: cutString(key) })));
            }
        }
        stopLoading('spinner');
      })
}