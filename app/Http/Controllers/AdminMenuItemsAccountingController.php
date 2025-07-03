<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminMenuItemsAccountingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'AdminMenuItemsAccountingController index() called.'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin_menu_items_accounting.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate and store logic here
        return redirect()->back()->with('success', 'Item created.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json([
            'message' => "Showing item with ID $id"
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('admin_menu_items_accounting.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate and update logic here
        return redirect()->back()->with('success', 'Item updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Delete logic here
        return response()->json([
            'message' => "Item with ID $id deleted."
        ]);
    }
}