<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Logs
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::defaultOrder()->get()->toTree(); // 1 sola query
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $node = new Category($data);

        if ($request->filled('parent_id')) {
            // inserta como hijo
            $parent = Category::find($request->parent_id);
            $parent->appendNode($node);               
        } else {
            // inserta como raíz
            $node->saveAsRoot();
        }

        return response()->json($node);
    }

    public function update(Request $r, Category $category)
    {
        $r->validate(['name'=>'required','slug'=>"required|unique:categories,slug,$category->id"]);
        $category->update($r->only('name','slug'));
        return back()->with('success','Updated');
    }

    /* Eliminar (junto con sub-árbol) */
    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success','Deleted');
    }

    /* Re-ordenar drag-and-drop */
    public function reorder(Request $request)
    {
        $tree = $request->input('tree', []);

        if (empty($tree)) {
            return response()->json(['error'=>'Árbol vacío'], 422);
        }

        DB::transaction(fn () => Category::rebuildTree($tree));

        return response()->noContent();     // 204
    }
}
