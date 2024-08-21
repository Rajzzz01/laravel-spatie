@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ $role->exists ? __('Edit Role') : __('Create Role') }}</div>

                    <div class="card-body">
                        <form
                            action="{{ $role->exists ? route('role-permissions.update', $role->id) : route('role-permissions.store') }}"
                            method="post">
                            @csrf
                            @if ($role->exists)
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $role->name) }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @php
                                $selectedRole = old('role', $role->name);
                                $selectedPermissions = old('permissions', $role->permissions->pluck('name')->toArray());
                            @endphp

                            <div class="form-check">
                                <input class="form-check-input role-radio" type="radio" value="{{ $role->name }}"
                                    name="role" id="role-{{ $role->id }}"
                                    {{ $selectedRole == $role->name ? 'checked' : '' }}>
                                <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>

                                @foreach ($permissions as $permission)
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox" type="checkbox"
                                            id="permission-{{ $permission->id }}" name="permissions[]"
                                            value="{{ $permission->name }}"
                                            {{ in_array($permission->name, $selectedPermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="permission-{{ $permission->id }}">{{ Str::ucfirst($permission->name) }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('permissions')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

