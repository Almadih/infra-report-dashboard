<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Image $image)
    {
        if (! Storage::disk('private')->exists($image->path)) {
            abort(404, 'Image not found.');
        }
        $mimeType = Storage::disk('private')->mimeType($image->path);
        $file = Storage::disk('private')->get($image->path);

        return response($file, 200)->header('Content-Type', $mimeType)->header('Cache-Control', 'public, max-age=2592000');
    }
}
