<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class MusicManagementController extends Controller
{
    public function list()
    {
        // Assuming you're using Laravel's built-in authentication
        if (auth()->check() && auth()->user()->role == 1) {
            // Lấy user_id của người dùng hiện tại
            $userId = Auth::id();

            // Lấy những bản ghi nhạc chỉ từ user_id hiện tại
            $musics = Music::where('user_id', $userId)->get();

            return view('music.list', ['musics' => $musics]);
        } else {
            return abort(403, 'Unauthorized action. You must be an admin.');
        }
    }



    // Edit
    public function edit($id)
    {
        $userId = auth()->user()->id;

        $music = Music::find($id);

        if ($music) {
            // Check if the user_id of the record matches the id of the current user
            if ($music->user_id === $userId) {
                // The record exists
                return view('music.edit', ['music' => $music]);
            } else {
                // The record does not belong to the current user
                return redirect()->route('music.list')->with('error', 'Permission denied');
            }
        } else {
            // The record does not exist
            return redirect()->route('music.list')->with('error', 'Music not found');
        }
    }



    // Update
    public function update(Request $request, $id)
    {
        $music = Music::find($id);

        // Check if the user_id of the record matches the id of the current user
        if ($music && $music->user_id === auth()->user()->id) {
            $request->validate([
                'title' => 'required',
                'artist' => 'required',
                'genre' => 'required',
                'file' => 'nullable|mimes:mp3',
                'image' => 'nullable|mimes:jpg,png,webp',
            ]);

            $music->update([
                'title' => $request->input('title'),
                'artist' => $request->input('artist'),
                'genre' => $request->input('genre'),
            ]);

            // Handle file and image upload if provided
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('music', $fileName);
                $music->update(['file_path' => $fileName]);
            }

            if ($request->hasFile('image')) {
                $file1 = $request->file('image');
                $fileName1 = time() . '.' . $file1->getClientOriginalExtension();
                $file1->storeAs('image', $fileName1);
                $music->update(['img_path' => $fileName1]);
            }


            return redirect()->route("music.edit", ['id' => $id])->with('success', 'Music updated successfully.');
        } else {
            // The record does not exist or does not belong to the current user
            return redirect()->route('music.list')->with('error', 'Permission denied');
        }
    }




    //Delete
    public function destroy($id)
    {
        // Tìm bản ghi âm nhạc cần xóa
        $music = Music::find($id);
        $userId = auth()->user()->id;

        if ($music) {

            // Kiểm tra xem user_id của bản ghi có khớp với id của người dùng hiện tại hay không
            if ($music->user_id === $userId) {

                // Trích xuất đường dẫn tệp từ bản ghi
                $music = Music::find($id);
                $filePath = $music->file_path;

                // Xóa tệp từ thư mục storage\app\music
                if (file_exists(storage_path('app/music/' . $filePath))) {
                    unlink(storage_path('app/music/' . $filePath));
                }

                // Sau đó, xóa bản ghi từ cơ sở dữ liệu
                $music->delete();
            } else {
                // Bản ghi không thuộc về người dùng hiện tại
                return redirect()->route('music.list')->with('error', 'Permission denied');
            }

            return redirect()->route('music.list')->with('success', 'Music deleted successfully.');
        } else {
            return redirect()->route('music.list')->with('error', 'Music not found.');
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
