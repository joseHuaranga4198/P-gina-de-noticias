<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['category', 'author'])->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        $news = $query->paginate(15)->appends($request->query());
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.news.index', compact('news', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:2048'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'is_featured' => ['boolean'],
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no debe superar 255 caracteres.',
            'excerpt.required' => 'El resumen es obligatorio.',
            'content.required' => 'El contenido es obligatorio.',
            'category_id.required' => 'Seleccione una categoría.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser JPEG, PNG, WebP o GIF.',
            'image.max' => 'La imagen no debe superar 2 MB.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        News::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Noticia creada exitosamente.');
    }

    public function edit(News $news)
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:2048'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published', 'unpublished'])],
            'is_featured' => ['boolean'],
        ], [
            'title.required' => 'El título es obligatorio.',
            'excerpt.required' => 'El resumen es obligatorio.',
            'content.required' => 'El contenido es obligatorio.',
            'category_id.required' => 'Seleccione una categoría.',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        // Handle publish state transitions
        if ($validated['status'] === 'published' && $news->status !== 'published') {
            $validated['published_at'] = now();
        } elseif ($validated['status'] !== 'published') {
            $validated['published_at'] = null;
        }

        // Update slug if title changed
        if ($news->title !== $validated['title']) {
            $base = Str::slug($validated['title']);
            $slug = $base;
            $count = 1;
            while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
                $slug = $base . '-' . $count++;
            }
            $validated['slug'] = $slug;
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Noticia actualizada exitosamente.');
    }

    public function destroy(News $news)
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Noticia eliminada exitosamente.');
    }

    public function toggleStatus(News $news)
    {
        if ($news->status === 'published') {
            $news->update([
                'status' => 'unpublished',
                'published_at' => null,
            ]);
            $message = 'Noticia despublicada.';
        } else {
            $news->update([
                'status' => 'published',
                'published_at' => now(),
            ]);
            $message = 'Noticia publicada.';
        }

        return redirect()->back()->with('success', $message);
    }
}
