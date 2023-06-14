<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Repositories\Task\TaskInterface;
use App\Http\Requests\StoreTaskRequest;
use App\Services\TaskService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
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
    public function store(StoreTaskRequest $request)
    {
        // dd($request->all());
        $requestData = (new TaskService())->uploadAttachment($request);

        if (!$requestData) {
            return $this->errorResponse(422, "Unable to upload the file!");
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
    public function update(StoreTaskRequest $request, int $id)
    {
        $request->request->remove('_method');
        $requestData = (new TaskService())->uploadAttachment($request);

        if (!$requestData) {
            return $this->errorResponse(422, "Unable to upload the file!");
        }

        try {
            $isUpdated = $this->todo->updateTask($requestData, $id);
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
