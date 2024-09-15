<x-app-layout>
    <div class="flex justify-center min-h-screen p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl w-full">
            <h1 class="font-bold text-2xl pb-4 text-center">Create a New Post</h1>

            <form action="{{ route('posts.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <x-input-label for="title">Title</x-input-label>
                    <x-text-input id="title" class="block mt-1 w-full" name="title" :value="old('title')" autofocus/>
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="content">Content</x-input-label>
                    <textarea name="content" id="content" rows="5" class="border-gray-300 rounded w-full"></textarea>
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="categories">
                        Categories
                    </x-input-label>

                    <div class="border border-gray-300 rounded p-4">
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($categories as $category)
                                <label class="flex items-center space-x-2 mb-2">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-checkbox">
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                </div>
                <div class="text-center">
                    <button type="submit" class="bg-blue-500 text-white rounded px-4 py-2">Create Post</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
