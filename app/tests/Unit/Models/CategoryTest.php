<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created_with_valid_data(): void
    {
        $category = Category::create([
            'name' => 'Tecnología',
            'slug' => 'tecnologia',
            'description' => 'Noticias sobre avances tecnológicos e innovación',
            'color' => '#2563EB',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('categories', ['name' => 'Tecnología']);
        $this->assertEquals('tecnologia', $category->slug);
    }

    public function test_category_auto_generates_slug(): void
    {
        $category = Category::create([
            'name' => 'Ciencia y Salud',
            'description' => 'Test',
            'color' => '#000000',
        ]);

        $this->assertEquals('ciencia-y-salud', $category->slug);
    }

    public function test_category_has_many_news(): void
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();

        News::factory()->count(3)->create([
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $this->assertCount(3, $category->news);
    }

    public function test_category_published_news_scope(): void
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();

        News::factory()->published()->create(['category_id' => $category->id, 'user_id' => $user->id]);
        News::factory()->draft()->create(['category_id' => $category->id, 'user_id' => $user->id]);

        $this->assertCount(1, $category->publishedNews);
    }

    public function test_active_scope_filters_inactive_categories(): void
    {
        Category::factory()->create(['is_active' => true]);
        Category::factory()->create(['is_active' => false]);

        $this->assertCount(1, Category::active()->get());
    }

    public function test_is_active_is_cast_to_boolean(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        $this->assertIsBool($category->is_active);
    }
}
