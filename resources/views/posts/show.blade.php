<x-app-layout>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h1 class="font-bold text-2xl text-center">{{ $post->title }}</h1>
            <a href="{{ route('profile.show', $post->author->id) }}" class="flex items-center space-x-1.5 text-gray-500 text-sm pb-2">
                <img src="https://eu.ui-avatars.com/api/?name={{$post->author->name}}e&size=250" class="rounded-full w-3 h-3">
                <span>{{ $post->author->name }} | {{ $post->created_at->format('F j, Y') }}</span>
            </a>
            <div class="flex space-x-2">
                @foreach ($post->categories as $category)
                    <x-filter-chip :filter="$category->name" href="{{route('posts.index', ['category' => $category->name])}}" />
                @endforeach
            </div>
            <p class="mt-4 text-justify text-gray-700 font-normal">{{ $post->content }}</p>

            @if ($post->author_id === auth()->id())
                <div class="mt-4">
                    <a href="{{ route('posts.edit', $post) }}" class="text-blue-500">Edit</a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 ml-4">Delete</button>
                    </form>
                </div>
            @endif

            <div class="border-b border-gray-300">
                <p class="mt-4">Comments</p>
            </div>

            <ul class="mt-4">
                @if($post->comments->empty())
                    <p class="text-center text-gray-500">No comments available</p>
                @else
                    @foreach ($post->comments as $comment)
                        <li class="relative mb-2 border rounded p-5">
                            <a href="{{ route('profile.show', $comment->user->id) }}" class="flex items-center space-x-1.5 text-gray-500 text-sm pb-2">
                                <img src="https://eu.ui-avatars.com/api/?name={{$comment->user->name}}e&size=250" class="rounded-full w-3 h-3">
                                <span>{{ $comment->user->name }} | {{ $comment->created_at->format('F j, Y') }}</span>
                            </a>
                            <p class="font-normal text-gray-700">{{ $comment->comment }}</p>

                            @if ($comment->user_id === auth()->id())
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="absolute top-2 right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-700"><x-delete-logo class="" /></button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                @endif

            </ul>

            @auth
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-4">
                    @csrf
                    <x-input-label value="Add your comment"/>
                    <div>
                        <textarea name="content" class="w-full p-2 border rounded border-gray-700" rows="4" placeholder="Add your comment..."></textarea>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="bg-gray-600 text-white p-2 rounded">Submit Comment</button>
                    </div>
                </form>
            @else
                <p class="mt-4">Please <a href="{{ route('login') }}" class="text-blue-500">log in</a> to comment.</p>
            @endauth
        </div>
    </div>
</x-app-layout>
