<?php
require_once('Controller.php');

class HomeController extends Controller
{
    public function __construct($nomeController)
    {
        parent::__construct($nomeController);
    }

    public function index()
    {
        $this->redirecionar('/produtos');
    }
}