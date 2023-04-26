<?php 
    $dadosValidacao = !empty($dados['validacao']) ? $dados['validacao'] : [];
    $dadosForm = !empty($dados['dadosForm']) ? $dados['dadosForm'] : [];
?>
<section>
    <form action="<?= $this->linkController('categorias/edicao') ?>" method="POST" class="needs-validation <?= !empty($dados['validacao']) ? 'was-validated' : '' ?>" novalidate>
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
            <label for="imposto" class="form-label">% Imposto</label>
            <input type="number" step="0.1" class="form-control" id="imposto" name="imposto"  value="<?= !empty($dadosForm['imposto']) ? $dadosForm['imposto'] : '' ?>" required>
            <?php if (!empty($dadosValidacao['imposto'])) { ?>
                <div class="invalid-feedback">
                    <?= implode('<br>', $dadosValidacao['imposto']) ?>
                </div>
            <?php } ?>
        </div>
        <input type="hidden" name="id" value="<?= $dados['id'] ?>">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</section>