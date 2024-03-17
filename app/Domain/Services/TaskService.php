<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Models\Task;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

final class TaskService
{
    /** @return Collection<int,Task> */
    public function getTasks(): Collection
    {
        return Task::query()->get();
    }

    public function getTask(string $id): ?Task
    {
        /** @var Task */
        return Task::query()
            ->where('id', $id)
            ->first();
    }

    public function createTask(string $title): Task
    {
        return Task::create([
            'title' => $title,
        ]);
    }

    public function closeTask(Task $task): void
    {
        if (null !== $task->closed) {
            throw new \RuntimeException('Task already closed');
        }

        $task->update([
            'closed' => Date::now(),
        ]);
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}
