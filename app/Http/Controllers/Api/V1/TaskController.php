<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Repositories\Task\TaskInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response as HttpResponseCode;

class TaskController extends Controller
{
    use ApiResponser;

    private $todo;

    public function __construct(TaskInterface $todoRepo)
    {
        $this->todo = $todoRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tasks = $this->todo->allTasks();
            return TaskResource::collection($tasks)
                ->additional([
                    'status' => 'Success',
                    'message' => 'Tasks Retrieved Successfully!',
                ])
                ->response()->setStatusCode(HttpResponseCode::HTTP_OK);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->all();

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $filename = Carbon::parse(now())->timestamp; //UTC
            $pathfile = $attachment->storeAs('attachments', $filename . '.' . $attachment->getClientOriginalExtension());
            $requestData['attachment'] = $pathfile;
        }

        try {
            $isSave = $this->todo->storeTask($requestData);
            return (new TaskResource($isSave))
                ->additional([
                    'status' => 'Success',
                    'message' => 'Task Inserted!',
                ])
                ->response()
                ->setStatusCode(HttpResponseCode::HTTP_CREATED);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $task = $this->todo->findTask($id);
            return (new TaskResource($task))
                ->additional([
                    'status' => 'Success',
                    'message' => 'Task Retrieved!',
                ])
                ->response()->setStatusCode(HttpResponseCode::HTTP_OK);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $isUpdated = $this->todo->updateTask($request->all(), $id);
            return $this->successResponse($isUpdated, "Task successfully Updated!", HttpResponseCode::HTTP_OK);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $isDestroy = $this->todo->destroyTask($id);
            return $this->successResponse($isDestroy, "Task Deleted!", HttpResponseCode::HTTP_OK);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getCode(), $ex->getMessage());
        }
    }
}
