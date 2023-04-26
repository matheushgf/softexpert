<?php 
require_once('Model.php');

class ProdutoCategoria extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabela = 'produto_categorias';

        $this->regras = [
            'categoria_id' => ['obrigatorio'],
            'produto_id' => ['obrigatorio']
        ];
    }

    public function categoriasPorProduto(
        int $idProduto,
        bool $apenasAtivos = true
    ): array {
        $where = [
            [
                'campo' => 'pc.produto_id',
                'operacao' => '=',
                'valores' => $idProduto
            ]
        ];

        if ($apenasAtivos) {
            array_push(
                $where,
                [
                    'campo' => 'pc.status',
                    'operacao' => '=',
                    'valores' => 'true'
                ]
            );
        }
        $arProdutos = $this->select(
            ['c.nome AS nome', 'c.id as id', 'pc.status AS rel_ativo', 'pc.id as rel_id'],
            $where,
            [
                [
                    'tabela' => 'categorias AS c',
                    'condicao' => 'c.id = pc.categoria_id'
                ]
            ],
            ['c.nome' => 'ASC', 'c.id' => 'DESC'],
            [],
            null,
            'pc'
        );
        
        return $arProdutos;
    }

    public function getPorProdutoECategoria(
        int $idProduto,
        int $idCategoria,
        bool $apenasAtivos
    ): array {
        $where = [
            [
                'campo' => 'produto_id',
                'operacao' => '=',
                'valores' => $idProduto
            ],
            [
                'campo' => 'categoria_id',
                'operacao' => '=',
                'valores' => $idCategoria
            ]
        ];

        if ($apenasAtivos) {
            array_push(
                $where,
                [
                    'campo' => 'status',
                    'operacao' => '=',
                    'valores' => 'true'
                ]
            );
        }
        $arProdutos = $this->select(
            [],
            $where,
            [],
            [],
            [],
            1
        );
        
        return !empty($arProdutos[0]) ? $arProdutos[0] : [];
    }
}