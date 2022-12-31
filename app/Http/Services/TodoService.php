<?php

    namespace App\Http\Services;

    use App\Events\InvalidateCacheEvent;
    use App\Http\Requests\StoreTodoRequest;
    use App\Http\Requests\UpdateTodoRequest;
    use App\Http\Resources\TodoResource;
    use App\Models\Todo;
    use App\Util\TodoStatus;
    use App\Util\Utilities;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\File;

    class TodoService
    {
        public function createTodo(StoreTodoRequest $request): TodoResource {

            // upload file and get file url
            $fileUrl = $this->uploadTodoImage($request);

            //prepare the
            $todoData = $request->only(
                    ["title", "description", "startDate", "priority", "status"]
                ) + ["photoUrl" => $fileUrl];

            $todo = Todo::create($todoData);
            $todo->refresh();

            event(new InvalidateCacheEvent());

            return new TodoResource($todo);
        }

        public function updateTodo(UpdateTodoRequest $request, int $id): TodoResource|array {

            $todo = Todo::query()->find($id);

            if ( empty($todo) ) {
                return Utilities::getResponse("This todo no longer exists!", false, 404);
            }

            // the todo id provided in the request has to be the same id provided in the query parameters
            if ( $request->id != $id ) {
                return Utilities::getResponse("Invalid request!", false, 404);
            }

            // upload file and get file url
            $fileUrl = $this->uploadTodoImage($request);
            $fileData = !empty($fileUrl) ? ["photoUrl" => $fileUrl] : [];


            //prepare the
            $todoData = $request->only(
                    ["title", "description", "startDate", "priority", "status"]
                ) + $fileData;

            $todo->update($todoData);
            $todo->refresh();

            event(new InvalidateCacheEvent());

            return new TodoResource($todo);
        }

        public function getTodos(): AnonymousResourceCollection {
            $todos = Cache::remember("all_todos", 86400, fn() => Todo::all());
            return TodoResource::collection($todos);
        }

        /**
         * @throws \Exception
         */
        public function getTodosByStatus($status): AnonymousResourceCollection {

            $knownStatuses = collect(["pending", "completed"]);

            if ( !$knownStatuses->contains($status) ) {
                throw new \Exception("Unknown todo status provided");
            }

            $statusSearchKey = "";
            switch ( $status ) {
                case "pending":
                    $statusSearchKey = TodoStatus::Pending;
                    break;
                case "completed":
                    $statusSearchKey = TodoStatus::Completed;
                    break;
            }

            $todos = Cache::remember("todos_{$status}", 86400, fn() => Todo::whereStatus($statusSearchKey)->get());
            return TodoResource::collection($todos);
        }

        public function getTodo(int $id): array|TodoResource {

            $todo = Todo::find($id);

            if ( empty($todo) ) {
                return Utilities::getResponse("This todo no longer exists!", false, Response::HTTP_NOT_FOUND);
            }

            return new TodoResource($todo);
        }

        public function deleteTodo(int $id): array {

            $todo = Todo::query()->find($id);

            if ( empty($todo) ) {
                return Utilities::getResponse("This todo no longer exists!", false, Response::HTTP_NOT_FOUND);
            }

            $deleted = $todo->delete();

            event(new InvalidateCacheEvent());

            if ( $deleted ) {
                return Utilities::getResponse("Todo deleted successfully", false, Response::HTTP_NO_CONTENT);
            } else {
                return Utilities::getResponse("Could not delete this record!", false, Response::HTTP_NOT_ACCEPTABLE);
            }
        }

        public function uploadTodoImage(FormRequest $request): string {
            $file = $request->file("photoUpload");
            $fileUrl = "";

            //check if a file was uploaded
            if ( $request->hasFile("photoUpload") and $file->isValid() ) {

                $path = "files/images/todos/";
                File::makeDirectory(public_path($path), 0755, true, true);

                // upload file and save the url
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . rand(1000000, 9999999) . "." . $extension;

                $fileUrl = asset($path . $fileName);
                $location = public_path($path);
                File::put($location . $fileName, $file->get());
            }

            return $fileUrl;
        }
    }
