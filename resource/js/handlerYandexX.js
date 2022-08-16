function handler(response) {
    
    $("#result").empty();
    
    var content = '<table class="table table-dark table-striped table-hover table-sm w-50 mx-auto">' + '<thead><tr><th>Сайт</th><th>Значение</th></tr></thead>';
    for (var index in response) 
    {
        var link = `<a href="${response[index].link}" target="_blank">${response[index].value}</a>`;
        content += `<tr><td>${response[index].name}</td><td>${link}</td></tr>`;
    }
    content += '</table>';

    $('#result').append(content);
}