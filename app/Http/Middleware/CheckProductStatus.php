<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProductStatus
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Product|null $product */
        $product = Product::query()->find($request->route('product'))->first();

        if (!$product || !$product->getRawOriginal('status')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
