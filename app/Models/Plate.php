<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plate extends Model
{
    use HasFactory;

    protected $fillable = ['id','name','category_id'];
    protected $hidden = ['created_at','updated_at'];

    protected $table = 'plates';

    public function getPlateByCategoryID(int $id): object
    {
        $listPlates = Plate::select('plates.id', 'plates.name','category_id')
        ->where('plates.category_id', $id)->get();
        return count($listPlates) == 1 ? $listPlates[0] : $listPlates;
    }

    public function getCategoryByPlateID($plate): object
    {
        $category = Plate::select('plates.category_id')->where('plates.id',$plate)->get();
        return $category;
    }

    public function addPlate(array $plate): void
    {
        try {
            Plate::create($plate);
        } catch(\Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * O retorno desse método precisa ser um array devido a regra de negócio aplicada no retorno das informações.
     *
     */
    public function getPlatesByCategoryNotIn(int $category, array $plate): array
    {
        $encontrou = Plate::where('category_id', $category)->whereNotIn('id',$plate)->exists();
        if($encontrou == true)
            return (Plate::where('category_id', $category)->whereNotIn('id',$plate)->get()->toArray())[0];
        return [];
    }

    public function getPlates()
    {
        return Plate::all();
    }

    public function getPlateCategoryByName(int $category, string $name): bool
    {
        $plate = Plate::where('category_id', $category)->where('name',$name)->get();
        return (count($plate) > 0 && $plate->id > 0);
    }

    public function getCountPlateByCategoryID(int $id): int
    {
        return count($this->getPlateByCategoryID($id));
    }

    public function getOnePlatyCategoryID(int $category, int $plate): object
    {
        return Plate::where('category_id',$category)->where('id',$plate)->get();
    }

    public function getTypePlateByCategoryID(int $plate, int $category): array
    {


        $plate = (Plate::select('plates.id', 'plates.name','plates.category_id')->where('plates.id', $plate)->get())[0];

        $category = Category::select('categories.id','categories.name')->where('categories.id_pai',$category)->groupby('categories.id')->get();
        dd([$plate, $category]);

        $dado = [];
        foreach ($category as $idx => $c) {
            if((int)$plate->id === (int)$c->id) {
                $dado = [
                    'name' => $c->name,
                    'id' => $plate->id,
                    'category_id' => $c->id,
                    'plate' => $plate->name
                ];
                break;
            }
        }

        return $dado;
    }

    public function getNextPlateByCategoryID(int $category): object
    {
        $plate = Plate::select('plates.id','plates.name','plates.category_id')->whereNotIn('category_id', [$category])->get();
        return empty($plate->id) ? $plate[0] : $plate;
    }

}
