<?php 
require_once('Database.php');

class Model
{
    protected $db = null;
    protected $tabela = null;
    protected $regras = null;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function select(
        array $campos = [],
        array $where = [],
        array $joins = [],
        array $orderBy = [],
        array $groupBy = [],
        int $limit = null,
        string $aliasTabela = '',
    ): array {
        $con = $this->db->getConexao();
    
        $query = 'SELECT ' . (!empty($campos) ? implode(', ', $campos) : '*') . " FROM {$this->tabela} ";
        $query .= !empty($aliasTabela) ? ("AS {$aliasTabela} ") : '';

        foreach ($joins as $join) {
            $query .= 'LEFT JOIN ' . $join['tabela'] . ' ON ' . $join['condicao'];
        }
        $query .= ' ';

        $placeholders = [];
        $valores = [];
        $i = 1;
        foreach ($where as $clausula) {
            if ($i == 1) {
                $query .= 'WHERE ';
            } else {
                $query .= 'AND ';
            }

            if ($clausula['operacao'] == 'IN') {
                $query .= $clausula['campo'] . ' IN (';
                foreach ($clausula['valores'] as $valor) {
                    $placeholder = '$' . $i;
                    array_push($placeholders, $placeholder);
                    array_push($valores, $valor);

                    $query .= $placeholder . (end($clausula['valores']) == $valor ? '' : ',');
                    $i++;
                }
                $query .= ')';
            } else {
                $placeholder = '$' . $i;
                array_push($placeholders, $placeholder);
                array_push($valores, $clausula['valores']);

                $query .= $clausula['campo'] . ' ' . $clausula['operacao'] . ' ' . $placeholder;
                $i++;
            }
            $query .= ' ';
        }

        if (!empty($groupBy)) {
            $query .= 'GROUP BY ' . implode(', ', $groupBy) . ' ';
        }

        if (!empty($orderBy)) {
            $query .= 'ORDER BY ';
            foreach ($orderBy as $campo => $ordem) {
                $query .= "{$campo} {$ordem}" . (array_key_last($orderBy) == $campo ? ' ' : ', ');
            }
        }

        if (!empty($limit)) {
            $query .= ' LIMIT ' . $limit;
        }
        
        pg_prepare($con, "", $query);        
        $resultado = pg_execute($con, "", $valores);
    
        if (!$resultado) {
            $erro = pg_last_error($con);
            $this->db->fecharConexao();
            die("Erro na query: " . $erro);
        }
        
        $arResultado = pg_fetch_all($resultado);
        $arResultado = $arResultado ?: [];

        $this->db->fecharConexao();
        return $arResultado;
    }

    public function getPorId(
        int $id,
        array $campos = [],
        array $where = []
    ): array {
        array_push(
            $where,
            [
                'campo' => 'id',
                'operacao' => '=',
                'valores' => $id
            ]
        );
        $resposta = $this->select($campos, $where);
        if (empty($resposta[0])) {
            return [];
        }
        return $resposta[0];
    }

    public function salvar(array $arCampos): int|bool
    {
        $con = $this->db->getConexao();
    
        $campos = array_keys($arCampos);
        $placeholders = [];
        for ($i = 1; $i <= count($campos); $i++) {
            array_push($placeholders, '$'.$i);
        }
        
        $query = "INSERT INTO {$this->tabela} (" . implode(',', $campos) . ") VALUES (" . implode(',', $placeholders) . ") RETURNING id";
        pg_prepare($con, "", $query);
        $resultado = pg_execute($con, "", array_values($arCampos));
        $idInserido = pg_fetch_array($resultado);
        
        if (!$resultado) {
            $erro = pg_last_error($con);
            $this->db->fecharConexao();
            die("Erro na query: " . $erro);
        }
        
        $this->db->fecharConexao();
        return !empty($idInserido['id']) ? $idInserido['id'] : true;
    }

    public function atualizar(
        int $idLinha,
        array $arCampos
    ): bool {
        $con = $this->db->getConexao();

        $valores = [];
        $query = "UPDATE {$this->tabela} SET";
        $i = 1;

        foreach ($arCampos as $campo => $valor) {
            if ($campo != 'id') {
                $query .= ($i > 1 ? ', ' : ' ') . $campo . '=' . '$' . $i;
                array_push($valores, $valor);
                $i++;
            }
        }
        $query .= ' WHERE id=$' . $i;
        array_push($valores, $idLinha);

        pg_prepare($con, "", $query);
        $resultado = pg_execute($con, "", $valores);
    
        if (!$resultado) {
            $erro = pg_last_error($con);
            $this->db->fecharConexao();
            die("Erro na query: " . $erro);
        }
        
        $this->db->fecharConexao();
        return true;
    }

    public function validar(
        array $dados,
        array $regras = []
    ): array {
        $regrasValidacao = !empty($this->regras) ? $this->regras : $regras;
        $errosValidacao = [];

        foreach ($regrasValidacao as $campo=>$regras) {
            foreach ($regras as $regra) {
                switch ($regra) {
                    case 'obrigatorio':
                        if (empty($dados[$campo])) {
                            if (!isset($errosValidacao[$campo])) $errosValidacao[$campo] = [];
                            array_push($errosValidacao[$campo], 'Campo obrigatório.');
                        }
                        break;
                    case 'decimal':
                        if (!is_numeric($dados[$campo])) {
                            if (!isset($errosValidacao[$campo])) $errosValidacao[$campo] = [];
                            array_push($errosValidacao[$campo], 'O valor deve ser um número válido');
                        }
                        break;
                }
            }
        }
        
        return $errosValidacao;
    }
}