<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="font-bold text-2xl mb-4">Edit Post</h1>

            <form action="{{ route('posts.update', $post->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <x-input-label for="title">Title</x-input-label>
                    <x-text-input id="title" name="title" :value="old('title', $post->title)" class="block mt-1 w-full" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="content">Content</x-input-label>
                    <textarea name="content" id="content" rows="5" class="border-gray-300 rounded w-full" required>{{ old('content', $post->content) }}</textarea>
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="categories">Categories</x-input-label>
                    <div class="border border-gray-300 rounded p-4">
                        @foreach ($categories as $category)
                            <label class="flex items-center space-x-2 mb-2">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                       {{ $post->categories->pluck('id')->contains($category->id) ? 'checked' : '' }}
                                       class="form-checkbox">
                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Post</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
