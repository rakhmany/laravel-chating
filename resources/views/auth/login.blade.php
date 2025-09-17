@extends('layouts.app')

@section('title', 'Login - Laravel Chat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-sign-in-alt"></i> Login</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="{{ old('username') }}" placeholder="Enter your username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Enter your password" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </div>
                </form>

                <hr>
                
                <div class="text-center">
                    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                </div>

                <div class="alert alert-info">
                    <strong>Demo Credentials:</strong><br>
                    Username: <code>admin</code><br>
                    Password: <code>admin</code>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection