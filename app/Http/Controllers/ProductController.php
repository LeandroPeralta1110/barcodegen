<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->roles->contains('name', 'administrador')) {
            // Si es un administrador, obtener todos los usuarios
            $products = Product::paginate();
        } elseif (Auth::user()->roles->contains('name', 'administrador_lavazza')) {
            // Si es un administrador_lavazza, obtener solo los usuarios de la sucursal Lavazza
            $products = Product::where('sucursal_id', 2)->paginate();
        } elseif (Auth::user()->roles->contains('name', 'administrador_jumillano')) {
            // Si es un administrador_jumillano, obtener solo los usuarios de la sucursal Jumillano
            $products = Product::where('sucursal_id', 1)->paginate();
        } elseif (Auth::user()->roles->contains('name', 'administrador_impacto')) {
            // Si es un administrador_impacto, obtener solo los usuarios de la sucursal Impacto
            $products = Product::where('sucursal_id', 3)->paginate();
        } else {
            // En caso contrario, no tiene permisos para ver usuarios
            abort(403, 'Unauthorized');
        }

        return view('product.index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * $products->perPage());
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
            $this->authorize('create product', Product::class);
        } elseif (Auth::user()->roles->contains('name', 'administrador_lavazza')) {
            $this->authorize('create product', Product::class);
        }
       
        $product = new Product();
        
        // Obtener todas las sucursales para el campo de selección
        $sucursales = \App\Models\Sucursal::pluck('nombre', 'id');

        // Pasa la sucursal seleccionada al formulario si está presente
        $sucursalSeleccionada = request('sucursal_id');

        return view('product.create', compact('product', 'sucursales', 'sucursalSeleccionada'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    request()->validate(Product::$rules);

    // Verifica si la solicitud proviene de la sección de creación de productos
    if ($request->has('sucursal_id')) {
        // Obtén la sucursal_id del formulario
        $sucursalId = $request->input('sucursal_id');
    } else {
        // Obtén la sucursal_id del usuario actual
        $sucursalId = auth()->user()->sucursal_id;
    }

    // Crear el producto asignando la sucursal_id
    $product = Product::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'sucursal_id' => $sucursalId,
    ]);

    return redirect()->route('products.index')
        ->with('success', 'Product created successfully.');
}

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        return view('product.show', compact('product'));
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
        $this->authorize('edit product', Product::class);
        }elseif(Auth::user()->roles->contains('name', 'administrador_lavazza')){
            $this->authorize('edit product', Product::class);
        }

        $product = Product::find($id);
        
        // Obtener todas las sucursales para el campo de selección
        $sucursales = \App\Models\Sucursal::pluck('nombre', 'id');

        return view('product.edit', compact('product', 'sucursales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        request()->validate(Product::$rules);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
{
    if (Auth::user()->roles->contains('name', 'administrador')) {
    $this->authorize('delete product', Product::class);
    }elseif(Auth::user()->roles->contains('name', 'administrador_lavazza')){
        $this->authorize('delete product', Product::class);
    }

    $product = Product::with('codigosBarras')->find($id);

    if (!$product) {
        return redirect()->route('products.index')->with('error', 'Product not found.');
    }

    // Eliminar los códigos de barras relacionados
    $product->codigosBarras()->delete();

    // Eliminar el producto
    $product->delete();

    return redirect()->route('products.index')->with('success', 'Product and related barcodes deleted successfully');
}

}