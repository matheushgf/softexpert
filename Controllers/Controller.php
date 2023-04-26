<?php
class Controller
{
    public $nomeController = null;
    public $viewAtual = null;
    private $viewPadrao = BASE_PATH . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'default.php';
    
    public function __call($name, $arguments)
    {
        if (!method_exists($this, $name)) {
            throw new Exception('404');
        }
    }

    public function __construct($nomeController)
    {
        $this->nomeController = $nomeController;
    }

    public function mostraView(
        string $nomeView,
        array $dados = []
    ) {
        $config = $this->carregarConfig();

        $this->viewAtual = (
            BASE_PATH .
            DIRECTORY_SEPARATOR .
            'Views' .
            DIRECTORY_SEPARATOR .
            $this->nomeController .
            DIRECTORY_SEPARATOR .
            $nomeView .
            '.php'
        );
        
        if (!file_exists($this->viewAtual)) {
            $this->viewAtual = null;
            die('404');
        }
        extract([
            'viewAtual' => $this->viewAtual,
            'paginaAtual' => $this->nomeController . '_' . $nomeView,
            'controllerAtual' => $this->nomeController,
            'config' => $config,
            'dados' => $dados
        ]);

        include $this->viewPadrao;
    }
    
    public function carregarConfig(): array
    {
        include BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'project.php';
        return $config;
    }
    public function getViewAtual(): string
    {
        return $this->viewAtual;
    }

    public function getNomeController(): string
    {
        return $this->nomeController;
    }

    public function linkController(string $caminho): string
    {
        return $this->carregarConfig()['HOST'] . $caminho;
    }

    public function getCamposPost(): array
    {
        $arDados = [];
        foreach ($_POST as $campo => $valor) {
            $arDados[$campo] = $valor;
        }

        return $arDados;
    }

    public function getCamposGet(): array
    {
        $arDados = [];
        foreach ($_GET as $campo => $valor) {
            $arDados[$campo] = $valor;
        }

        return $arDados;
    }

    public function salvarRespostaForm(
        array $dados,
        array $validacao
    ) {
        session_start();

        $_SESSION['dadosForm'] = $dados;
        $_SESSION['validacao'] = $validacao;
    }

    public function destroiRespostaForm()
    {
        unset($_SESSION['dadosForm']);
        unset($_SESSION['validacao']);
        session_destroy();
    }

    public function getRespostaForm(array $dadosOriginais = []): array
    {
        session_start();

        $resposta = [];

        if (isset($_SESSION['dadosForm'])) {
            $resposta['dadosForm'] = $_SESSION['dadosForm'];
        } else {
            $resposta['dadosForm'] = [];
        }

        if (isset($_SESSION['validacao'])) {
            $resposta['validacao'] = $_SESSION['validacao'];
        } else {
            $resposta['validacao'] = [];
        }
        
        foreach ($dadosOriginais as $campo => $valor) {
            if (
                empty($resposta['validacao'][$campo])
                && empty($resposta['dadosForm'][$campo])
            ) {
                $resposta['dadosForm'][$campo] = $valor;
            }
        }

        $this->destroiRespostaForm();
        return $resposta;
    }

    public function redirecionar(string $caminho)
    {
        header('Location: ' . $caminho);
        die();
    }

    public function getIdURL(): int
    {
        $url = explode('/', $_SERVER['REQUEST_URI']);
        return isset($url[3]) && !empty($url[3]) && is_numeric($url[3]) ? (int) $url[3] : null;
    }
}