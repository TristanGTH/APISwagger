<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create(Request $request)
    {

        $request->validate([
            'body' => 'required',
            'completed' => 'required',

        ]);


        $task = Task::create([
            'body' => $request->body,
            'completed' => $request->completed,
            "user_id"=> $request->user()->id
        ]);


        return response()->json([
            'task' => $task,
        ], 201);

    }

    public function delete(Request $request, $id){


        $ifTaskExists = Task::where('id', $id)->exists();

        if(!$ifTaskExists){
            return response()->json([
                'errors' => "La tâche n'existe pas."
            ], 404);
        }

        $task = Task::where('id', $id)->first();

        if($task->user_id !== $request->user()->id){
            return response()->json([
                'errors' => "Accès à la tâche non autorisé."
            ], 403);
        }

        Task::where('id', $id)->first()->delete();

        return response()->json([
            'id' => $task->id,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
            'body' => $task->body,
            'completed' => $task->completed,
            'user' => Auth()->user()
        ], 200);

    }
}

