<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $featured = News::published()
            ->featured()
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        $latest = News::published()
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->take(12)
            ->get();

        $categories = Category::active()
            ->whereHas('news', function ($query) {
                $query->published();
            })
            ->withCount(['news' => function ($query) {
                $query->published();
            }])
            ->get();

        return view('public.home', compact('featured', 'latest', 'categories'));
    }

    public function show(string $slug)
    {
        $news = News::published()
            ->where('slug', $slug)
            ->with(['category', 'author'])
            ->firstOrFail();

        $news->incrementViews();

        $related = News::published()
            ->where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->with('category')
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        return view('public.show', compact('news', 'related'));
    }

    public function category(string $slug)
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();

        $news = News::published()
            ->where('category_id', $category->id)
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = Category::active()
            ->whereHas('news', function ($query) {
                $query->published();
            })
            ->withCount(['news' => function ($query) {
                $query->published();
            }])
            ->get();

        return view('public.category', compact('category', 'news', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $results = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);

        if (strlen(trim($query)) >= 2) {
            $results = News::published()
                ->search(trim($query))
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->paginate(12)
                ->appends(['q' => $query]);
        }

        $news = $results;

        return view('public.search', compact('results', 'news', 'query'));
    }
}
