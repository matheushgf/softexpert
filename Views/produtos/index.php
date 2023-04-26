<section>
    <a type="button" class="btn btn-primary btnNovo mb-4" href="<?= $this->linkController('produtos/novo') ?>">Novo produto</a>
    <table class="table table-hover table-bordered">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Nome</th>
          <th scope="col">Preço</th>
          <th scope="col">Categoria</th>
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
                <td>R$ <?= number_format($linha['preco'], 2, ',', '.') ?></td>
                <td><?= $linha['categorias'] ?></td>
                <td><?= $linhaAtiva ? 'Ativo' : 'Inativo' ?></td>
                <td>
                  <a class="btn btn-primary" href="/produtos/editar/<?= $linha['id'] ?>" role="button">Editar</a>
                  <a class="btn <?= $linhaAtiva ? 'btn-danger' : 'btn-success' ?>" href="/produtos/<?= $linhaAtiva ? 'deletar' : 'reativar' ?>/<?= $linha['id'] ?>" role="button"><?= $linhaAtiva ? 'Deletar' : 'Reativar' ?></a>
                </td>
            </tr>
        <?php } ?>
      </tbody>
    </table>
</section>