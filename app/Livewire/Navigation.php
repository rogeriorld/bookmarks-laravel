<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Actions\Logout;

class Navigation extends Component
{

    public function logout()
    {
        (new Logout())();
        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('components.layouts.navigation');
    }
}
