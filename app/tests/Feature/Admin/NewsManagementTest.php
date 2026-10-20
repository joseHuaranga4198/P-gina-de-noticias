<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->category = Category::factory()->create();
    }

    public function test_admin_can_view_news_list(): void
    {
        News::factory()->count(3)->create([
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.news.index'));
        $response->assertStatus(200);
        $response->assertSee('Gestión de Noticias');
    }

    public function test_admin_can_view_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.news.create'));
        $response->assertStatus(200);
        $response->assertSee('Crear Nueva Noticia');
    }

    public function test_admin_can_create_news(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => 'Nueva Noticia de Prueba',
            'excerpt' => 'Resumen breve de la noticia para el test.',
            'content' => '<p>Contenido detallado de la noticia de prueba.</p>',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', ['title' => 'Nueva Noticia de Prueba']);
    }

    public function test_admin_can_create_published_news(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => 'Noticia Publicada de Prueba',
            'excerpt' => 'Resumen para publicación inmediata.',
            'content' => '<p>Contenido publicado completo.</p>',
            'category_id' => $this->category->id,
            'status' => 'published',
        ]);

        $news = News::where('title', 'Noticia Publicada de Prueba')->first();
        $this->assertNotNull($news);
        $this->assertEquals('published', $news->status);
        $this->assertNotNull($news->published_at);
    }

    public function test_create_news_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), []);
        $response->assertSessionHasErrors(['title', 'excerpt', 'content', 'category_id', 'status']);
    }

    public function test_create_news_validates_title_max_length(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => str_repeat('A', 256),
            'excerpt' => 'Resumen válido',
            'content' => 'Contenido válido',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_create_news_validates_image_type(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => 'Test',
            'excerpt' => 'Test',
            'content' => 'Test',
            'category_id' => $this->category->id,
            'status' => 'draft',
            'image' => UploadedFile::fake()->create('documento.pdf', 100),
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_create_news_validates_image_size(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => 'Test',
            'excerpt' => 'Test',
            'content' => 'Test',
            'category_id' => $this->category->id,
            'status' => 'draft',
            'image' => UploadedFile::fake()->create('pesada.jpg', 3000, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_admin_can_upload_image_with_news(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => 'Noticia Con Imagen Adjunta',
            'excerpt' => 'Resumen de prueba con imagen',
            'content' => 'Contenido completo',
            'category_id' => $this->category->id,
            'status' => 'draft',
            'image' => UploadedFile::fake()->create('portada.jpg', 500, 'image/jpeg'),
            'image_alt' => 'Descripción de la portada',
        ]);

        $news = News::where('title', 'Noticia Con Imagen Adjunta')->first();
        $this->assertNotNull($news);
        $this->assertNotNull($news->image);
        Storage::disk('public')->assertExists($news->image);
    }

    public function test_admin_can_edit_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.news.edit', $news));
        $response->assertStatus(200);
        $response->assertSee('Editar Noticia');
    }

    public function test_admin_can_update_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
            'title' => 'Título Original',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.news.update', $news), [
            'title' => 'Título Actualizado Modificado',
            'excerpt' => 'Resumen actualizado',
            'content' => 'Contenido actualizado',
            'category_id' => $this->category->id,
            'status' => 'draft',
        ]);

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', ['title' => 'Título Actualizado Modificado']);
    }

    public function test_admin_can_delete_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.news.destroy', $news));

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    public function test_admin_can_toggle_news_status(): void
    {
        $news = News::factory()->published()->create([
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.news.toggle-status', $news));

        $this->assertEquals('unpublished', $news->fresh()->status);
    }

    public function test_admin_can_publish_unpublished_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
            'status' => 'unpublished',
            'published_at' => null,
        ]);

        $this->actingAs($this->admin)->patch(route('admin.news.toggle-status', $news));

        $fresh = $news->fresh();
        $this->assertEquals('published', $fresh->status);
        $this->assertNotNull($fresh->published_at);
    }

    public function test_news_list_can_filter_by_status(): void
    {
        News::factory()->published()->create([
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
        ]);
        News::factory()->draft()->create([
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.news.index', ['status' => 'draft']));
        $response->assertStatus(200);
    }

    public function test_news_list_can_filter_by_category(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.news.index', ['category' => $this->category->id]));
        $response->assertStatus(200);
    }
}
