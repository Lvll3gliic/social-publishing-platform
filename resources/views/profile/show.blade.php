<x-app-layout>
    <div class="flex flex-col items-center justify-center">
        <img src="https://eu.ui-avatars.com/api/?name={{$user->name}}&size=250" class="rounded-full w-1/6 h-1/6">
        <p class="mt-2 text-2xl pb-4">{{ $user->name }}</p>
        <div class="border-t border-gray-300 pt-2 flex flex-col items-center justify-center">
            <p class="text-lg">Posts</p>
            <p>{{$posts->count()}}</p>
        </div>
    </div>
    @if($posts->isEmpty())
        <p class="text-center text-gray-500 p-5">No posts available</p>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>
        </div>
    @endif
</x-app-layout>
