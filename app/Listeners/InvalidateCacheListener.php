<?php

    namespace App\Listeners;

    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Support\Facades\Cache;

    class InvalidateCacheListener
    {
        public function handle($event) {
            Cache::forget("all_todos");

            Cache::forget("todos_pending");
            Cache::forget("todos_completed");
        }
    }
