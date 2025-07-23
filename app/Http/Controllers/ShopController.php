<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->where('status', 'active')

            /* ---------- BÚSQUEDA ---------- */
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = trim($request->search);

                // ➊ Full-Text si el término tiene ≥3 caracteres
                if (mb_strlen($term) >= 3) {
                    $q->whereFullText(['name', 'slug'], $term);
                }
                // ➋ LIKE “burdo” para términos cortos (ej. “XS”)
                else {
                    $like = '%' . str_replace(' ', '%', $term) . '%';
                    $q->where(function ($q) use ($like) {
                        $q->where('name',  'like', $like)
                        ->orWhere('slug', 'like', $like);
                    });
                }
            })

            /* ---------- FILTRO POR CATEGORÍA ---------- */
            ->when($request->filled('category'), function ($q) use ($request) {
                $slug = $request->category;                         // ej. “streetwear”
                $q->whereHas('categories', fn ($c) =>               // INNER JOIN implícito
                    $c->where('slug', $slug));
            })

            /* ---------- Eager-loads ---------- */
            ->with([
                'mainImage:id,imageable_id,src',
                'variants:id,product_id,price',
                'categories:id,name,slug'            // útil si luego quieres mostrar chips
            ])

            ->latest()
            ->paginate(12)
            ->withQueryString();                     // mantiene ?search y ?category al paginar

        /* ---------- Precio calculado ---------- */
        $products->getCollection()->transform(function ($product) {
            $product->display_price = $product->variants->min('price')
                                    ?: $product->base_price;
            return $product;
        });

        return view('pages.shop.shop', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load([
            'images', 'variants.size', 'variants.color', 'categories:id,name,slug',
        ]);

        $displayPrice = $product->variants->min('price') ?: $product->base_price;

        return view('pages.product.product', compact('product', 'displayPrice'));
    }
}
