<?php

namespace App\Controllers;

class Inicio extends BaseController
{
    protected $helpers = ['funciones'];

    public function __construct(){
        $this->session;
    }

    public function index()
    {
        return view('index');
    }
}
