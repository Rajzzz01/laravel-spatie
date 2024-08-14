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
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                            </div>
                            @foreach ($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input role-radio" type="radio" value="{{ $role->name }}" name="role" id="role-{{ $role->id }}"
                                    {{ in_array($role->name, old('role', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>

                                    @if ($role->name == 'Employee')
                                        @foreach ($permissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}"
                                                {{ in_array($permission->name, old('permissions', $user->permissions->pluck('name')->toArray())) ? 'checked' : '' }} @disabled($role->name != 'Employee')>
                                                <label class="form-check-label @if($role->name != 'Employee') text-muted @endif" for="permission-{{ $permission->id }}">
                                                    {{ Str::ucfirst($permission->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
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
            // Enable/Disable permissions based on the selected role
            $('input[type=radio]').on('click', function() {
                var selectedValue = $(this).val();
                if(selectedValue == 'Employee'){
                    $(".permission-checkbox").prop('disabled', false);
                } else {
                    $(".permission-checkbox").prop('disabled', true).prop("checked", false);
                }
            });

            // Trigger click event to set the state based on the current role
            var selectedRole = $('input[type=radio]:checked').data('role-name');
            if(selectedRole == 'Employee'){
                $(".permission-checkbox").prop('disabled', false);
            }
        });
    </script>
@endpush
