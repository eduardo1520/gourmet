<?php

namespace App\Http\Controllers;

use App\Models\Category;


class StartController extends Controller
{
    public function index(Category $category)
    {
        $ct = $category->getFirstCategory();
        return view('gamer.game', compact('ct'));
    }
}
