<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\StoreTodoRequest;
    use App\Http\Requests\UpdateTodoRequest;
    use App\Http\Resources\TodoResource;
    use App\Http\Services\TodoService;
    use App\Models\Todo;
    use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

    class TodoController extends Controller
    {

        public TodoService $todoService;

        /**
         * @param TodoService $todoService
         */
        public function __construct(TodoService $todoService) {
            $this->todoService = $todoService;
        }

        /**
         * @return AnonymousResourceCollection
         */
        public function index(): AnonymousResourceCollection {
            return $this->todoService->getTodos();
        }

        /**
         * @param StoreTodoRequest $request
         * @return TodoResource
         */
        public function store(StoreTodoRequest $request): TodoResource {
            return $this->todoService->createTodo($request);
        }

        /**
         * @param int $id
         * @return TodoResource|array
         * @throws \Exception
         */
        public function show(int $id): TodoResource|array {
            return $this->todoService->getTodo($id);
        }

        /**
         * @param UpdateTodoRequest $request
         * @param int $id
         * @return TodoResource|array
         * @throws \Exception
         */
        public function update(UpdateTodoRequest $request, int $id): TodoResource|array {
            return $this->todoService->updateTodo($request, $id);
        }

        /**
         * @param string $status
         * @return AnonymousResourceCollection
         * @throws \Exception
         */
        public function getTodoByStatus(string $status): AnonymousResourceCollection {
            return $this->todoService->getTodosByStatus($status);
        }

        /**
         * @param int $id
         * @param string $status
         * @return Todo
         * @throws \Exception
         */
        public function todoStatus(int $id, string $status): Todo {
            return $this->todoService->setTodoStatus($id, $status);
        }

        /**
         * @param int $id
         * @return array
         * @throws \Exception
         */
        public function destroy(int $id): array {
            return $this->todoService->deleteTodo($id);
        }
    }
