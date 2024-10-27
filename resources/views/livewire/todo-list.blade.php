<main class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h1 class="text-2xl font-bold mb-4 text-center text-gray-800">Todo List</h1>

    <!-- Formulár pre pridanie novej úlohy -->
    <form wire:submit="save" class="mb-4">
        <div class="flex mb-1">
            <input type="text" wire:model="add" placeholder="Pridať novú úlohu"
                class="flex-grow px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Pridať</button>
        </div>
        @error('add')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </form>
    <!-- Zoznam úloh -->
    <ul class="space-y-2">
        {{-- @dd($todos) --}}
        @if ($todos->count() > 0)
            @foreach ($todos as $todo)
                <li wire:key="{{ $todo->id }}" class="flex items-center justify-between bg-gray-200 p-3 rounded-md">
                    @if ($todo->completed)
                        <input wire:click="check({{ $todo->id }})" class="mr-2" type="checkbox" checked>
                    @else
                        <input wire:click="check({{ $todo->id }})" class="mr-2" type="checkbox">
                    @endif
                    @if ($edit === $todo->id)
                        <div class="flex flex-col">
                            <input type="text" wire:model="newName" class="flex-grow">
                            @error('newName')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <span
                            class="{{ $todo->completed === 1 ? 'line-through flex-grow' : 'flex-grow' }}">{{ $todo->name }}</span>
                    @endif
                    @if ($edit === $todo->id)
                        <div>
                            <button class="text-green-500" wire:click="editTodo({{ $todo->id }})">Submit</button>
                            <button class="text-red-500" wire:click="cancelEdit()">Cancel</button>
                        </div>
                    @endif
                    <div class="flex space-x-2">
                        <button wire:click="enableEdit({{ $todo->id }})" class="text-blue-500 hover:text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                        </button>
                        <button wire:click="deleteTodo({{ $todo->id }})" class="text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </li>
            @endforeach
        @else
            <p>Zoznam je prázdny</p>
        @endif
    </ul>
</main>
