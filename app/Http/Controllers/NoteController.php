<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // Return all notes ordered by newest first
    public function index()
    {
        return response()->json(Note::orderBy('created_at', 'desc')->get());
    }

    // Store a new note
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'content' => 'required|string',
            'color' => 'nullable|string',
        ]);

        $note = Note::create($data);

        return response()->json($note);
    }

    // Update an existing note
    public function update(Request $request, Note $note)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'content' => 'required|string',
            'color' => 'nullable|string',
        ]);

        $note->update($data);

        return response()->json($note);
    }

    // Delete a note
    public function destroy(Note $note)
    {
        $note->delete();

        return response()->json(['message' => 'Note deleted']);
    }
}
