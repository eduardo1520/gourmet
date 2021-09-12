<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use App\Models\CategoryModel;
use App\Models\Entities\CategoryEntity;

class CategoryTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_category()
    {

        $category = new CategoryModel();
        $entity = $category->getCategoryByID(1);
        $this->assertInstanceOf(CategoryEntity::class, $entity);
        $this->assertIsString($entity->name, 'Massa');
    }
}
