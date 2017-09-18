function showPass(vuid) {
    $.get("credentials", {VUID: vuid, mode: "pass"}, function(retData) {
        $('.replace'+vuid).html(retData);
    });
}

function addInput() {
    $('#searchBlock').toggle();
    
    $('#permissionsTable tr:last').after('<tr>...</tr><tr>...</tr>');
}

var suggest_selected = 0;
var suggest_count = 0;

$('#searchPermission').keyup(function(K){
    switch(K.keyCode) {
        case 13: // Enter
        case 27: // Esc
        case 38: // Up
        case 40: // Down
            break;
        default:
            if( $(this).val().length > 1 ) {
                var inp = document.getElementById('searchPermission');
                var drop = document.getElementById('search_advice_wrapper');

                var rect = inp.getBoundingClientRect();
                drop.style.left = rect.left + "px";
                drop.style.top = (rect.bottom + 2)+"px";
                drop.style.display = 'block';

                input_initial_value = $(this).val();
                $("#search_advice_wrapper").html("").show();
                    $.get("wsname", { "wsname":$(this).val() }, function(data) {
                        //php скрипт возвращает нам строку, ее надо распарсить в массив.
                        // возвращаемые данные: ['test','test 1','test 2','test 3']
                        if(!data) return;

                        var list = eval( "(" + data + ")");
                        suggest_count = list.length;

                        if(suggest_count > 0) {
                            // перед показом слоя подсказки, его обнуляем
                            $("#search_advice_wrapper").html("").show();
                            for(var i in list)
                            {
                                if(list[i] != '')
                                    {
                                            // добавляем слою позиции
                                            $('#search_advice_wrapper').append('<div class="advice_variant">' + list[i] + '</div>');
                                            }
                                    }
                        }
                    }, 'html');
            }
            break;
    }
});
//считываем нажатие клавишь, уже после вывода подсказки
$("#searchPermission").keydown(function(I)
{
    switch(I.keyCode) 
    {
        // по нажатию клавишь прячем подсказку
        case 13: // enter
        case 27: // escape
            $('#search_advice_wrapper').hide();
            return false;
            break;
        // делаем переход по подсказке стрелочками клавиатуры
        case 38: // стрелка вверх
        case 40: // стрелка вниз
                I.preventDefault();
                if(suggest_count){
                    //делаем выделение пунктов в слое, переход по стрелочкам
                    key_activate( I.keyCode-39 );
                }
        break;
    }
});
// делаем обработку клика по подсказке
$('#search_advice_wrapper').on('click', '.advice_variant', function()
{
    var txt = $(this).text();
    //var login = txt.substring(txt.lastIndexOf("(")+1, txt.lastIndexOf(")"));
    // ставим текст в input поиска
    $('#searchPermission').val(txt);
    // прячем слой подсказки
    $('#search_advice_wrapper').fadeOut(350).html('');
});
// если кликаем в любом месте сайта, нужно спрятать подсказку
$('html').click(function(){
    $('#search_advice_wrapper').hide();
});

// если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
$('#searchPermission').click(function(event){
    if(suggest_count)
            $('#search_advice_wrapper').show();
            event.stopPropagation();
    });

function key_activate(n){
    $('#search_advice_wrapper div').eq(suggest_selected-1).removeClass('active');

    if(n === 1 && suggest_selected < suggest_count){
        suggest_selected++;
    }else if(n === -1 && suggest_selected > 0){
        suggest_selected--;
    }

    if( suggest_selected > 0){
        $('#search_advice_wrapper div').eq(suggest_selected-1).addClass('active');

        var txt = $('#search_advice_wrapper div').eq(suggest_selected-1).text();
        //var login = txt.substring(txt.lastIndexOf("(")+1, txt.lastIndexOf(")"));
        // ставим текст в input поиска
        $('#searchPermission').val(txt);
    } else {
        $("#searchPermission").val( input_initial_value );
    }
}
