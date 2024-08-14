@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Create User') }}</div>

                    <div class="card-body">
                        <form action="{{ route('users.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            @foreach ($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input role-radio" type="radio" value="{{ $role->name }}" name="role" id="role-{{$role->id}}">
                                    <label class="form-check-label" for="role-{{$role->id}}">{{ $role->name }}</label>
                                    @if ($role->name == 'Employee')
                                        @foreach ($permissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" type="checkbox" id="permission-{{ $permission->id }}" @disabled(true) name="permissions[]" value="{{$permission->name}}">
                                                <label class="form-check-label @if(true) text-muted @endif" for="permission-{{ $permission->id }}">{{ Str::ucfirst($permission->name) }}</label>
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
            $('input[type=radio]').on('click', function() {
                var selectedValue = $(this).val();
                if(selectedValue == 'Employee'){
                    $(".permission-checkbox").prop('disabled', false)
                } else {
                    $(".permission-checkbox").prop('disabled', true)
                    $(".permission-checkbox").prop("checked", false);
                }
            });
        });
    </script>
@endpush
