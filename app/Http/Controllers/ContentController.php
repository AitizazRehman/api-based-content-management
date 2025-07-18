<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        return Content::all();
    }

    public function store(StoreContentRequest $request)
    {
        $content = Content::create($request->validated());

        return response()->json($content, 201);
    }

    public function show(Content $content)
    {
        return $content;
    }

    public function update(UpdateContentRequest $request, Content $content)
    {
        $content->update($request->validated());

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
