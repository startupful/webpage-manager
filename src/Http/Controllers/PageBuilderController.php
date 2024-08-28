<?php

namespace Startupful\WebpageManager\Http\Controllers;

use Startupful\WebpageManager\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PageBuilderController extends Controller
{
    public function edit($pageId)
    {
        $page = Page::findOrFail($pageId);
        
        \Log::info('Page loaded: ' . $page->id);
        \Log::info('Page title: ' . $page->title);
        
        return view('webpage-manager::page-builder', compact('page'));
    }

    public function update(Request $request, $pageId)
    {
        $page = Page::findOrFail($pageId);
        
        $validated = $request->validate([
            'content' => 'required',
        ]);

        // Ensure content is stored as a string
        $content = is_array($validated['content']) ? json_encode($validated['content']) : $validated['content'];
        
        $page->update(['content' => $content]);

        return redirect()->back()->with('success', 'Page content updated successfully.');
    }
}