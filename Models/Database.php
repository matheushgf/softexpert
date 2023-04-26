<?php
class Database
{
    private $host = 'localhost';
    private $porta = '5432';
    private $database = 'softexpert';
    private $usuario = 'softexpertuser';
    private $senha = '123456';
    private $conn;

    private function conectar()
    {
        $this->conn = pg_connect("host={$this->host} port={$this->porta} dbname={$this->database} user={$this->usuario} password={$this->senha}");
        if (!$this->conn) {
            die("Falha na conexão: " . pg_last_error());
        }
    }

    public function getConexao()
    {
        if (!$this->conn || pg_connection_status($this->conn) == PGSQL_CONNECTION_BAD) {
            $this->conectar();
        }
        return $this->conn;
    }

    public function fecharConexao()
    {
        if ($this->conn && PGSQL_CONNECTION_OK) {
            pg_close($this->conn);
        }
        $this->conn = null;
    }

    public function __destruct()
    {
        $this->fecharConexao();
    }
}