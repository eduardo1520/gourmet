<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    private $categoryModel;
    private $titulo;
    private $btn;

    public function __construct(Category $category)
    {
        $this->categoryModel = $category;
        $this->titulo = 'Qual prato que você pensou?';
        $this->btn = 'OK';
    }

    public function index(Request $request)
    {

        $category = $request->category;

        if((int) $request->answer == 1) {
            $child = $this->getChild($category);
            if(empty($child)) {
                return redirect()->route('plate.index',['category' =>(int) $category, 'answer' => (int) $request->answer]);
            }
        } else {
            $listCategory = $this->findCategory($category);
            if(empty($listCategory)) {
                return redirect()->route('plate.index',['category' => $category, 'answer' => (int) $request->answer]);
            }
        }
        $titulo = $this->titulo;
        $btn = $this->btn;
        return view('gamer.categoryCreate', compact('category','titulo','btn'));
    }

    public function store(Request $request)
    {
        $category['id'] = $request->session()->get('category')[0];
        $this->categoryModel->addCategory(['name' => $request->name, 'id_pai' => $category['id']]);
        return redirect()->route('home');
    }

    private function findCategory($category) {
        $nextCategory  = $this->categoryModel->getCategoryChild((int) $category);
        if(empty($nextCategory)) {
            $nextCategory = $this->categoryModel->getNextCategory([$category]);
        }
        return $nextCategory;
    }

    private function getChild($category): ?object
    {
        return $this->categoryModel->getCategoryChild((int) $category);
    }

    public function create(Request $request)
    {
        if($request->all()) {
            return redirect()->route('category.index');
        }
    }
}
