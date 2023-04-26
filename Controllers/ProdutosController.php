<?php
require_once('Controller.php');
require_once('Models/Produto.php');
require_once('Models/Categoria.php');
require_once('Models/ProdutoCategoria.php');

class ProdutosController extends Controller
{
    public function __construct($nomeController)
    {
        parent::__construct($nomeController);
    }

    public function index()
    {
        $produtos = new Produto();
        $arProdutos = $produtos->select(
            ['id', 'nome', 'preco', 'status'],
            [],
            [],
            ['id' => 'ASC']
        );

        foreach ($arProdutos as &$produto) {
            $arCategorias = (new ProdutoCategoria())->categoriasPorProduto($produto['id'], true);
            $produto['categorias'] = implode(
                ', ',
                array_map(
                    function ($categoria) {
                        return $categoria['nome'];
                    },
                    $arCategorias
                )
            );
        }

        $this->mostraView('index', $arProdutos);
    }

    public function novo()
    {
        $arDados = $this->getRespostaForm();

        $this->mostraView('novo', $arDados);
    }

    public function salvar()
    {
        $arCampos = $this->getCamposPost();
        $produtos = new Produto();
        $produtoCategorias = new ProdutoCategoria();

        $validacaoProduto = $produtos->validar($arCampos);
        if (!empty($validacaoProduto)) {
            $this->salvarRespostaForm($arCampos, $validacaoProduto);
            $this->redirecionar('/produtos/novo');
        }

        $arIdsCategorias = $arCampos['categoria_ids'];
        unset($arCampos['categoria_ids']);

        $idProduto = $produtos->salvar($arCampos);

        foreach ($arIdsCategorias as $idCategoria) {
            $produtoCategorias->salvar([
                'categoria_id' => $idCategoria,
                'produto_id' => $idProduto
            ]);
        }
        
        $this->redirecionar('/produtos');
    }

    public function editar()
    {
        $idProduto = $this->getIdURL();
        $produto = (new Produto())->getPorId($idProduto);
        $arCategorias = (new ProdutoCategoria())->categoriasPorProduto($idProduto, true);
        $produto['categoria_ids'] = array_map(
            function ($categoria) {
                return $categoria['id'];
            },
            $arCategorias
        );

        $arDados = $this->getRespostaForm($produto);
        $arDados['id'] = $idProduto;

        $this->mostraView('editar', $arDados);
    }

    public function edicao()
    {
        $arCampos = $this->getCamposPost();
        $produtos = new Produto();
        $produtoCategorias = new ProdutoCategoria();
        $idProduto = $arCampos['id'];

        $validacaoProduto = $produtos->validar($arCampos);

        if (!empty($validacaoProduto)) {
            $this->salvarRespostaForm($arCampos, $validacaoProduto);
            $this->redirecionar('/produtos/editar/' . $idProduto);
        }

        $arIdsCategoriasForm = $arCampos['categoria_ids'];
        unset($arCampos['categoria_ids']);

        $produtos->atualizar($idProduto, $arCampos);

        $arCategoriasAtuais = $produtoCategorias->categoriasPorProduto($idProduto, true);
        
        foreach ($arIdsCategoriasForm as $idCategoriaForm) {
            $categoria = [
                'categoria_id' => $idCategoriaForm,
                'produto_id' => $idProduto
            ];

            $produtoCategoria = $produtoCategorias->getPorProdutoECategoria(
                $idProduto,
                $idCategoriaForm,
                false
            );
            if (empty($produtoCategoria)) {
                $produtoCategorias->salvar($categoria);
            } elseif (
                !empty($produtoCategoria)
                && $produtoCategoria['status'] != 't'
            ) {
                $produtoCategorias->atualizar(
                    $produtoCategoria['id'],
                    ['status' => 'true']
                );
            }
        }

        foreach ($arCategoriasAtuais as $categoriaAtual) {
            if (!in_array($categoriaAtual['id'], $arIdsCategoriasForm)) {
                $produtoCategorias->atualizar(
                    $categoriaAtual['rel_id'],
                    ['status' => 'false']
                );
            }
        }

        $this->redirecionar('/produtos');
    }

    public function deletar()
    {
        $idProduto = $this->getIdURL();
        $produtos = new Produto();

        $produtos->atualizar(
            $idProduto, 
            ['status' => 'false']
        );
        $this->redirecionar('/produtos');
    }

    public function reativar()
    {
        $idProduto = $this->getIdURL();
        $produtos = new Produto();

        $produtos->atualizar(
            $idProduto, 
            ['status' => 'true']
        );
        $this->redirecionar('/produtos');
    }

    public function procurar()
    {
        $produtos = new Produto();
        $arParams = $this->getCamposGet();
        
        $where = [
            [
                'campo' => 'status',
                'operacao' => '=',
                'valores' => 't'
            ]
        ];
        
        if (!empty($arParams['term'])) {
            array_push(
                $where,
                [
                    'campo' => 'LOWER(nome)',
                    'operacao' => 'LIKE',
                    'valores' => '%' . strtolower($arParams['term']) . '%'
                ]
            );
        }
        if (!empty($arParams['ids'])) {
            array_push(
                $where,
                [
                    'campo' => 'id',
                    'operacao' => 'IN',
                    'valores' => $arParams['ids']
                ]
            );
        }

        $arProdutos = $produtos->select(
            ['id', 'nome AS text', 'imposto'],
            $where,
            [],
            ['nome' => 'ASC'],
            [],
            10
        );

        echo json_encode([
            'results' => $arProdutos,
            'more' => false
        ]);
    }
}