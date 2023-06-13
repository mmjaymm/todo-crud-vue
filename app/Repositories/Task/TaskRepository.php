<?php

namespace App\Repositories\Task;

use App\Models\Task;
use App\Repositories\Task\TaskInterface;
use Illuminate\Support\Facades\Log;
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

    public function storeTask($task)
    {
        $isSave = Task::create($task);

        if (!$isSave) {
            throw new \Exception("Something wrong, unable to save this records!", 500);
        }

        return $isSave;
    }

    public function updateTask($task, $id)
    {
        try {
            $isUpdated = Task::where([
                'id' => $id
            ])->update($task);

            if (!$isUpdated) {
                throw new \Exception("Something wrong, unable to update this record!", Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $isUpdated;
        } catch (\Exception $ex) {
            Log::channel('daily')->critical(
                $ex->getMessage(),
                [
                    'ClassName : ' . get_class($this),
                    'Method Name : ' . __FUNCTION__,
                    'Line : ' . __LINE__
                ]
            );
            throw new \Exception("Unable to update this record!", Response::HTTP_INTERNAL_SERVER_ERROR);
            return false;
        }
    }

    public function destroyTask($id)
    {
        try {
            $task = $this->findTask($id);
            $isDeleted = $task->delete();

            if (!$isDeleted) {
                throw new \Exception("Something wrong, unable to delete this record!", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $isDeleted;
    }

    public function findTask($id)
    {
        $task = Task::find($id);

        if (!$task) {
            throw new \Exception("Task {$id} is not found!", Response::HTTP_NOT_FOUND);
        }

        return $task;
    }
}
