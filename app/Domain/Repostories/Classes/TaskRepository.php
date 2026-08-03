<?php

namespace App\Domain\Repostories\Classes;


use App\Domain\Enums\Project\ProjectStatus;
use App\Domain\Enums\Task\TaskStatus;
use App\Domain\Repostories\AbstractRepository;
use App\Domain\Repostories\Interfaces\ITaskRepository;

class TaskRepository extends AbstractRepository implements ITaskRepository
{
    public function getStats(): array
    {
        return $this->model->newQuery()
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('projects.user_id', auth()->id())
            ->selectRaw("
            COUNT(DISTINCT projects.id) as total_projects,
            COUNT(DISTINCT CASE WHEN projects.status = ? THEN projects.id END) as active_projects,
            COUNT(tasks.id) as total_tasks,
            COUNT(CASE WHEN tasks.status = ? THEN 1 END) as completed_tasks,
            COUNT(CASE WHEN tasks.status = ? THEN 1 END) as pending_tasks,
            COUNT(CASE
                WHEN tasks.status != ?
                AND tasks.due_date < NOW()
                THEN 1
            END) as overdue_tasks
        ", [
                ProjectStatus::ACTIVE,
                TaskStatus::DONE,
                TaskStatus::TODO,
                TaskStatus::DONE,
            ])
            ->first()->toArray();
    }
}
