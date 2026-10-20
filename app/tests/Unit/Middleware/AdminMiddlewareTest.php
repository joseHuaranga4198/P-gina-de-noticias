<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_passes_middleware(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $request = Request::create('/admin', 'GET');
        $request->setUserResolver(fn() => $admin);

        $middleware = new AdminMiddleware();
        $response = $middleware->handle($request, fn($req) => response('OK'));

        $this->assertEquals('OK', $response->getContent());
    }

    public function test_non_admin_user_is_redirected(): void
    {
        $user = User::factory()->create(['role' => 'editor']);
        $this->actingAs($user);

        $request = Request::create('/admin', 'GET');
        $request->setUserResolver(fn() => $user);

        $middleware = new AdminMiddleware();
        $response = $middleware->handle($request, fn($req) => response('OK'));

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_unauthenticated_user_is_redirected(): void
    {
        $request = Request::create('/admin', 'GET');
        $request->setUserResolver(fn() => null);

        $middleware = new AdminMiddleware();
        $response = $middleware->handle($request, fn($req) => response('OK'));

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_non_admin_json_request_returns_403(): void
    {
        $user = User::factory()->create(['role' => 'editor']);

        $request = Request::create('/admin', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $request->setUserResolver(fn() => $user);

        $middleware = new AdminMiddleware();
        $response = $middleware->handle($request, fn($req) => response('OK'));

        $this->assertEquals(403, $response->getStatusCode());
    }
}
