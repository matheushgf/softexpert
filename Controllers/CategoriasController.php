<?php
require_once('Controller.php');
require_once('Models/Categoria.php');

class CategoriasController extends Controller
{
    public function __construct($nomeController)
    {
        parent::__construct($nomeController);
    }

    public function index()
    {
        $categorias = new Categoria();
        $arCategorias = $categorias->select(
            ['id', 'nome', 'imposto', 'status'],
            [],
            [],
            ['id' => 'ASC'],
            []
        );

        $this->mostraView('index', $arCategorias);
    }

    public function novo()
    {
        $arDados = $this->getRespostaForm();
        $this->mostraView('novo', $arDados);
    }

    public function salvar()
    {
        $arCampos = $this->getCamposPost();
        $categorias = new Categoria();

        $validacaoCategoria = $categorias->validar($arCampos);

        if (!empty($validacaoCategoria)) {
            $this->salvarRespostaForm($arCampos, $validacaoCategoria);
            $this->redirecionar('/categorias/novo');
        }

        $categorias->salvar($arCampos);
        $this->redirecionar('/categorias');
    }

    public function editar()
    {
        $idCategoria = $this->getIdURL();
        $categoria = (new Categoria())->getPorId($idCategoria);

        $arDados = $this->getRespostaForm($categoria);
        $arDados['id'] = $idCategoria;

        $this->mostraView('editar', $arDados);
    }

    public function edicao()
    {
        $arCampos = $this->getCamposPost();
        $categorias = new Categoria();
        $idCategoria = $arCampos['id'];

        $validacao = $categorias->validar($arCampos);

        if (!empty($validacao)) {
            $this->salvarRespostaForm($arCampos, $validacao);
            $this->redirecionar('/categorias/editar/' . $idCategoria);
        }

        $categorias->atualizar($idCategoria, $arCampos);
        $this->redirecionar('/categorias');
    }

    public function deletar()
    {
        $idCategoria = $this->getIdURL();
        $categorias = new Categoria();

        $categorias->atualizar(
            $idCategoria,
            ['status' => 'false']
        );
        $this->redirecionar('/categorias');
    }

    public function reativar()
    {
        $idCategoria = $this->getIdURL();
        $categorias = new Categoria();

        $categorias->atualizar(
            $idCategoria,
            ['status' => 'true']
        );
        $this->redirecionar('/categorias');
    }
    
    public function procurar()
    {
        $categorias = new Categoria();
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

        $arCategorias = $categorias->select(
            ['id', 'nome AS text'],
            $where,
            [],
            ['nome' => 'ASC'],
            [],
            10
        );

        echo json_encode([
            'results' => $arCategorias,
            'more' => false
        ]);
    }
}