<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Size, Color};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    /* ---------- LIST ---------- */
    public function index(Request $r)
    {
        $products = Product::query()
            ->when($r->search, fn ($q,$s) =>
                $q->whereFullText(['name','slug'],$s))
            ->with(['mainImage:id,imageable_id,src'])      // eager-load 1 imagen
            ->withCount('variants')                        // contador eficiente
            ->latest()->paginate(15)->withQueryString();


        return view('admin.products.index', compact('products'));
    }

    /* ---------- CREATE -------- */
    public function create()
    {
        return view('admin.products.create', $this->formData());
    }

    /* ---------- STORE  -------- */
    public function store(Request $r)
    {
        $data = $this->validated($r);

        DB::transaction(function () use ($data,&$product,$r) {
            $product = Product::create($data);
            $this->syncRelations($product,$data,$r,false);
        });

        return response()->json([
            'redirect' => route('admin.products.edit', $product)
        ], 201);
    }

    /* ---------- EDIT   -------- */
    public function edit(Product $product)
    {
        // 1. Cargamos relaciones que la vista necesita
        $product->load(['variants.size', 'variants.color', 'categories', 'images']);

        // 2. Aplanamos las variantes (sólo ids y datos primitivos)
        $variantsForJs = $product->variants->map(fn ($v) => [
            'id'       => $v->id,
            'size_id'  => $v->size_id,
            'color_id' => $v->color_id,   //  aquí viaja el color_id numérico
            'sku'      => $v->sku,
            'price'    => $v->price,
            'stock'    => $v->stock,
        ]);
        
        return view(
            'admin.products.create',
            array_merge(
                $this->formData($product),    // categorías, colores, etc.
                [
                    'product'        => $product,
                    'variantsForJs'  => $variantsForJs,
                ],
            ),
        );
    }

    /* ---------- UPDATE -------- */
    public function update(Request $r, Product $product)
    {
        $data = $this->validated($r,$product->id);

        DB::transaction(function () use ($product,$data,$r) {
            $product->update($data);
            $this->syncRelations($product,$data,$r,true);
        });

        return response()->json([
            'redirect' => route('admin.products.edit', $product)
        ]);
    }

    /* ---------- SHOW (read-only) ---- */
    public function show(Product $product)
    {
        $product->load(['variants.size','variants.color','categories','images']);

        return view('admin.products.create',
            $this->formData($product) + [
                'product'  => $product,
                'readonly' => true
            ]);
    }

    /* ---------- DESTROY -------- */
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            $this->deleteImages($product);
            $product->variants()->delete();
            $product->delete();                 // soft-delete
        });

        return back()->with('success','Producto eliminado');
    }

    private function formData(?Product $product = null): array
    {
        $ret = [
            'categoryTree'   => Category::defaultOrder()->get()->toTree(),
            'categoriesFlat' => Category::all(['id','name','parent_id']),
            'sizes'          => Size::orderBy('code')->get(),
            'colors'         => Color::orderBy('name')->get(),
            'statusOptions'  => ['draft'=>'Borrador','active'=>'Activo','archived'=>'Archivado'],
            'genderOptions'  => ['men'=>'Men','women'=>'Women','unisex'=>'Unisex'],
            // URLs de imágenes existentes para Alpine
            'existingImages' => $product
                ? $product->images->map(fn ($i) => [
                    'id'  => $i->id,
                    'url' => $i->url,
                ])
                : collect(),
        ];

        return $ret;
    }

    private function validated(Request $r, $ignore = null): array
    {
        if ($r->filled('variants') && is_string($r->variants)) {
            $r->merge(['variants' => json_decode($r->variants, true)]);
        }

        return $r->validate([
            'name'        => 'required|string|max:255',
            'slug'        => ['required','string','max:255',
                              Rule::unique('products','slug')->ignore($ignore)],
            'description' => 'required|string',
            'base_price'  => 'required|numeric|min:0',
            'status'      => ['required',Rule::in(['draft','active','archived'])],
            'gender_hint' => ['required',Rule::in(['men','women','unisex'])],

            'category_ids'   => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',

            'variants'                => 'required|array|min:1',
            'variants.*.size_id'      => 'required|exists:sizes,id',
            'variants.*.color_id'     => 'nullable|integer|exists:colors,id',
            'variants.*.sku'          => 'required|string',
            'variants.*.price'        => 'required|numeric|min:0',
            'variants.*.stock'        => 'required|integer|min:0',

            'images'   => 'nullable|array',          // opcional al editar
            'images.*' => 'file|image|max:10240',

            'deleted_image_ids'   => 'array',
            'deleted_image_ids.*' => 'integer|exists:product_images,id',
        ]);
    }

    private function syncRelations(Product $product,array $data,Request $r,bool $updating)
    {
        // Categorías
        $product->categories()->sync($data['category_ids']);

        // Variantes
        if ($updating) {
            $product->variants()->delete();
        }
        foreach ($data['variants'] as $v) {
            $product->variants()->create($v);
        }

        // Imágenes (nuevas)
        if ($r->filled('deleted_image_ids')) {
            foreach ($r->deleted_image_ids as $imgId) {
                $img = $product->images()->find($imgId);
                if ($img) {
                    Storage::disk('public')->delete($img->src);
                    $img->delete();
                }
            }
        }

        if ($r->hasFile('images')) {
            foreach ($r->file('images') as $index => $file) {
                $filename = $file->hashName('products');          // evita colisión
                $file->storePubliclyAs('', $filename, 'public');  // ya subido; ruta relativa

                $abs = Storage::disk('public')->path($filename);
                Image::read($abs)->scale(width:600)->save($abs, 80);

                $product->images()->create([
                    'src'      => $filename,
                    'alt'      => $product->name,
                    'position' => $index,
                ]);
            }
        }
    }

    /** Borra físicamente los ficheros */
    private function deleteImages(Product $product): void
    {
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->src);
        }
    }
}
