<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Sucursal;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    use HasRoles;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view user', User::class);
        $users = User::paginate();
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
        $this->authorize('create user', User::class);
        $sucursales = Sucursal::pluck('nombre', 'id'); // Invertir el orden
        $user = new User();
        return view('user.create', compact('user', 'sucursales'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Crea el nuevo usuario
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'sucursal_id' => $request->sucursal_id, // Asegúrate de que se esté asignando correctamente
    ]);

    // Obtiene el rol con el nombre específico, por ejemplo, 'usuario'
    $role = Role::where('name', 'usuario')->first();

    // Asigna el rol al usuario
    $user->assignRole($role);

    return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
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
        $this->authorize('edit user', User::class);
        $user = User::find($id);
        $sucursales = Sucursal::pluck('nombre', 'id'); // Invertir el orden

        return view('user.edit', compact('user', 'sucursales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        /* $this->authorize('update', User::class); */
        request()->validate(User::$rules);

        $user->update($request->all());

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
{
    $this->authorize('delete user', User::class);
    $user = User::find($id);

    if ($user) {
        $user->update(['activo' => false]);
        return redirect()->route('users.index')->with('success', 'User deactivated successfully');
    }

    return redirect()->route('users.index')->with('error', 'User not found');
}



}
