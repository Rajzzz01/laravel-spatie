@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ $user->exists ? __('Edit User') : __('Create User') }}</div>

                    <div class="card-body">
                        <form action="{{ $user->exists ? route('users.update', $user->id) : route('users.store') }}" method="post">
                            @csrf
                            @if($user->exists)
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            @php
                                $selectedRole = old('role', $user->roles->pluck('name')->first());
                                $selectedPermissions = old('permissions', $user->permissions->pluck('name')->toArray());
                            @endphp

                            @foreach ($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input role-radio" type="radio" value="{{ $role->name }}" name="role" id="role-{{ $role->id }}"
                                    {{ $selectedRole == $role->name ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>

                                    @if ($role->name == 'Employee')
                                        @foreach ($permissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}"
                                                {{ in_array($permission->name, $selectedPermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission-{{ $permission->id }}">{{ Str::ucfirst($permission->name) }}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach

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
@push('scripts')
    <script>
        $(document).ready(function() {
            function togglePermissions() {
                var selectedRole = $('input[name="role"]:checked').val();
                if (selectedRole == 'Employee') {
                    $(".permission-checkbox").prop('disabled', false);
                } else {
                    $(".permission-checkbox").prop('disabled', true).prop('checked', false);
                }
            }

            // Initial toggle based on the selected role on page load
            togglePermissions();

            // Toggle permissions when a role is selected
            $('input[name="role"]').on('change', function() {
                togglePermissions();
            });
        });
    </script>
@endpush
