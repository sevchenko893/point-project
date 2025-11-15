<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .left-side {
            background: url('https://images.unsplash.com/photo-1557683304-673a23048d34?q=80&w=1200') center/cover no-repeat;
            height: 100vh;
        }
        .register-card {
            max-width: 430px;
            width: 100%;
            padding: 35px;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0px 6px 22px rgba(0,0,0,0.08);
        }
        .form-control {
            padding: 12px;
            border-radius: 10px;
        }
        .btn-register {
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

        <!-- Left Image -->
        <div class="col-md-7 left-side d-none d-md-block"></div>

        <!-- Right Form -->
        <div class="col-md-5 d-flex justify-content-center align-items-center vh-100">
            <div class="register-card">

                <h3 class="mb-4 text-center fw-bold">Create Account</h3>

                <form method="POST" action="/register">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Masukkan nama lengkap"
                            required>
                    </div>

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

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Konfirmasi password"
                            required>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 btn-register">
                        Register
                    </button>

                    <p class="text-center mt-3">
                        Sudah punya akun?
                        <a href="/login" class="fw-semibold">Login</a>
                    </p>

                </form>

            </div>
        </div>

    </div>
</div>

</body>
</html>
