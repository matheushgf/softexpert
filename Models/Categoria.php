<?php 
require_once('Model.php');

class Categoria extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabela = 'categorias';

        $this->regras = [
            'nome' => ['obrigatorio'],
            'imposto' => ['obrigatorio', 'decimal']
        ];
    }
}