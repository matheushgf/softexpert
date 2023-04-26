<section>
    <a type="button" class="btn btn-primary btnNovo mb-4" href="<?= $this->linkController('categorias/novo') ?>">Nova categoria</a>
    <table class="table table-hover table-bordered">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Nome</th>
          <th scope="col">% Imposto</th>
          <th scope="col">Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
            if (empty($dados)) { ?>
            <tr>
                <td colspan="5">Nenhum item encontrado</td>
            </tr>
        <?php }
            foreach($dados as $linha) {
              $linhaAtiva = ($linha['status'] == 't');
        ?>
            <tr>
                <td><?= $linha['id'] ?></td>
                <td><?= $linha['nome'] ?></td>
                <td><?= number_format($linha['imposto'], 2, ',', '.') ?>%</td>
                <td><?= $linhaAtiva ? 'Ativo' : 'Inativo' ?></td>
                <td>
                  <a class="btn btn-primary" href="/categorias/editar/<?= $linha['id'] ?>" role="button">Editar</a>
                  <a class="btn <?= $linhaAtiva ? 'btn-danger' : 'btn-success' ?>" href="/categorias/<?= $linhaAtiva ? 'deletar' : 'reativar' ?>/<?= $linha['id'] ?>" role="button"><?= $linhaAtiva ? 'Deletar' : 'Reativar' ?></a>
                </td>
            </tr>
        <?php } ?>
      </tbody>
    </table>
</section>