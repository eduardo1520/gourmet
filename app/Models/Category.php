<?php

namespace App\Models;

use App\Models\Entities\CategoryEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected  $fillable = ['id','name','id_pai'];
    protected  $hidden = ['created_at', 'update_at'];
    protected $table = 'categories';


    public function getFirstCategory()
    {
        return Category::get()->first();
    }

    public function getNextCategory($categorias)
    {
        return Category::whereNotIn('id',$categorias)->whereNotIn('id_pai',$categorias)->first();
    }

    public function getCategoryChild(int $categoria):?object
    {
         return Category::where('id_pai',$categoria)->first();
    }

    public function addCategory(array $categoria): int
    {
        try{
            $lastID = Category::create($categoria);
            return $lastID->id;
        } catch(\Exception $exception) {
            die($exception->getMessage());
        }
    }

    public function getCategoryNotIn(array $categories): object
    {
        $encontrou = Category::whereNotIn('id_pai',$categories)->exists();

        if($encontrou == true)
            return collect(Category::whereNotIn('id', $categories)->whereNotIn('id_pai',$categories)->get());
        return collect(Category::where('id', $categories)->whereNotIn('id',$categories)->get());
    }

}
