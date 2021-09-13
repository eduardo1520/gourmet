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
        // Se for uma nova categoria
        $category['id'] = $request->session()->has('category') === true
            ? $request->session()->pull('category')[0]: 0;

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

    public function show(Request $request, $id)
    {
        // verifica se existe categorias
        if($request->has('categories') === false && $this->saveChosen($request, 'categories') === true) {
            $this->setCategorySession($request, $id,'categories',[$id]);
        }

        $chosen = $this->getSession($request, 'categories');
        print_r($chosen);
        $category = $this->categoryModel->getCategoryNotIn($chosen)[0];
        $this->setCategorySession($request, $category->id,'categories',$chosen);

//        dd($chosen, $category);
//        $this->removeSessionChosen($request, 'categories');
        $titulo = "O prato que você pensou é {$category->name} ?";
        $btn = $this->btn;
        return view('gamer.category', compact('category','titulo','btn'));
    }

    private function isChosen(int $plate, array $chosen): bool
    {
        return in_array($plate, $chosen);
    }

    private function getSession(Request $request, string $name):array
    {
        return $request->session()->get($name, []);
    }

    private function setCategorySession(Request $request, $id, $name, $value): bool
    {
        if($this->isChosen($id, $this->getSession($request,$name)) === false) {
            $request->session()->push($name, $value);
            return true;
        }
        return false;
    }

    private function removeSessionChosen(Request $request, string $name): void
    {
        $request->session()->forget($name);
    }

    private function saveChosen(Request $request, $name): bool
    {
        $request->session()->put($name, []);
        return true;
    }

}
