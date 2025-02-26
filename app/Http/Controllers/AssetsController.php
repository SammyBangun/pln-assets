<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetsController extends Controller
{
    public function show($type)
    {
        // Ambil data berdasarkan type
        $items = Asset::where('type', $type)->get();

        // Kirim data ke Vue melalui Inertia
        return Inertia::render('Assets/Item', [
            'items' => $items,
            'type' => $type
        ]);
    }
}
