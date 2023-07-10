<?php

namespace Tests\Feature\Api\Admin\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilities\UserTestCase;

class CategoryControllerTest extends UserTestCase
{
    use RefreshDatabase;

    public function testRouteReturnsAPaginatedList(): void
    {
        Category::factory()->count(15)->create();

        $response = $this->actingAs($this->adminUser)->get(route('api.admin.categories.index'));

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

        $response = $this->actingAs($this->adminUser)->post(route('api.admin.categories.store'), $data);

        $response->assertOk();
        $this->assertDatabaseHas('categories', $data);
    }


    public function testUpdatesAnExistingCategory(): void
    {
        $category = Category::factory()->create();
        $data = ['name' => 'Updated Name'];

        $response = $this->actingAs($this->adminUser)->patch(route('api.admin.categories.update', $category->getAttribute('id')), $data);

        $response->assertOk();
        $this->assertDatabaseHas('categories', array_merge(['id' => $category->getAttribute('id')], $data));
    }

    public function testReturnsErrorIfDataIsInvalid()
    {
        $data = ['name' => ''];

        $response = $this->actingAs($this->adminUser)->post(route('api.admin.categories.store'), $data);

        $response->assertFound();
        $response->assertSessionHasErrors(['name']);
    }
}
