<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;



class MusicController extends Controller
{
    public function create()
    {
        // Assuming you're using Laravel's built-in authentication
        if (auth()->check() && auth()->user()->role == 1) {
            return view('music.create');
        } else {
            return abort(403, 'Unauthorized action. You must be an admin.');
        }
    }



    //insert song data to table
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'artist' => 'required',
            'genre' => 'required',
            'file' => 'required|mimes:mp3',
            'image' => 'required|mimes:jpg,png,webp',
        ]);

        $file = $request->file('file');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('music', $fileName);

        $file1 = $request->file('image');
        $fileName1 = time() . '.' . $file1->getClientOriginalExtension();
        $file1->storeAs('image', $fileName1);

        Music::create([
            'title' => $request->input('title'),
            'artist' => $request->input('artist'),
            'genre' => $request->input('genre'),
            'file_path' => $fileName,
            'img_path' => $fileName1,
            'user_id' => auth()->user()->id, //USER ID LINKING
        ]);

        return redirect('/music/create')->with('success', 'Music uploaded successfully');
    }
}
