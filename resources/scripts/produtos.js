$(document).ready(function(){
    var selectCategoria = $('#select-categorias');

    if (typeof idsCategoria != 'undefined' && idsCategoria !== null) {
        $.ajax({
            url: '/categorias/procurar',
            data: {
                'ids': idsCategoria
            },
            dataType: 'json',
            success: function(dados) {
                if (dados.results) {
                    dados.results.forEach(item => {
                        var opcao = new Option(item.text, item.id, true, true);
                        selectCategoria.append(opcao).trigger('change');
                    });

                    selectCategoria.trigger({
                        type: 'select2:select',
                        params: {
                            data: dados
                        }
                    });
                }
            }
        });
    }
    
    selectCategoria.select2({
        placeholder: 'Selecione a categoria',
        allowClear: false,
        multiple: true,
        language: 'pt-BR',
        theme: 'bootstrap-5',
        selectionCssClass: ':all:',
        dropdownCssClass: ':all:',
        minimumInputLength: 2,
        ajax: {
            url: '/categorias/procurar',
            dataType: 'json',
            cache: true
        }
    });
});