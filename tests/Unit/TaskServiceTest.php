<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Domain\Services\TaskService;
use Tests\TestCase;

/**
 * @internal
 *
 * @covers \App\Domain\Services\TaskService
 */
final class TaskServiceTest extends TestCase
{
    private TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new TaskService();
    }

    /**
     * Create a new task and ensure that the initial state is set correctly.
     *
     * @test
     */
    public function task_is_created_successfully(): void
    {
        $time = time();
        $task = $this->service->createTask($title = 'Test Task');

        $this->assertSame($title, $task->getAttribute('title'));
        $this->assertSame($time, strtotime((string) $task->getAttribute('created')));
        $this->assertSame((string) $task->getAttribute('created'), (string) $task->getAttribute('last_modified'));
        $this->assertNull($task->getAttribute('closed'));
        $this->assertNull($task->getAttribute('deleted'));
    }

    /**
     * Ensure that getTasks returns all non-deleted tasks.
     *
     * @test
     */
    public function get_tasks_fetches_all_non_deleted_tasks(): void
    {
        $count = 120;
        for ($n=0; $n < $count; ++$n) {
            $this->service->createTask("Task {$n}");
        }

        $deleteme = $this->service->createTask('Delete Me');
        $this->service->deleteTask($deleteme);

        $tasks = $this->service->getTasks();

        $this->assertSame($count, $tasks->count());
    }

    /**
     * Getting a task directly by ID works.
     *
     * @test
     */
    public function fetching_task_by_id_grabs_the_correct_task(): void
    {
        $task = $this->service->createTask('Test Task');
        $target = $this->service->getTask($task->id);

        $this->assertNotNull($target);
        $this->assertSame((string) $task->getKey(), (string) $target->getKey());
    }

    /**
     * Close an existing task and ensure appropriate values are updated.
     *
     * @test
     */
    public function closing_task_works_successfully(): void
    {
        $task = $this->service->createTask($title = 'Test Task');

        $time = time();
        $this->service->closeTask($task);

        $task->refresh();

        $this->assertSame($time, strtotime((string) $task->getAttribute('closed')));
        $this->assertSame((string) $task->getAttribute('closed'), (string) $task->getAttribute('last_modified'));
    }

    /**
     * Ensure that trying to close a task which is already closed raises an exception.
     *
     * @test
     */
    public function closing_already_closed_task_fails(): void
    {
        $task = $this->service->createTask($title = 'Test Task');
        $this->service->closeTask($task);

        $this->expectException(\RuntimeException::class);

        $this->service->closeTask($task);
    }

    /**
     * Delete an existing task and ensure appropriate values are updated.
     *
     * @test
     */
    public function task_is_deleted_successfully(): void
    {
        $task = $this->service->createTask($title = 'Test Task');

        $time = time();
        $this->service->deleteTask($task);

        $task->refresh();

        $this->assertSame($time, strtotime((string) $task->getAttribute('deleted')));
        $this->assertSame((string) $task->getAttribute('deleted'), (string) $task->getAttribute('last_modified'));
    }
}
