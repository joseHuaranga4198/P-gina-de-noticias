<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertSee('Gestión de Categorías');
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Nueva Categoría Test',
            'description' => 'Descripción de la categoría de prueba',
            'color' => '#FF5733',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Nueva Categoría Test']);
    }

    public function test_create_category_validates_required_name(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => '',
            'color' => '#FF5733',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_create_category_validates_unique_name(): void
    {
        Category::factory()->create(['name' => 'Categoría Repetida']);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Categoría Repetida',
            'color' => '#FF5733',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_create_category_validates_color_format(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Test Color Invalido',
            'color' => 'azul-no-hex',
        ]);

        $response->assertSessionHasErrors('color');
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Nombre Actualizado Test',
            'color' => '#123456',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Nombre Actualizado Test']);
    }

    public function test_admin_can_delete_empty_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_category_with_news(): void
    {
        $category = Category::factory()->create();
        News::factory()->create([
            'category_id' => $category->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_update_category_allows_same_name_for_self(): void
    {
        $category = Category::factory()->create(['name' => 'Nombre Propio']);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Nombre Propio',
            'color' => '#654321',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHasNoErrors();
    }
}
