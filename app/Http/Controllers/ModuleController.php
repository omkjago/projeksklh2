<?php

// app/Http/Controllers/ModuleController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::all();
        return view('elearning.materials.index', compact('modules'));
    }

    public function create()
    {
        return view('elearning.materials.create');
    }

    public function store(Request $request)
    {
        $module = Module::create($request->all());
        return redirect()->route('elearning.materials.index');
    }

    public function show($id)
    {
        $module = Module::findOrFail($id);
        return view('elearning.materials.show', compact('module'));
    }

    public function edit($id)
    {
        $module = Module::findOrFail($id);
        return view('elearning.materials.edit', compact('module'));
    }

    public function update(Request $request, $id)
    {
        $module = Module::findOrFail($id);
        $module->update($request->all());
        return redirect()->route('elearning.materials.index');
    }

    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();
        return redirect()->route('elearning.materials.index');
    }
}
