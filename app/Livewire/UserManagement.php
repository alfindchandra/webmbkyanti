<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    public $users, $roles;
    public $userId, $name, $email, $password, $role_id;
    public $isModalOpen = 0;

    public function render()
    {
        $this->users = User::with('roles')->get();
        $this->roles = Role::all();
        
        return view('livewire.user-management');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->userId = '';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role_id = '';
    }

    public function store()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role_id' => 'required',
        ];

        if(!$this->userId) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $data);
        
        $role = Role::findById($this->role_id);
        $user->syncRoles([$role]);

        session()->flash('message', $this->userId ? 'User updated successfully.' : 'User created successfully.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->roles->first()->id ?? '';
        
        $this->openModal();
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'User deleted successfully.');
    }
}
