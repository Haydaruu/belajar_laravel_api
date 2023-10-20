<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Resources\Task\TaskIndexResource;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $task = TaskIndexResource::collection(
            Task::query()->with('user')->paginate(5)
        );

        return response()->json([
            'status' => true,
            'message' => 'Berhasil get data pengguna',
            'data' => $task->items(),
            'meta' => [
                'total' => $task->total(),
                'per_page' => $task->perPage(),
                'current_page' => $task->currentPage(),
                'last_page' => $task->lastPage(),
                'from' => $task->firstItem(),
                'to' => $task->lastItem(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        $file = $request->file('upload_file')->getClientOriginalExtension();

        if (in_array($file, ['jpg', 'png', 'jpeg'])) {
            $upload_file = $request->file('upload_file')->store('public/images');
        } else {
            $upload_file = $request->file('upload_file')->store('public/files');
        }
        $task = Task::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'upload_file' => $upload_file,
            'user_id' => auth()->user()->id,
        ]);

        $task = Task::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambahkan task',
            'data' => new TaskResource($task),

        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan task',
            'data' => new TaskResource($task),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $file = $request->file('upload_file')->getClientOriginalExtension();

        $upload_file = $task->upload_file;
        if ($request->hasFile('upload_file')) {
            \File::delete(storage_path("app/{$upload_file}"));
            $file = $request->file('upload_file')->getClientOriginalExtension();

            if (in_array($file, ['jpg', 'png', 'jpeg'])) {
                $upload_file = $request->file('upload_file')->store('public/images');
            } else {
                $upload_file = $request->file('upload_file')->store('public/files');
            }
        }

        $task->update([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'upload_file' => $upload_file,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil mengubah task',
            'data' => new TaskResource($task),

        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        \File::delete(storage_path("app/{$task->upload_file}"));

        $task->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
