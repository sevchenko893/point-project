<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .left-side {
            background: url('https://images.unsplash.com/photo-1542281286-9e0a16bb7366?q=80&w=1200') center/cover no-repeat;
            height: 100vh;
        }
        .login-card {
            max-width: 380px;
            width: 100%;
            padding: 30px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0px 6px 20px rgba(0,0,0,0.08);
        }
        .form-control {
            padding: 12px;
            border-radius: 10px;
        }
        .btn-login {
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
        }
        body {
            overflow: hidden;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- Left Image Section -->
        <div class="col-md-7 left-side d-none d-md-block"></div>

        <!-- Right Form Section -->
        <div class="col-md-5 d-flex justify-content-center align-items-center vh-100">
            <div class="login-card">

                <h3 class="mb-4 text-center fw-bold">Login</h3>

                {{-- ALERT ERROR LOGIN --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Masukkan email"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            required>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 btn-login">
                        Login
                    </button>

                    <a href="/register" class="btn btn-outline-dark w-100 btn-login mt-2">
                        Register
                    </a>

                </form>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
