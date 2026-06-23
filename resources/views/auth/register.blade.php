<x-guest-layout>
<div class="auth-card">
    <div class="text-center mb-4">
        <div class="mb-2" style="font-size:2rem;">🧵</div>
        <div class="auth-title">Buat Akun Baru</div>
        <div class="auth-subtitle mt-1">Daftarkan diri Anda ke Sistem Distro IPM</div>
    </div>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input id="nama" type="text" name="nama"
                class="form-control @error('nama') is-invalid @enderror"
                value="{{ old('nama') }}" required autofocus placeholder="Masukkan nama lengkap">
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required placeholder="nama@email.com">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                required placeholder="Minimal 8 karakter">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                class="form-control" required placeholder="Ulangi password">
        </div>

        <button type="submit" class="btn-auth">
            Daftar Sekarang
        </button>

        <div class="text-center mt-3">
            <span class="text-muted" style="font-size:.9rem;">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="link-muted ms-1">Masuk di sini</a>
        </div>
    </form>
</div>
</x-guest-layout>