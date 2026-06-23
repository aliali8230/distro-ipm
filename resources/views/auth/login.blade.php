<x-guest-layout>
<div class="auth-card">
    <div class="text-center mb-4">
        <div class="mb-2" style="font-size:2rem;">🧵</div>
        <div class="auth-title">Sistem Manajemen</div>
        <div class="auth-subtitle mt-1">Masuk ke akun Anda</div>
    </div>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="nama@email.com">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                required autocomplete="current-password" placeholder="Masukkan password">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer">
                <input type="checkbox" name="remember" class="form-check-input m-0">
                <span style="font-size:.88rem;color:#7a869a;">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="link-muted">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="btn-auth">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
        </button>

        <div class="text-center mt-3">
            <span class="text-muted" style="font-size:.9rem;">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="link-muted ms-1">Daftar di sini</a>
        </div>
    </form>
</div>
</x-guest-layout>