<?php

namespace Tests\Feature\Api\Admin;

use App\Models\Brand;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilities\UserTestCase;

class BrandControllerTest extends UserTestCase
{
    use RefreshDatabase;

    public function testRouteReturnsAPaginatedList(): void
    {
        Brand::factory()->count(15)->create();

        $response = $this->actingAs($this->adminUser)->get(route('admin.api.brands.index'));

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

    public function testCreatesANewBrand(): void
    {
        $data = Brand::factory()->make()->toArray();

        $response = $this->actingAs($this->adminUser)->post(route('admin.api.brands.store'), $data);

        $response->assertOk();
        $response->assertJson(['message' => 'Brand created successfully']);
        $this->assertDatabaseHas('brands', $data);
    }

    public function testUpdatesAnExistingBrand(): void
    {
        $brand = Brand::factory()->create();
        $data = ['name' => 'Updated Name'];

        $response = $this->actingAs($this->adminUser)->patch(route('admin.api.brands.update', $brand->getAttribute('id')), $data);

        $response->assertOk();
        $response->assertJson(['message' => 'Brand updated successfully']);
        $this->assertDatabaseHas('brands', array_merge(['id' => $brand->getAttribute('id')], $data));
    }

    public function testReturnsErrorIfDataIsInvalid()
    {
        $data = ['name' => ''];

        $response = $this->actingAs($this->adminUser)->post(route('admin.api.brands.store'), $data);

        $response->assertFound();
        $response->assertSessionHasErrors(['name']);
    }
}
