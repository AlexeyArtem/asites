function handler(response) {
    
    $("#result").empty();

    console.log(response);

    var content = '<table class="table table-dark table-striped table-hover table-sm w-50 mx-auto">' + '<thead><tr><th>Название</th><th>№ счетчика</th></tr></thead>';
    content += `<tr><td>Яндекс Метрика</td><td>${response.counterYA}</td></tr>`;
    content += `<tr><td>Google Analytics</td><td>${response.counterGA}</td></tr>`;
    content += '</table>';
    $('#result').append(content);
}