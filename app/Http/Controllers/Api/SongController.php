<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;

class SongController extends Controller
{
    public function index()
    {
        $songs = Song::with('category')->get();
        return response()->json($songs);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'artist' => 'required',
            'duration' => 'required',
            'category_id' => 'required|exists:categories,id',
            'picture' => 'nullable|file|image|max:2048',
            'audio' => 'required|file|mimes:mp3',
        ]);

        $song = new Song();
        $song->title = $validatedData['title'];
        $song->artist = $validatedData['artist'];
        $song->duration = $validatedData['duration'];
        $song->category_id = $validatedData['category_id'];

        if ($request->hasFile('picture')) {
            $pictureFile = $request->file('picture');
            $pictureFileName = time() . '_' . $pictureFile->getClientOriginalName();
            $pictureFile->move(public_path('uploads').'/pictures/', $pictureFileName);
            $song->picture = 'uploads/pictures/' . $pictureFileName;
        }
        
        if ($request->hasFile('audio')) {
            $audioFile = $request->file('audio');
            $audioFileName = time() . '_' . $audioFile->getClientOriginalName();
            $audioFile->move(public_path('uploads').'/audio/', $audioFileName);
            $song->url = 'uploads/audio/' . $audioFileName;
        }

        $song->save();

        return response()->json($song, 201);
    }

}
