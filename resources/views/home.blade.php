@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    {{-- @dd(Auth::user()->getAllPermissions()); --}}
                    <br>
                    @can('create')
                        You can CREATE User.
                    @endcan
                    @can('edit')
                        You can EDIT User.
                    @endcan
                    @can('delete')
                        You can DELETE User.
                    @endcan
                    @can('read')
                        You can READ User.
                    @endcan
                    @can('only super-admins can see this section')
                        Congratulations, you are a super-admin!
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
