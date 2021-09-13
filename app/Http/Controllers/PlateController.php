<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plate;
use Illuminate\Http\Request;


class PlateController extends Controller
{
    private $plateModel;
    private $title;
    private $btn;
    private $categoryModel;

    public function __construct(Plate $plate, Category $category)
    {
        $this->plateModel = $plate;
        $this->categoryModel = $category;
        $this->title = 'Qual prato que você pensou?';
        $this->btn = 'OK';

    }

    public function index(Request $request)
    {

    }

    public function create(Request $request)
    {
        if(!$request->category && $request->name && strlen($request->name) > 2) {
            return back();
        } else {
            $this->saveChosen($request,'category');
            $this->setSession($request,'category',$request->category);
            $plate = ['category_id'=> $request->category, 'name'=> $request->name];
            return $this->store($plate);
        }
    }

    public function store(array $plate)
    {
        $this->plateModel->addPlate($plate);
        $rdPlate = $this->getRandomPlates();
        $titulo = "{$plate['name']} é ________ mas {$rdPlate->name} não.";
        $btn = 'OK';

        return view('gamer.categoryCreate', compact('titulo','btn'));
    }

    public function show(Request $request,$id)
    {

        // caso não seja a categoria que eu estou pensando
        if((int)$request->answer === 0 && (int)$request->category > 0) {
            return redirect()->route('category.show',$request->category);
        }

        if((int)$request->answer === 1 && (int)$request->category > 0 && (int)$request->plate > 0) {
            $typePlate = $this->plateModel->getTypePlateByCategoryID($request->category, $request->plate);
            if(count($typePlate) === 1)
                return view('gamer.success');
        }

        // Se não for o prato da categoria, procurar outro prato.
        if((int)$request->answer === 0 && $request->plate > 0 && $request->category > 0) {
            $got = $this->setSession($request,'plates',$request->plate);
            $plate = $this->plateModel->getPlatesByCategoryNotIn($request->category, $this->getSession($request, 'plates'))[0];
            if($request->plate < $plate->id) {
                if( $got === true) {
                    return view('gamer.plates', compact('plate'));
                } else {
                    $this->setSession($request,'plates',$plate->id);
                    return view('gamer.plates', compact('plate'));
                }
            } else {
                $category['id'] = $request->category;
                $category['plate'] = $request->plate;
                $titulo = $this->title;
                $btn = $this->btn;
                return view('gamer.plateCreate', compact('category','titulo','btn'));
            }

        }

        // Escolhendo a categoria
        $plate = $this->plateModel->getOnePlateCategoryID($request->category);
        return view('gamer.plates', compact('plate'));

    }

    private function getRandomPlates(): object
    {
        $listPlate = $this->plateModel->getPlates();
        $plate = $listPlate[rand(0, count($listPlate)-1)];
        return $plate;
    }

    private function isChosen(int $plate, array $chosen): bool
    {
        return in_array($plate, $chosen);
    }

    private function saveChosen(Request $request, $name): bool
    {
        $request->session()->put($name, []);
        return true;
    }

    private function getSession(Request $request, string $name): array
    {
        return $request->session()->get($name, []);
    }

    private function setSession(Request $request, $name, $value): bool
    {
        if($this->isChosen($request->plate, $this->getSession($request,$name)) === false) {
            $request->session()->push($name, $value);
            return true;
        }

        return false;
    }

}
