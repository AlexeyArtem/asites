function handler(response) {
    
    $("#result").empty();

    let content = '';
    
    if(typeof response.desk === 'undefined' || typeof response.mob === 'undefined') content = getList(response);
    else {
        content += '<div class="row mx-4 mb-4"><div class="col-6"><h3>Скорость с компьютера</h3></div>';
        content += getList(response.desk);
        content += "</div>";

        content += '<div class="row mx-4 mb-4"><div class="col-6"><h3>Скорость с мобильного</h3></div>';
        content += getList(response.mob);
        content += "</div>";
    }
    
    $('#result').append(content);
}

function getList(data)
{
    let content = '<div class="col-6 "><ol class="list-counter-circle">';
    
    content += `<li>Время загрузки достаточной части контента: ${data['first-contentful-paint']}</li>`;
    content += `<li>Индекс скорости: ${data['speed-index']}</li>`;
    content += `<li>Отрисовка крупного контента: ${data['largest-contentful-paint']}</li>`;
    content += `<li>Время до момента возможности взаимодейтсвия: ${data['time-to-interactive']}</li>`;
    content += `<li>Время задержки при вводе: ${data['total-blocking-time']}</li>`;
    content += `<li>Смещение макета: ${data['cumulative-layout-shift']}</li>`;

    content += '</ol></div>';

    return content;
}