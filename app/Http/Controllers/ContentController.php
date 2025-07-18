<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        return Content::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'allowed_countries' => 'nullable|array',
            'allowed_countries.*' => 'string|size:2',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'is_active' => 'boolean'
        ]);

        $content = Content::create($validated);

        return response()->json($content, 201);
    }

    public function show(Content $content)
    {
        return $content;
    }

    public function update(Request $request, Content $content)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
            'allowed_countries' => 'sometimes|array',
            'allowed_countries.*' => 'string|size:2',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'is_active' => 'sometimes|boolean'
        ]);

        $content->update($validated);

        return response()->json($content);
    }

    public function destroy(Content $content)
    {
        $content->delete();

        return response()->json(null, 204);
    }
    public function publicIndex(Request $request)
    {
        $countryCode = $request->attributes->get('country_code');
        return Content::where('is_active', 1)
            ->where(function ($query) use ($countryCode) {
                $query->whereNull('allowed_countries')
                    ->orWhereJsonContains('allowed_countries', $countryCode);
            })
            ->where(function ($query) {
                $query->whereNull('start_time')
                    ->orWhere('start_time', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_time')
                    ->orWhere('end_time', '>=', now());
            })
            ->get();
    }
}
