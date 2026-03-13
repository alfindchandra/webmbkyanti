<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $email    = '';
    public string $password = '';
    public bool   $remember = true; // always remember by default

    protected array $rules = [
        'email'    => 'required|email',
        'password' => 'required|string|min:1',
    ];

    protected array $messages = [
        'email.required'    => 'Email tidak boleh kosong.',
        'email.email'       => 'Format email tidak valid.',
        'password.required' => 'Password tidak boleh kosong.',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        $this->addError('email', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.login')
            ->layout('layouts.guest');
    }
}
