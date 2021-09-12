<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class GameGourmetController extends Controller
{
    private $titulo;

    public function __construct()
    {
        $this->titulo = 'Pense em um prato que gosta';
    }

    public function index(Request $request)
    {
        $titulo = $this->titulo;

        $request->session()->forget('plates');
        $request->session()->put('plates',[]);

        return view('gamer.index', compact('titulo'));
    }

}
