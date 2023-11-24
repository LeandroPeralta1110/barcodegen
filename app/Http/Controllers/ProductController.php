<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
        $products = Product::paginate();

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
        $this->authorize('create product', Product::class);
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
        $this->authorize('edit product', Product::class);

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
    $this->authorize('delete product', Product::class);

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