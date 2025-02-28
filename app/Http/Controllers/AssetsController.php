<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Asset;

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

    public function latest($serial_number)
    {
        // Ambil data berdasarkan serial_number
        $items = Asset::where('serial_number', $serial_number)->get();

        // Kirim data ke Vue melalui Inertia
        return Inertia::render('Assets/Item', [
            'items' => $items
        ]);
    }
}
