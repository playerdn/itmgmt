function showPass(vuid) {
    $.get("credentials", {VUID: vuid, mode: "pass"}, function(retData) {
        // console.log(retData);
        $('.replace'+vuid).html(retData);
    });
}

function addInput() {
    $('#searchBlock').toggle();
    
    $('#permissionsTable tr:last').after('<tr>...</tr><tr>...</tr>');
}

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
