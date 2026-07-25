@extends('layouts.app')

@section('content')
    <h1 class="h3">Login</h1>
    <form method="POST" action="{{ route('auth.login.store') }}" class="mt-3">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" id="username" name="username" autocomplete="username">
            <x-form-error name="username" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input class="form-control" id="password" name="password" type="password" autocomplete="current-password">
            <x-form-error name="password" />
        </div>
        <button class="btn btn-primary" type="submit">Login</button>
    </form>
@endsection
