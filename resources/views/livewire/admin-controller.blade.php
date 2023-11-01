<div>
    <x-app-layout>
    <h1>Crear Usuario</h1>
    <form wire:submit.prevent="createUser" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre de usuario</label>
            <input type="text" class="form-control" wire:model="name" id="name" placeholder="Nombre de usuario">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="text" class="form-control" wire:model="email" id="email" placeholder="Correo electrónico">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" wire:model="password" id="password" placeholder="Contraseña">
        </div>
        <button type="submit" class="btn btn-primary">Crear Usuario</button>
    </form>
    </x-app-layout>
</div>
