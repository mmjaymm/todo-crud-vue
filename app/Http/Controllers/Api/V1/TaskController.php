<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Repositories\Task\TaskInterface;
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
}
