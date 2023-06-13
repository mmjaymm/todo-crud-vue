<?php

namespace App\Repositories\Task;

interface TaskInterface
{
    public function allTasks($perPage = 10);
    public function findTask($id);
    public function storeTask($task);
    public function updateTask($task, $id);
    public function destroyTask($id);
}
