<?php
//Funciones que realizan acciones con los usuarios, como crear, editar, ver y eliminar usuariso de las unidades de negocio.
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Sucursal;
use Dotenv\Validator;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;
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
    // Obtener el ID de la unidad de negocio del usuario actual
    $userSucursalId = auth()->user()->sucursal_id;

    if (Auth::user()->roles->contains('name', 'administrador')) {
        // Si es un administrador, obtener todos los usuarios y administradores
        $users = User::paginate();
    } elseif (Auth::user()->roles->contains('name', 'administrador_lavazza')) {
        // Si es un administrador_lavazza, obtener usuarios de la sucursal Lavazza
        $users = User::where('sucursal_id', $userSucursalId)
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'administrador_lavazza');
            })
            ->whereHas('roles', function ($query) {
                $query->where('name', 'usuario');
            })
            ->paginate();
    } elseif (Auth::user()->roles->contains('name', 'administrador_jumillano')) {
        // Si es un administrador_jumillano, obtener solo los usuarios de la sucursal Jumillano
        $users = User::where('sucursal_id', $userSucursalId)
        ->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'administrador_jumillano');
        })
        ->whereHas('roles', function ($query) {
            $query->where('name', 'usuario');
        })
        ->paginate();
    } elseif (Auth::user()->roles->contains('name', 'administrador_impacto')) {
        $users = User::where('sucursal_id', $userSucursalId)
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'administrador_impacto');
            })
            ->whereHas('roles', function ($query) {
                $query->where('name', 'usuario');
            })
            ->paginate();
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
            $this->authorize('create user', User::class);
        }elseif (Auth::user()->roles->contains('name', 'administrador_jumillano')) {
            $this->authorize('create user', User::class);
        }elseif (Auth::user()->roles->contains('name', 'administrador_impacto')) {
            $this->authorize('create user', User::class);
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
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,',
        'password' => 'required|string', // El campo es nullable para que no sea obligatorio, pero con una longitud mínima
    ];

    $request->validate($rules);

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
            if($role === '2'){
                $user->sucursal_id = 1;
            }elseif ($role === '4') {
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
        // Si es administrador_jumillano, asignar el rol 'usuario'
        $role = Role::where('name', 'usuario')->first();
        $user->assignRole($role);
        $user->sucursal_id = 1;
    } elseif (Auth::user()->roles->contains('name', 'administrador_lavazza')) {
        // Si es administrador_lavazza, asignar el rol 'usuario'
        $role = Role::where('name', 'usuario')->first();
        $user->assignRole($role);
        $user->sucursal_id = 2;
    } elseif (Auth::user()->roles->contains('name', 'administrador_impacto')) {
        // Si es administrador_impacto, asignar el rol 'usuario'
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
        } elseif (Auth::user()->roles->contains('name', 'administrador_lavazza')) {
            $this->authorize('edit user', User::class);
        } elseif (Auth::user()->roles->contains('name', 'administrador_impacto')) {
            $this->authorize('edit user', User::class);
        } elseif (Auth::user()->roles->contains('name', 'administrador_jumillano')) {
            $this->authorize('edit user', User::class);
        }
    
        $roles = Role::pluck('name', 'id');
        $user = User::find($id);
        $sucursales = Sucursal::pluck('nombre', 'id'); // Invertir el orden
    
        return view('user.edit', compact('user', 'sucursales', 'roles'));
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

        // Definir reglas de validación
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required|string', // El campo es nullable para que no sea obligatorio, pero con una longitud mínima
        ];

        // Aplicar las reglas de validación
        $request->validate($rules);
    
        // Actualizar los otros campos del usuario
        $user->name = $request->input('name');
        $user->email = $request->input('email');
    
        // Verificar si se proporcionó una nueva contraseña y actualizarla
        if (!empty($request->input('password'))) {
            $user->password = bcrypt($request->input('password'));
        }
    
        // Verificar si el usuario autenticado tiene permiso para asignar roles
        if (Auth::user()->roles->contains('name', 'administrador')||Auth::user()->roles->contains('name', 'administrador_jumillano')||Auth::user()->roles->contains('name', 'administrador_lavazza')||Auth::user()->roles->contains('name', 'administrador_impacto')) {
            // Verificar si se seleccionó un nuevo rol y asignarlo al usuario
            $selectedRole = $request->input('role');
            if ($selectedRole) {
                $role = Role::find($selectedRole);
                if ($role) {
                    $user->syncRoles([$role->name]);
    
                    if ($role->name === 'administrador') {
                        $user->sucursal_id = 1;
                    }elseif ($role->name === 'administrador_jumillano') {
                        $user->sucursal_id = 1; // Asigna el ID de la sucursal correspondiente para Jumillano
                    } elseif ($role->name === 'administrador_lavazza') {
                        $user->sucursal_id = 2;
                    } elseif ($role->name === 'administrador_impacto') {
                        $user->sucursal_id = 3;
                    }
                    
                } else {
                    return redirect()->back()->with('error', 'El rol seleccionado no existe.');
                }
            }
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
        $this->authorize('delete user', User::class);
    } elseif (Auth::user()->roles->contains('name', 'administrador_impacto')) {
        $this->authorize('delete user', User::class);
    } elseif (Auth::user()->roles->contains('name', 'administrador_jumillano')) {
        $this->authorize('delete user', User::class);
    }

    $user = User::find($id);

    if ($user) {
        $user->update(['activo' => false]);
        return redirect()->route('users.index')->with('success', 'Usuario Eliminado Correctamente');
    }

    return redirect()->route('users.index')->with('error', 'User not found');
}
}
