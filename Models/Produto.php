<?php 
require_once('Model.php');

class Produto extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabela = 'produtos';

        $this->regras = [
            'nome' => ['obrigatorio'],
            'preco' => ['obrigatorio', 'decimal'],
            'categoria_ids' => ['obrigatorio']
        ];
    }
}