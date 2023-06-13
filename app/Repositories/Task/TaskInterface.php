<?php

namespace App\Repositories\Task;

interface TaskInterface
{
    public function allTasks($perPage = 10);
}
