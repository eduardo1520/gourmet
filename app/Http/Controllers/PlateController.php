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
//        $platesCategory = $this->getPlatesByCategory($request);
//        $qtdPlates = count($platesCategory);

//        if($this->iguessed($request) === true)
//            return view('gamer.success');
//
//        $plate = $this->choosePlate($request, $qtdPlates, $platesCategory);
//        if($plate->id > 0)
//            return view('gamer.plates', compact('plate'));
//
//        $titulo = $this->title;
//        $btn = $this->btn;
//        $category = $this->getPlatesRegistered($request, $qtdPlates);
//        if(isset($category['id']) && $category['id'] > 0)
//            return view('gamer.plateCreate', compact('category', 'titulo','btn'));
//
//        if ($this->newPlate($request) == true)
//            return redirect()->route('plate.create',[$request]);
//
//        if ($request->category && (int)$request->answer === 0 && empty($request->plate))
//            return redirect()->route('category.create', ['category' => $request->category, 'answer' => $request->answer, 'type' => 'category']);
    }

    public function create(Request $request)
    {
        if(!$request->category && $request->name && strlen($request->name) > 2) {
            return back();
        } else {
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
        $plate = $this->plateModel->getPlateByCategoryID($id);
        if((int)$request->answer === 1 && empty($request->plate) && !empty($plate->id))
            return view('gamer.plates',compact('plate'));

        if((int)$request->answer === 1 && (int)$request->plate > 0 && (int)$request->category > 0 && !empty($plate->id))
            return view('gamer.success');


        dd([$plate]);

        $nextPlate = $this->plateModel->getPlatesByCategoryNotIn($request->category, [$request->plate]);
        $plate = $this->plateModel->getNextPlateByCategoryID($request->category);

        if(empty($nextPlate) && (int)$request->category < $plate->category_id
            && $this->saveChosen($request,'category') === true
            && $this->setSession($request, 'category', $request->category) === true)
            return view('gamer.plates',compact('plate'));

        $titulo = $this->title;
        $btn = $this->btn;
        $category['id'] = $this->getSession($request, 'category')[0];
        return view('gamer.plateCreate', compact('titulo','btn','category'));























//        if($this->iguessed($request) === true) {
//            $game = current($this->getSession($request, 'iguessed'));
//            if($game['typeFood'] == '') {
//                $this->removeSessionChosen($request,'iguessed');
//                $this->saveChosen($request,'iguessed',[]);
//                $game['typeFood'] = true;
//                $this->setSession($request,'iguessed', $game);
//                $plate = (object) $this->plateModel->getTypePlateByCategoryID($request->plate, $request->category);
//
//                return view('gamer.plates', compact('plate'));
//            }
//
//            $this->removeSessionChosen($request,'iguessed');
//            return view('gamer.success');
//
//        }
//
//        if((int)$request->answer === 1 && (int) $id == 1){
//            $this->removeSessionChosen($request,'plates');
//            $this->saveChosen($request,'plates');
//            $plate = $this->plateModel->getPlateByCategoryID($id)[0];
//            return view('gamer.plates', compact('plate'));
//        } elseif((int)$request->answer === 0 && (int)$request->plate > 0 && (int)$request->category > 0) {
//            $chosen = !empty($this->getSession($request,'plates')) ? $this->getSession($request,'plates') : [2];
//            $plate = (object) $this->plateModel->getPlatesByCategoryByNotIn($request->category,$chosen);
//            if($this->isChosen($plate->id, $chosen) === false)
//                $this->setSession($request,'plates',$plate->id);
//
//            $qtdPlates = $this->plateModel->getCountPlateByCategoryID($request->category);
//            $category = $this->getPlatesRegistered($request, $qtdPlates);
//            $titulo = $this->title;
//            $btn = $this->btn;
//            if(!empty($category) && $category['id'] > 0 && (int)$request->plate === (int)$plate->id)
//                return view('gamer.plateCreate', compact('category', 'titulo','btn'));
//
//            return view('gamer.plates', compact('plate'));
//        }

    }

    /** Se a Categoria e o prato for sim então eu adivinhei! */
    private function iguessed(Request $request):bool
    {
        if($request->answer == 1 && !empty($request->plate) && !empty($request->category) > 0) {
            if($request->session()->has('iguessed') === false) {
                $this->saveChosen($request, 'iguessed');
                $game = [
                    'category' => $request->category,
                    'plate' => $request->plate,
                    'answer' => $request->answer,
                    'typeFood' => ''
                ];
                $this->setSession($request,'iguessed', $game);
            }
        } else {
            $this->removeSessionChosen($request,'iguessed');
        }
        return ($request->answer == 1 && !empty($request->plate) && !empty($request->category));
    }

    /** Se for uma categoria mais não é o tipo que foi pensado */
    private function newPlate( Request $request): bool
    {
        if((int) $request->answer === 0 && !empty($request->plate)) {
            $dado = $this->plateModel->getCategoryByPlateID($request->plate)[0];
            $request->session()->put('category', (int) $dado->category_id);
            return true;
        }
        return false;
    }

    private function getPlatesByCategory(Request $request): object
    {
        return $this->plateModel->getPlateByCategoryID($request->category);
    }

    private function getPlatesRegistered(Request $request, int $qtdPlates): array
    {
        $category = [];
        if((int)$request->answer === 0 && (int)$request->plate > 0 && (int)$request->category > 0) {
            $chosen = $this->getSession($request, 'plates');
            if(count($chosen) === $qtdPlates) {
                $category = [
                    'answer' => $request->answer,
                    'id' => $request->category
                ];
                return $category;
            }
        }
        return $category;
    }

//    private function choosePlate(Request $request, int $qtdPlates, object $plate): object
//    {
//        if ((int)$request->category === 1 && (int)$request->answer === 1) {
//            $plate = $this->saveChosen($request);
//            return $plate;
//        }
//
//        if(isset($request->plate) && $qtdPlates != (int)$request->plate) {
//            $chosen = $this->getSession($request,'plates');
//            $plates = $this->plateModel->getPlatesByCategoryByNotIn($request->category,$chosen)[0];
//            if($this->isChosen($plates->id, $chosen)) {
//                $obj = new $this->plateModel;
//                $obj->id = '';
//                return $obj;
//            }
//            if(empty($chosen)) {
//                $this->setSession($request, 'plates',$plate->id);
//            } elseif(!empty($plates)) {
//                $plate = $plates;
//                if($this->isChosen($plate->id,$chosen) == false) {
//                    $this->setSession($request, 'plates',$plate->id);
//                }
//            }
//            return $plate;
//        }
//        return $plate;
//    }

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
        $request->session()->push($name, $value);
        return true;
    }

    private function removeSessionChosen(Request $request, string $name): void
    {
        $request->session()->forget($name);
    }

}
