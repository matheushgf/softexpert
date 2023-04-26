<?php 
    $dadosValidacao = !empty($dados['validacao']) ? $dados['validacao'] : [];
    $dadosForm = !empty($dados['dadosForm']) ? $dados['dadosForm'] : [];
?>
<section>
    <form action="<?= $this->linkController('produtos/salvar') ?>" method="POST" class="needs-validation <?= !empty($dados['validacao']) ? 'was-validated' : '' ?>" novalidate>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?= !empty($dadosForm['nome']) ? $dadosForm['nome'] : '' ?>" required>
            <?php if (!empty($dadosValidacao['nome'])) { ?>
                <div class="invalid-feedback">
                    <?= implode('<br>', $dadosValidacao['nome']) ?>
                </div>
            <?php } ?>
        </div>
        <div class="mb-3">
            <label for="preco" class="form-label">Preço</label>
            <input type="number" step="0.1" class="form-control" id="preco" name="preco"  value="<?= !empty($dadosForm['preco']) ? $dadosForm['preco'] : '' ?>" required>
            <?php if (!empty($dadosValidacao['preco'])) { ?>
                <div class="invalid-feedback">
                    <?= implode('<br>', $dadosValidacao['preco']) ?>
                </div>
            <?php } ?>
        </div>
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <select class="form-select select2-dark" id="select-categorias" name="categoria_ids[]" required></select>
            <?php if (!empty($dadosValidacao['categoria_ids'])) { ?>
                <div class="invalid-feedback">
                    <?= implode('<br>', $dadosValidacao['categoria_ids']) ?>
                </div>
            <?php } ?>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-success">Salvar</button>
        </div>
    </form>
</section>
<script type="text/javascript">
    var idsCategoria = '<?= !empty($dadosForm['categoria_ids']) ? json_encode($dadosForm['categoria_ids']) : 'undefined' ?>';
    if (idsCategoria != 'undefined') {
        idsCategoria = JSON.parse(idsCategoria);
    }
</script>