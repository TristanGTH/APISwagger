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

    public function update(Request $request, $id){


        $request->validate([
            'body' => 'required',
            'completed'=> 'required'
        ]);

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

        $updatedTask = Task::find($id);



        $updatedTask->body = $request->body;
        $updatedTask->completed = $request->completed;
        $updatedTask->save();

        return response()->json([
            'id' => $updatedTask->id,
            'created_at' => $updatedTask->created_at,
            'updated_at' => $updatedTask->updated_at,
            'body' => $updatedTask->body,
            'completed' => $updatedTask->completed,
            'user' => Auth()->user()
        ], 200);

    }

    public function showAll(Request $request){
        $tasks = Task::where('user_id', Auth()->user()->id)->orderby('updated_at', 'desc')->orderby('created_at', 'asc')->get();

        return response()->json([
            'tasks' => $tasks
        ], 201);
    }
}

