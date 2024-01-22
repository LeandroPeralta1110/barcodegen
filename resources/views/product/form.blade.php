<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('nombre') }}
                    {{ Form::text('nombre', $product->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
                    {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('descripcion') }}
                    {{ Form::text('descripcion', $product->descripcion, ['class' => 'form-control' . ($errors->has('descripcion') ? ' is-invalid' : ''), 'placeholder' => 'Descripcion']) }}
                    {!! $errors->first('descripcion', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <!-- Nuevo campo de selección para la sucursal -->
        @role('administrador')
        <div class="form-group">
            {{ Form::label('sucursal_id', 'Unidad de Negocio') }}
            {{ Form::select('sucursal_id', $sucursales, $sucursalSeleccionada, ['class' => 'form-control' . ($errors->has('sucursal_id') ? ' is-invalid' : ''), 'placeholder' => 'Selecciona una sucursal']) }}
            {!! $errors->first('sucursal_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        @endrole
    </div>
</div>
