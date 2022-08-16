function handler(response) {
    $("#result").empty();

    let nameLinks = 'Количество ссылок';
    if(response.name == 'Файл индекса Sitemap') nameLinks = 'Количество файлов sitemap';

    var content = '<table class="statTable table table-dark table-striped table-hover table-sm  w-50 mx-auto">';
    content += `<tr><td>Тип файла </td><td>${response.name}</td></tr>`;
    content += `<tr><td>Размер </td><td>${response.size} КБ</td></tr>`;
    content += `<tr><td>${nameLinks} </td><td>${response.links}</td></tr>`;
    content += '</table>';

    $('#result').append(content);
}