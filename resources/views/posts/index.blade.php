<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Main Feed
        </h2>
    </x-slot>
    <form action="{{ route('posts.index') }}" method="GET" class="mb-4">
        <div class="grid grid-cols-2 space-x-2">
            <div>
                <x-input-label for="search" class="block text-sm mb-2">Search for Posts:</x-input-label>
                <x-text-input  type="text" name="search" id="search" value="{{ request('search') }}" class="p-2 border rounded w-full" placeholder="Enter keywords..." />
            </div>
            <div>
                <x-input-label for="category" class="block text-sm mb-2">Filter by Category:</x-input-label>
                <select name="category" id="category" class="p-2 border rounded w-full">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-center mt-4">
            <button type="submit" class="bg-gray-600 text-white p-2 rounded">Search & Filter</button>
        </div>
    </form>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if($posts->isEmpty())
                <p class="text-center text-gray-500">No posts available</p>
            @else
                @foreach ($posts as $post)
                    <x-post-card :post="$post" />
                @endforeach
                <div>
                    {{ $posts->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
