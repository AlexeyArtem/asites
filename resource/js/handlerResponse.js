function handler(response) {
    $("#result").empty();
    
    var content = '<table class="responseServer_table table table-dark table-striped table-hover table-sm w-50 mx-auto">';
    content += `<tr><td>Код статуса HTTP</td><td>${response.info.status}</td></tr>`;
    content += `<tr><td>Время ответа сервера</td><td>${response.info.time} мс</td></tr>`;
    content += `<tr><td>IP сайта</td><td>${response.info.ip}</td></tr>`;
    content += `<tr><td>Кодировка</td><td>${response.info.encoding}</td></tr>`;
    content += `<tr><td>Размер страницы</td><td>${response.info.size} КБ</td></tr>`;
    content += '</table>';

    content += '<ul class="list-counter-circle w-50 mx-auto">';
    for (var index in response.headers) content += `<li>${response.headers[index]}</li>`;
    content += '</ul>';

    $('#result').append(content);
}