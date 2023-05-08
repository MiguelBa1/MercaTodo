<?php

namespace Tests\Feature\Api\Admin;

use App\Models\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilities\UserTestCase;

class CategoryControllerTest extends UserTestCase
{
    use RefreshDatabase;

    public function testRouteReturnsAPaginatedList(): void
    {
        Category::factory()->count(15)->create();

        $response = $this->actingAs($this->adminUser)->get(route('admin.api.categories.index'));

        $response->assertOk();
        $response->assertJsonCount(10, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                ]
            ]
        ]);
    }


    public function testCreatesANewCategory(): void
    {
        $data = Category::factory()->make()->toArray();

        $response = $this->actingAs($this->adminUser)->post(route('admin.api.categories.store'), $data);

        $response->assertOk();
        $response->assertJson(['message' => 'Category created successfully']);
        $this->assertDatabaseHas('categories', $data);
    }


    public function testUpdatesAnExistingCategory(): void
    {
        $category = Category::factory()->create();
        $data = ['name' => 'Updated Name'];

        $response = $this->actingAs($this->adminUser)->patch(route('admin.api.categories.update', $category->getAttribute('id')), $data);

        $response->assertOk();
        $response->assertJson(['message' => 'Category updated successfully']);
        $this->assertDatabaseHas('categories', array_merge(['id' => $category->getAttribute('id')], $data));
    }

    public function testReturnErrorIfDataIsInvalid()
    {
        $data = ['name' => ''];

        $response = $this->actingAs($this->adminUser)->post(route('admin.api.categories.store'), $data);

        $response->assertSessionHasErrors(['name']);
        $response->assertStatus(302);
    }
}
