<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('name') }}
            {{ Form::text('name', $user->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('email') }}
            {{ Form::text('email', $user->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) }}
            {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
        </div>

        <div class="form-group">
            {{ Form::label('sucursal_id', 'Sucursal') }}
            {{ Form::select('sucursal_id', $sucursales, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una sucursal']) }}
            {!! $errors->first('sucursal_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>                          
        
        <div class="form-group">
            {{ Form::label('password') }}
            {{ Form::password('password', ['class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''), 'placeholder' => 'Password']) }}
            {!! $errors->first('password', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        {{-- <div class="form-group">
            {{ Form::label('two_factor_secret') }}
            {{ Form::text('two_factor_secret', $user->two_factor_secret, ['class' => 'form-control' . ($errors->has('two_factor_secret') ? ' is-invalid' : ''), 'placeholder' => 'Two Factor Secret']) }}
            {!! $errors->first('two_factor_secret', '<div class="invalid-feedback">:message</div>') !!}
        </div> --}}
        {{-- <div class="form-group">
            {{ Form::label('two_factor_recovery_codes') }}
            {{ Form::text('two_factor_recovery_codes', $user->two_factor_recovery_codes, ['class' => 'form-control' . ($errors->has('two_factor_recovery_codes') ? ' is-invalid' : ''), 'placeholder' => 'Two Factor Recovery Codes']) }}
            {!! $errors->first('two_factor_recovery_codes', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('two_factor_confirmed_at') }}
            {{ Form::text('two_factor_confirmed_at', $user->two_factor_confirmed_at, ['class' => 'form-control' . ($errors->has('two_factor_confirmed_at') ? ' is-invalid' : ''), 'placeholder' => 'Two Factor Confirmed At']) }}
            {!! $errors->first('two_factor_confirmed_at', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('current_team_id') }}
            {{ Form::text('current_team_id', $user->current_team_id, ['class' => 'form-control' . ($errors->has('current_team_id') ? ' is-invalid' : ''), 'placeholder' => 'Current Team Id']) }}
            {!! $errors->first('current_team_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('profile_photo_path') }}
            {{ Form::text('profile_photo_path', $user->profile_photo_path, ['class' => 'form-control' . ($errors->has('profile_photo_path') ? ' is-invalid' : ''), 'placeholder' => 'Profile Photo Path']) }}
            {!! $errors->first('profile_photo_path', '<div class="invalid-feedback">:message</div>') !!}
        </div> --}}

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>