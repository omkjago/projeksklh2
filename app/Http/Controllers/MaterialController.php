<?php

// app/Http/Controllers/MaterialController.php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\Module;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::all();
        return view('elearning.materials.index', compact('materials', 'module'));
    }

    public function create()
    {
        return view('elearning.materials.create'); // Ensure this view exists
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'link' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,docx,pptx,txt',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials');
        }

        Material::create([
            'title' => $request->title,
            'content' => $request->content,
            'link' => $request->link,
            'file_path' => $filePath,
        ]);

        return redirect()->route('materials.index')->with('success', 'Material added successfully!');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        if ($material->file_path) {
            Storage::delete($material->file_path);
        }
        $material->delete();
        return redirect()->route('materials.index')->with('success', 'Material deleted successfully!');
    }
}
