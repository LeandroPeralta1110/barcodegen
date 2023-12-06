<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Sucursal;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule as ValidationRule;
use Spatie\Permission\Models\Permission;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    use HasFactory, Notifiable, HasRoles;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    if (Auth::user()->roles->contains('name', 'administrador')) {
        // Si es un administrador, obtener todos los usuarios
        $users = User::paginate();
    } elseif (Auth::user()->roles->contains('name', 'administrador_lavazza')) {
        // Si es un administrador_lavazza, obtener solo los usuarios de la sucursal Lavazza
        $users = User::where('sucursal_id', 2)->paginate();
    } elseif (Auth::user()->roles->contains('name', 'administrador_jumillano')) {
        // Si es un administrador_jumillano, obtener solo los usuarios de la sucursal Jumillano
        $users = User::where('sucursal_id', 1)->paginate();
    } elseif (Auth::user()->roles->contains('name', 'administrador_impacto')) {
        // Si es un administrador_impacto, obtener solo los usuarios de la sucursal Impacto
        $users = User::where('sucursal_id', 3)->paginate();
    } else {
        // En caso contrario, no tiene permisos para ver usuarios
        abort(403, 'Unauthorized');
    }

    return view('user.index', compact('users'))
        ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    
        // Verificar si el usuario es administrador o administrador_lavazza
        if (Auth::user()->roles->contains('name', 'administrador')) {
            $this->authorize('create user', User::class);
        } elseif (Auth::user()->roles->contains('name', 'administrador_lavazza')) {
            $this->authorize('create user_area_lavazza', User::class);
        }
    
        $sucursales = Sucursal::pluck('nombre', 'id');
        $user = new User();
        $roles = Role::pluck('name', 'id');
    
        return view('user.create', compact('user', 'sucursales', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    
     public function store(Request $request)
{
    // Crear el nuevo usuario
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    // Verificar si el usuario tiene el rol "administrador"
    if (Auth::user()->roles->contains('name', 'administrador'))  {
        // Si es administrador, asignar el rol recibido en el formulario
        $role = $request->role;
        
        if ($role) {
            // Utiliza attach para asociar roles a usuarios
            $user->roles()->attach($role);

            // Asignar la sucursal_id según el rol específico
            if ($role === '4') {
                $user->sucursal_id = 1; // Asigna el ID de la sucursal correspondiente para Jumillano
            }elseif($role === '5'){
                $user->sucursal_id = 2;
            }elseif($role === '6'){
                $user->sucursal_id = 3;
            }

            // Guardar el usuario con los roles y sucursal_id asignados
            $user->save();

            return redirect()->route('users.index')->with('success', 'Usuario Creado Exitosamente.');
        } else {
                // Manejar el caso en el que el rol no existe
                // Puedes lanzar una excepción, redirigir con un mensaje de error, etc.
                return redirect()->back()->with('error', 'El rol especificado no existe.');
            }
    } elseif (Auth::user()->roles->contains('name', 'administrador_jumillano')) {
        // Si es administrador_jumillano, asignar el rol 'usuario' (puedes personalizar esta lógica según tus necesidades)
        $role = Role::where('name', 'usuario')->first();
        $user->assignRole($role);
        $user->sucursal_id = 1;
    } elseif (Auth::user()->roles->contains('name', 'administrador_lavazza')) {
        // Si es administrador_lavazza, asignar el rol 'usuario' (puedes personalizar esta lógica según tus necesidades)
        $role = Role::where('name', 'usuario')->first();
        $user->assignRole($role);
        $user->sucursal_id = 2;
    } elseif (Auth::user()->roles->contains('name', 'administrador_impacto')) {
        // Si es administrador_impacto, asignar el rol 'usuario' (puedes personalizar esta lógica según tus necesidades)
        $role = Role::where('name', 'usuario')->first();
        $user->assignRole($role);
        $user->sucursal_id = 3;
    }

    // Obtener los roles después de asignarlos
    $roles = $user->roles;

    // Guardar el usuario con los roles y sucursal_id asignados
    $user->save();

    return redirect()->route('users.index')->with('success', 'Usuario Creado Exitosamente.');
}

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->roles->contains('name', 'administrador')) {
        $this->authorize('edit user', User::class);
        
        }elseif(Auth::user()->roles->contains('name', 'administrador_lavazza')){
            $this->authorize('edit user_area_lavazza', User::class);
        }

        $roles = Role::pluck('name', 'id');
        $user = User::find($id);
        $sucursales = Sucursal::pluck('nombre', 'id'); // Invertir el orden

        return view('user.edit', compact('user', 'sucursales','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{

    $user = User::find($id);

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            ValidationRule::unique('users')->ignore($id),
        ],
        'sucursal_id' => 'required',
        'password' => 'nullable|confirmed',
    ]); 

    dd($validatedData);
    // Actualizar los otros campos del usuario
    $user->name = $validatedData['name'];
    $user->email = $validatedData['email'];
    $user->sucursal_id = $validatedData['sucursal_id'];

    // Verificar si se proporcionó una nueva contraseña y actualizarla
    if (!empty($validatedData['password'])) {
        $user->password = bcrypt($validatedData['password']);
    }

    $user->save();

    return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
}

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
{
    if (Auth::user()->roles->contains('name', 'administrador')) {
        $this->authorize('delete user', User::class);
    }elseif(Auth::user()->roles->contains('name', 'administrador_lavazza')){
        $this->authorize('delete user_area_lavazza', User::class);
    }
    $user = User::find($id);

    if ($user) {
        $user->update(['activo' => false]);
        return redirect()->route('users.index')->with('success', 'Usuario Desactivado');
    }

    return redirect()->route('users.index')->with('error', 'User not found');
}



}
