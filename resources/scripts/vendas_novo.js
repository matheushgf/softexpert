$(document).ready(function (){
    var divItens = $('#div-itens');
    var btnAdicionar = $('#btn-adicionar');
    var qtdItens = 0;

    btnAdicionar.on('click', function(){
        console.log('ADD');
        divItens.append("");
    });

    var selectProduto = $('.select-produto');
    selectProduto.select2({
        placeholder: 'Selecione o produto',
        allowClear: false,
        multiple: false,
        language: 'pt-BR',
        theme: 'bootstrap-5',
        selectionCssClass: ':all:',
        dropdownCssClass: ':all:',
        minimumInputLength: 2,
        ajax: {
            url: '/produtos/procurar',
            dataType: 'json',
            cache: true
        }
    });

    var inputQuantidade = $('.item-venda-quantidade').on('change', function() {
        var atual = $(this);
        var ordem = atual.data('ordem');
    });
});