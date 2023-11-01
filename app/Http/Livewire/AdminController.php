<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use App\Models\Product;

class AdminController extends Component
{
    public $name;
    public $nombre;
    public $email;
    public $descripcion;
    public $password;

    public function showCreateUserForm()
{
    return view('livewire.admin-controller');
}

    public function createUser()
{
    // Valida los campos si es necesario
    $this->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
    ]);

    // Crea el nuevo usuario
    $user = User::create([
        'name' => $this->name,
        'email' => $this->email,
        'password' => bcrypt($this->password),
    ]);

    // Obtiene el rol con el nombre específico, por ejemplo, 'admin'
    $role = Role::where('name', 'usuario')->first();

    // Asigna el rol al usuario
    $user->assignRole($role);

    // Limpia los campos después de crear el usuario
    $this->name = '';
    $this->email = '';
    $this->password = '';

    // Puedes agregar un mensaje de éxito si lo deseas
    session()->flash('message', 'Usuario creado con éxito.');
}

public function showCreateProductForm()
{
    return view('livewire.create-product');
}

   public function crearProducto()
{
   $this->validate([
    'nombre' => 'required|string',
    'descripcion' => 'required',
    ]); 

    // Crea el nuevo producto en la base de datos
    Product::create([
        'nombre' => $this->nombre,
        'descripcion' => $this->descripcion,
    ]);

    // Limpia los campos después de crear el producto
    $this->nombre = '';
    $this->descripcion = '';

    session()->flash('message', 'Producto creado con éxito.');
}
    
    public function render()
    {
        return view('livewire.admin-controller');
    }
}
