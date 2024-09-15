<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <a href="{{ route('posts.show', $post->id) }}" class="font-bold text-xl text-gray-800">{{ $post->title }}</a>
    <a href="{{ route('profile.show', $post->author->id) }}" class="flex items-center space-x-1.5 text-gray-500 text-sm pb-2">
        <img src="https://eu.ui-avatars.com/api/?name={{$post->author->name}}e&size=250" class="rounded-full w-3 h-3">
        <span>{{ $post->author->name }} | {{ $post->created_at->format('F j, Y') }}</span>
    </a>

    <div class="flex space-x-2 pb-5">
        @foreach ($post->categories as $category)
            <x-filter-chip :filter="$category->name" href="{{route('posts.index', ['category' => $category->name])}}" />
        @endforeach
    </div>
    <p class="text-justify font-normal text-gray-700">{{ Str::limit($post->content, 250) }}</p>

    <div class="flex items-center space-x-2 pt-2">
        <x-comments-logo />
        <p class="text-sm">{{ $post->comments->count() }} comments</p>
    </div>
</div>
