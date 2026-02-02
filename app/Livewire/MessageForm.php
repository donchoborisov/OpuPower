<?php

namespace App\Livewire;

use App\Models\Contact;
use Livewire\Component;

class MessageForm extends Component
{
    public $name;
    public $email;
    public $message;
    public $showSuccess = false;

    protected $rules = [
        'name' => 'required|max:100',
        'email' => 'required|email',
        'message' => 'required|max:800',
    ];

    public function send()
    {
        $this->validate();

        Contact::create([
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ]);

        $this->reset('name', 'email', 'message');
        session()->flash('success', 'Thank you for contacting us!');
    }

    public function render()
    {
        return view('livewire.message-form');
    }
}
