<?php

namespace App\Repositories\Task;

use App\Models\Task;
use App\Repositories\Task\TaskInterface;
use Symfony\Component\HttpFoundation\Response;

class TaskRepository implements TaskInterface
{
    public function allTasks($perPage = 10)
    {
        $tasks = Task::latest()->paginate($perPage);

        if (!$tasks) {
            throw new \Exception("No Records Found!", Response::HTTP_NOT_FOUND);
        }

        return $tasks;
    }
}
