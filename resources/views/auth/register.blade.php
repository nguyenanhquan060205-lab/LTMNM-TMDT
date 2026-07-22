@extends('layouts.app')

@section('content')
    <h1 class="h3">Register</h1>
    <form method="POST" action="{{ route('auth.register.store') }}" class="mt-3">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="full_name">Full name</label>
            <input class="form-control" id="full_name" name="full_name" autocomplete="name">
            <x-form-error name="full_name" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" id="username" name="username" autocomplete="username">
            <x-form-error name="username" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input class="form-control" id="password" name="password" type="password" autocomplete="new-password">
            <x-form-error name="password" />
        </div>
        <button class="btn btn-primary" type="submit">Register</button>
    </form>
@endsection
