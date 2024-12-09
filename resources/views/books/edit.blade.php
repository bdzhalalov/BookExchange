<x-app-layout>
    <div class="container">
        <h1 class="mb-4">Edit book: {!! $book[0]['title'] !!}</h1>

        <form action="{{ route('books.update', ['id' => $book[0]['id']]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $book[0]['title'])" required autofocus autocomplete="title" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="author" :value="__('Author')" />
                <x-text-input id="author" class="block mt-1 w-full" type="text" name="author" :value="old('author', $book[0]['author'])" required autofocus autocomplete="author" />
                <x-input-error :messages="$errors->get('author')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="genre" :value="__('Genre')" />
                <x-text-input id="genre" class="block mt-1 w-full" type="text" name="genre" :value="old('genre', $book[0]['genre'])" required autofocus autocomplete="genre" />
                <x-input-error :messages="$errors->get('genre')" class="mt-2" />
            </div>
            <div class="form-group" >
                <label for="condition">Condition</label>
                <select class="form-control" id="condition" name="condition" required>
                    <option value="new">New</option>
                    <option value="good">Good</option>
                    <option value="old">Old</option>
                    <option value="terrible">Terrible</option>
                </select>
            </div>
            <div>
                <img src="{{ asset('storage/app/' . $book[0]['coverImage']) }}" alt="Обложка книги {{ $book[0]['title'] }}" style="max-width: 200px; height: auto;">
            </div>
            <div>
                <x-input-label for="cover_image" :value="__('Cover')" />
                <x-text-input id="cover_image" class="block mt-1 w-full" type="file" name="cover_image" accept="image/*" :value="old('cover_image', $book[0]['coverImage'])" />
                <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />
            </div>
            <x-primary-button class="ms-4">
                {{ __('Save') }}
            </x-primary-button>
        </form>
    </div>
</x-app-layout>
