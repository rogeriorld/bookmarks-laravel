<?php

namespace App\Livewire;

use Livewire\Component;

class Notification extends Component
{
    public $messages = [];

    protected $listeners = ['notify' => 'showMessage', 'hideMessage' => 'hideMessage'];

    public function mount()
    {
        $this->messages = [];
    }

    public function showMessage($type, $message)
    {
        $id = uniqid();
        $this->messages[] = ['id' => $id, 'type' => $type, 'message' => $message];
        $this->dispatch('messageAdded', ['id' => $id]);
    }

    public function hideMessage($id)
    {
        $this->messages = array_filter($this->messages, function ($message) use ($id) {
            return $message['id'] !== $id;
        });
    }

    public function render()
    {
        return view('livewire.notification.notification', ['messages' => $this->messages]);
    }
}
