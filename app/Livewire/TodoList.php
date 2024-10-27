<?php

/**
 * TodoList.php
 * 
 * Tento súbor obsahuje clasu TodoList a je zodpovedný
 * za načitavanie záznamov z databázy, ukladanie nových
 * záznamov do databázy a aktualizovanie záznamov v databáze
 */

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;

class TodoList extends Component
{
    /**
     * Input hodnota sa ukladá do $add
     * @var string
     */
    public $add = '';

    /**
     * Slúži na porovnanie či sa edituje alebo nie
     * @var integer
     */
    public $edit;

    /**
     * Slúži na premenovanie
     * @var string
     */
    public $newName;

    /**
     * Záznami z db sa uložia do poľa $todos
     * @var array
     */
    public $todos = [];

    /**
     * Táto metóda slúži na získanie všetkých
     * záznamov z tabuľky todos a vracia záznami.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTodos()
    {
        // dd(Todo::get());
        return Todo::get();
    }

    /**
     * Táto metóda slúži na zýskanie todo podľa id
     * @param mixed $id
     * @return TModel|\Illuminate\Database\Eloquent\Collection|null
     */
    public function getTodoById($id)
    {
        return Todo::find($id);
    }

    /**
     * Táto metóda slúži na zmenu stavu $edit
     * a získava názov z db a priraďuje ju k $newName
     * @param mixed $id
     * @return void
     */
    public function enableEdit($id)
    {
        $this->edit = $id;
        $this->newName = $this->getTodoById($id)->name;
    }

    /**
     * Táto metóda slúži na zrušnie editácie
     * @return void
     */
    public function cancelEdit()
    {
        $this->edit = null;
    }

    /**
     * Táto metóda slúži na zmenu názvu todo
     * @param mixed $id
     * @return void
     */
    public function editTodo($id)
    {
        // dd($this->newName);
        $validated = $this->validate([
            'newName' => 'required|min:3'
        ], [
            'newName.required' => 'Prosím zadajte správu alebo slovo.',
            'newName.min' => 'Zadajte aspon 3 znaky.'
        ]);

        $todo = $this->getTodoById($id);
        $todo->name = $this->newName;
        $todo->save();
        $this->cancelEdit();
    }

    /**
     * Táto metóda slúži na zmenenie stavu 'completed'
     * na opačnú v tabuľke todos
     * @param mixed $id
     * @return void
     */
    public function check($id)
    {
        $todo = $this->getTodoById($id);
        $todo->completed = !$todo->completed;
        $todo->save();
    }

    /**
     * Táto metóda slúži na uloženie údajov do tabuľky todos.
     * Údaje sú pred uložením kontrolované.
     * @return void
     */
    public function save()
    {
        $validated = $this->validate([
            'add' => 'required|min:3'
        ], [
            'add.required' => 'Prosím zadajte správu alebo slovo.',
            'add.min' => 'Zadajte aspon 3 znaky.'
        ]);

        Todo::create([
            'name' => $validated['add']
        ]);

        $this->reset('add');
        ;
    }

    /**
     * Táto metóda slúži na zmazanie záznamu z tabuľky
     * todos podľa id
     * @param mixed $id
     * @return void
     */
    public function deleteTodo($id)
    {
        $todo = $this->getTodoById($id);
        $todo->delete();

        // Obnovenie komponentu
        $this->dispatch('$refresh');
    }

    /**
     * Táto metóda slúži na zobrazenie view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->todos = $this->getAllTodos();
        return view('livewire.todo-list', ['todos' => $this->todos]);
    }
}
