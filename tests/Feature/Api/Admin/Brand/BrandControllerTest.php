<?php

namespace Tests\Feature\Api\Admin\Brand;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilities\UserTestCase;

class BrandControllerTest extends UserTestCase
{
    use RefreshDatabase;

    public function testRouteReturnsAPaginatedList(): void
    {
        Brand::factory()->count(15)->create();

        $response = $this->actingAs($this->adminUser)->get(route('api.admin.brands.index'));

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

        $response = $this->actingAs($this->adminUser)->post(route('api.admin.brands.store'), $data);

        $response->assertOk();
        $this->assertDatabaseHas('brands', $data);
    }

    public function testUpdatesAnExistingBrand(): void
    {
        $brand = Brand::factory()->create();
        $data = ['name' => 'Updated Name'];

        $response = $this->actingAs($this->adminUser)->patch(route('api.admin.brands.update', $brand->getAttribute('id')), $data);

        $response->assertOk();
        $this->assertDatabaseHas('brands', array_merge(['id' => $brand->getAttribute('id')], $data));
    }

    public function testReturnsErrorIfDataIsInvalid()
    {
        $data = ['name' => ''];

        $response = $this->actingAs($this->adminUser)->post(route('api.admin.brands.store'), $data);

        $response->assertFound();
        $response->assertSessionHasErrors(['name']);
    }
}
