function handler(response) {
    
    $("#result").empty();
    
    let content = '<textarea class="form-control col-6 w-50" rows="15" type="text"></textarea>';
    $('#result').append(content);

    $('textarea').val($.trim(response[0] + '\n' + $('textarea').val()));
    for (let index in response ) {
        $('textarea').val($.trim($('textarea').val() + '\n' + response[index]));
    }
}