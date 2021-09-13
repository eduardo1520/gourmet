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

    public function getPlateByCategoryID(int $id, $plate): object
    {
        $countPlates = $this->getCountPlateByCategoryID($id);
        $listPlates = Plate::select('plates.id', 'plates.name','category_id')
        ->where('plates.category_id', $id)->get();
        return $countPlates == 1 ? $this->getOnePlateCategoryID($id, $plate) : $listPlates;
    }

    public function getOnePlateCategoryID(int$id): object
    {
        return Plate::select('plates.id', 'plates.name','category_id')->where('plates.category_id', $id)->first();
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
    public function getPlatesByCategoryNotIn(int $category, array $plate): object
    {
        $encontrou = Plate::where('category_id', $category)->whereNotIn('id',$plate)->exists();
        if($encontrou == true)
            return (Plate::where('category_id', $category)->whereNotIn('id',$plate)->get());
        return (Plate::where('category_id', $category)->where('id',$plate)->get());
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
        return Plate::select('plates.id', 'plates.name','category_id')->where('plates.category_id', $id)->count();
    }

    public function getTypePlateByCategoryID(int $category, int $plate): object
    {
        return collect(\DB::table('plates As p')
            ->select('p.name As plate','c.id','c.name As category')
            ->join('categories As c','c.id','=','p.category_id')
            ->where('p.id',$plate)
            ->where('c.id',$category)
            ->get());
    }

    public function getNextPlateByCategoryID(int $category): object
    {
        $plate = Plate::select('plates.id','plates.name','plates.category_id')->whereNotIn('category_id', [$category])->get();
        return empty($plate->id) ? $plate[0] : $plate;
    }

}
