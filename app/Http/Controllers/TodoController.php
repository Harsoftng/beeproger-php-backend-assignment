<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\StoreTodoRequest;
    use App\Http\Requests\UpdateTodoRequest;
    use App\Http\Resources\TodoResource;
    use App\Http\Services\TodoService;
    use App\Models\Todo;
    use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Request;
    use Illuminate\Support\Facades\Response;
    use Illuminate\Support\Facades\Storage;

    class TodoController extends Controller
    {

        public TodoService $todoService;

        public function __construct(TodoService $todoService) {
            $this->todoService = $todoService;
        }

        public function index(): AnonymousResourceCollection {
            return $this->todoService->getTodos();
        }

        public function store(StoreTodoRequest $request): TodoResource {
            return $this->todoService->createTodo($request);
        }

        public function show(int $id): TodoResource|array {
            return $this->todoService->getTodo($id);
        }

        public function update(UpdateTodoRequest $request, int $id): TodoResource|array {
            return $this->todoService->updateTodo($request, $id);
        }

        /**
         * @throws \Exception
         */
        public function todoStatus(string $status): AnonymousResourceCollection {
            return $this->todoService->getTodosByStatus($status);
        }

        public function destroy(int $id): array {
            return $this->todoService->deleteTodo($id);
        }
    }
