<?php

    namespace App\Http\Services;

    use App\Events\InvalidateCacheEvent;
    use App\Http\Requests\StoreTodoRequest;
    use App\Http\Requests\UpdateTodoRequest;
    use App\Http\Resources\TodoResource;
    use App\Models\Todo;
    use App\Util\Utilities;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Response;

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

        public function getTodo(int $id): array|TodoResource {

            $todo = Todo::find($id);

            if ( empty($todo) ) {
                return Utilities::getResponse("This todo no longer exists!", false, 404);
            }

            return new TodoResource($todo);
        }

        public function deleteTodo(int $id): array {

            $todo = Todo::query()->find($id);

            if ( empty($todo) ) {
                return Utilities::getResponse("This todo no longer exists!", false, 404);
            }

            $deleted = $todo->delete();

            event(new InvalidateCacheEvent());

            if ( $deleted ) {
                return Utilities::getResponse("Todo deleted successfully", false, 204);
            } else {
                return Utilities::getResponse("Could not delete this record!", false, 406);
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
