<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{

    //Show all songs
    public function index()
    {
        // Lấy danh sách bài hát từ cơ sở dữ liệu
        $songs = Music::all();

        return view('dashboard', ['songs' => $songs]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $songs = Music::where('title', 'like', "%$query%")
            ->orWhere('artist', 'like', "%$query%")
            ->orWhere('genre', 'like', "%$query%")
            ->get();

        return view('dashboard', ['songs' => $songs]);
    }


    //Get mp3 files
    public function streamMusic($filename)
    {
        $path = storage_path('app/music/' . $filename);

        if (file_exists($path)) {
            $file = Storage::get('music/' . $filename);

            $response = Response::make($file, 200);
            $response->header('Content-Type', 'audio/mpeg');

            return $response;
        } else {
            return response('Audio file not found', 404);
        }
    }

    //Get img files
    public function showimg($filename)
    {
        $path = storage_path('app/image/' . $filename);

        if (file_exists($path)) {
            $file = Storage::get('image/' . $filename);

            // Determine the MIME type based on the file extension
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
            ];

            $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

            if (array_key_exists($fileExtension, $mimeTypes)) {
                $contentType = $mimeTypes[$fileExtension];
            } else {
                // Default to a generic image MIME type if the extension is not recognized
                $contentType = 'image/jpeg';
            }

            $response = Response::make($file, 200);
            $response->header('Content-Type', $contentType);

            return $response;
        } else {
            return response('Img file not found', 404);
        }
    }
}
