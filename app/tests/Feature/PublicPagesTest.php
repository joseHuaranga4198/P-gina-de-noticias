<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private function seedBasicData(): array
    {
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'Ciencia', 'slug' => 'ciencia', 'is_active' => true]);
        $news = News::factory()->published()->create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'title' => 'Avance en investigación biomédica',
            'slug' => 'avance-en-investigacion-biomedica',
            'published_at' => now()->subHour(),
        ]);
        return compact('user', 'category', 'news');
    }

    public function test_home_page_loads_successfully(): void
    {
        $this->seedBasicData();
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Portal de Noticias');
    }

    public function test_home_page_shows_published_news(): void
    {
        $data = $this->seedBasicData();
        $response = $this->get('/');
        $response->assertSee($data['news']->title);
    }

    public function test_home_page_does_not_show_draft_news(): void
    {
        $data = $this->seedBasicData();
        News::factory()->draft()->create([
            'category_id' => $data['category']->id,
            'user_id' => $data['user']->id,
            'title' => 'Borrador Confidencial Oculto',
        ]);

        $response = $this->get('/');
        $response->assertDontSee('Borrador Confidencial Oculto');
    }

    public function test_news_detail_page_loads(): void
    {
        $data = $this->seedBasicData();
        $response = $this->get('/noticia/' . $data['news']->slug);
        $response->assertStatus(200);
        $response->assertSee($data['news']->title);
    }

    public function test_news_detail_increments_views(): void
    {
        $data = $this->seedBasicData();
        $initialViews = $data['news']->views;

        $this->get('/noticia/' . $data['news']->slug);

        $this->assertEquals($initialViews + 1, $data['news']->fresh()->views);
    }

    public function test_nonexistent_news_returns_404(): void
    {
        $response = $this->get('/noticia/slug-inexistente-12345');
        $response->assertStatus(404);
    }

    public function test_category_page_loads(): void
    {
        $data = $this->seedBasicData();
        $response = $this->get('/categoria/' . $data['category']->slug);
        $response->assertStatus(200);
        $response->assertSee($data['category']->name);
    }

    public function test_category_page_shows_only_category_news(): void
    {
        $data = $this->seedBasicData();
        $otherCat = Category::factory()->create(['name' => 'Deportes', 'slug' => 'deportes']);
        News::factory()->published()->create([
            'category_id' => $otherCat->id,
            'user_id' => $data['user']->id,
            'title' => 'Noticia Exclusiva de Deportes',
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get('/categoria/' . $data['category']->slug);
        $response->assertSee($data['news']->title);
        $response->assertDontSee('Noticia Exclusiva de Deportes');
    }

    public function test_nonexistent_category_returns_404(): void
    {
        $response = $this->get('/categoria/categoria-inexistente');
        $response->assertStatus(404);
    }

    public function test_search_page_loads(): void
    {
        $response = $this->get('/buscar');
        $response->assertStatus(200);
    }

    public function test_search_finds_matching_news(): void
    {
        $data = $this->seedBasicData();
        $response = $this->get('/buscar?q=biomédica');
        $response->assertStatus(200);
        $response->assertSee($data['news']->title);
    }

    public function test_search_does_not_find_draft_news(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        News::factory()->draft()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Borrador Secreto de Prueba',
        ]);

        $response = $this->get('/buscar?q=Secreto');
        $response->assertDontSee('Borrador Secreto de Prueba');
    }

    public function test_search_with_short_query_returns_no_results(): void
    {
        $response = $this->get('/buscar?q=a');
        $response->assertStatus(200);
    }
}
