<x-app-layout>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} User</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('users.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $user->name }}
                        </div>
                        <div class="form-group">
                            <strong>Email:</strong>
                            {{ $user->email }}
                        </div>
                        <div class="form-group">
                            <strong>Two Factor Secret:</strong>
                            {{ $user->two_factor_secret }}
                        </div>
                        <div class="form-group">
                            <strong>Two Factor Recovery Codes:</strong>
                            {{ $user->two_factor_recovery_codes }}
                        </div>
                        <div class="form-group">
                            <strong>Two Factor Confirmed At:</strong>
                            {{ $user->two_factor_confirmed_at }}
                        </div>
                        <div class="form-group">
                            <strong>Current Team Id:</strong>
                            {{ $user->current_team_id }}
                        </div>
                        <div class="form-group">
                            <strong>Profile Photo Path:</strong>
                            {{ $user->profile_photo_path }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>