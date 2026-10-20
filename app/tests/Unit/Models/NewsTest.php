<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    private function createNews(array $overrides = []): News
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();
        return News::factory()->create(array_merge([
            'category_id' => $category->id,
            'user_id' => $user->id,
        ], $overrides));
    }

    public function test_news_can_be_created(): void
    {
        $news = $this->createNews(['title' => 'Test News']);
        $this->assertDatabaseHas('news', ['title' => 'Test News']);
    }

    public function test_news_auto_generates_unique_slug(): void
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();

        $news1 = News::create([
            'title' => 'Same Title',
            'excerpt' => 'Excerpt 1',
            'content' => 'Content 1',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $news2 = News::create([
            'title' => 'Same Title',
            'excerpt' => 'Excerpt 2',
            'content' => 'Content 2',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $this->assertNotEquals($news1->slug, $news2->slug);
    }

    public function test_news_belongs_to_category(): void
    {
        $news = $this->createNews();
        $this->assertInstanceOf(Category::class, $news->category);
    }

    public function test_news_belongs_to_author(): void
    {
        $news = $this->createNews();
        $this->assertInstanceOf(User::class, $news->author);
    }

    public function test_published_scope(): void
    {
        $this->createNews(['status' => 'published', 'published_at' => now()->subHour()]);
        $this->createNews(['status' => 'draft', 'published_at' => null]);
        $this->createNews(['status' => 'unpublished', 'published_at' => null]);

        $this->assertCount(1, News::published()->get());
    }

    public function test_featured_scope(): void
    {
        $this->createNews(['is_featured' => true]);
        $this->createNews(['is_featured' => false]);

        $this->assertCount(1, News::featured()->get());
    }

    public function test_search_scope(): void
    {
        $this->createNews(['title' => 'Laravel Framework News']);
        $this->createNews(['title' => 'Python Updates']);

        $results = News::search('Laravel')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('Laravel Framework News', $results->first()->title);
    }

    public function test_search_scope_searches_excerpt(): void
    {
        $this->createNews(['title' => 'Other Title', 'excerpt' => 'This is about Laravel']);
        $results = News::search('Laravel')->get();
        $this->assertCount(1, $results);
    }

    public function test_search_scope_searches_content(): void
    {
        $this->createNews(['title' => 'Other', 'excerpt' => 'Other', 'content' => 'Deep content about Laravel']);
        $results = News::search('Laravel')->get();
        $this->assertCount(1, $results);
    }

    public function test_is_published_method(): void
    {
        $published = $this->createNews(['status' => 'published', 'published_at' => now()]);
        $draft = $this->createNews(['status' => 'draft', 'published_at' => null]);

        $this->assertTrue($published->isPublished());
        $this->assertFalse($draft->isPublished());
    }

    public function test_reading_time_attribute(): void
    {
        $content = implode(' ', array_fill(0, 200, 'palabra'));
        $news = $this->createNews(['content' => $content]);
        $this->assertEquals(1, $news->reading_time);

        $content = implode(' ', array_fill(0, 600, 'palabra'));
        $news = $this->createNews(['content' => $content]);
        $this->assertEquals(3, $news->reading_time);
    }

    public function test_increment_views(): void
    {
        $news = $this->createNews(['views' => 0]);
        $news->incrementViews();
        $this->assertEquals(1, $news->fresh()->views);
    }

    public function test_published_at_is_cast_to_datetime(): void
    {
        $news = $this->createNews(['published_at' => '2024-01-01 12:00:00']);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $news->published_at);
    }
}
