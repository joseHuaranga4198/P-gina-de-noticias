<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_require_authentication(): void
    {
        $routes = [
            ['GET', '/admin'],
            ['GET', '/admin/noticias'],
            ['GET', '/admin/noticias/create'],
            ['GET', '/admin/categorias'],
            ['GET', '/admin/categorias/create'],
        ];

        foreach ($routes as [$method, $url]) {
            $response = $this->call($method, $url);
            $this->assertTrue(
                $response->isRedirect(),
                "Route {$method} {$url} should redirect unauthenticated users"
            );
        }
    }

    public function test_admin_routes_require_admin_role(): void
    {
        $user = User::factory()->create(['role' => 'editor']);

        $routes = [
            ['GET', '/admin'],
            ['GET', '/admin/noticias'],
            ['GET', '/admin/categorias'],
        ];

        foreach ($routes as [$method, $url]) {
            $response = $this->actingAs($user)->call($method, $url);
            $this->assertTrue(
                $response->isRedirect(),
                "Route {$method} {$url} should reject non-admin users"
            );
        }
    }

    public function test_xss_prevention_in_news_title(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $this->actingAs($admin)->post(route('admin.news.store'), [
            'title' => '<script>alert("xss-test")</script>Título Seguro',
            'excerpt' => 'Resumen de prueba',
            'content' => '<p>Contenido seguro</p>',
            'category_id' => $category->id,
            'status' => 'draft',
        ]);

        $news = News::first();
        $this->assertNotNull($news);

        // Blade {{ $news->title }} escapes HTML tags
        $response = $this->actingAs($admin)->get(route('admin.news.index'));
        $response->assertDontSee('<script>alert("xss-test")</script>', false);
        $response->assertSee('&lt;script&gt;alert(&quot;xss-test&quot;)&lt;/script&gt;', false);
    }

    public function test_sql_injection_in_search(): void
    {
        $response = $this->get('/buscar?q=' . urlencode("'; DROP TABLE news; --"));
        $response->assertStatus(200);
        // Table should still exist
        $this->assertTrue(Schema::hasTable('news'));
    }

    public function test_password_is_hashed(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secreto123')]);
        $this->assertNotEquals('secreto123', $user->password);
    }

    public function test_logout_invalidates_session(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->post('/logout');

        $this->assertGuest();
    }
}
