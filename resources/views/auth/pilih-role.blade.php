<x-guest-layout>
<div class="auth-card" style="max-width:460px">
    <div class="text-center mb-4">
        <div class="mb-2" style="font-size:2rem;">🧵</div>
        <div class="auth-title" style="font-size:1.4rem">Sistem Manajemen</div>
        <div class="auth-subtitle mt-1">Pilih role untuk masuk</div>
    </div>

    @error('role')
    <div class="alert alert-danger mb-3" style="background:#fef2f2;color:#991b1b;border-radius:10px;font-size:.88rem;padding:.7rem 1rem;">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}
    </div>
    @enderror

    <form action="{{ route('pilih-role.store') }}" method="POST" id="formPilihRole">
        @csrf

        @php
        $roles = [
            'admin' => [
                'label' => 'Admin',
                'desc'  => 'Kelola pesanan dan upload pembayaran',
                'icon'  => 'bi-person-fill',
                'color' => '#4f6ef7',
            ],
            'finance' => [
                'label' => 'Finance',
                'desc'  => 'Verifikasi pembayaran',
                'icon'  => 'bi-currency-dollar',
                'color' => '#10b981',
            ],
            'produksi' => [
                'label' => 'Produksi',
                'desc'  => 'Kelola stok dan status produksi',
                'icon'  => 'bi-box-seam-fill',
                'color' => '#a855f7',
            ],
        ];
        $userRole = auth()->user()->role;
        @endphp

        @foreach($roles as $key => $r)
        @php $isOwned = $userRole === $key; @endphp
        <label class="role-card {{ !$isOwned ? 'disabled' : '' }}" data-role="{{ $key }}" data-color="{{ $r['color'] }}">
            <input type="radio" name="role" value="{{ $key }}" class="d-none role-input"
                {{ !$isOwned ? 'disabled' : '' }} {{ old('role') === $key ? 'checked' : '' }}>
            <div class="role-icon" style="{{ $isOwned ? "background:{$r['color']}1a;color:{$r['color']}" : '' }}">
                <i class="bi {{ $r['icon'] }}"></i>
            </div>
            <div class="flex-grow-1">
                <div class="role-title">{{ $r['label'] }}</div>
                <div class="role-desc">{{ $r['desc'] }}</div>
            </div>
            <div class="role-radio">
                <div class="custom-radio"></div>
            </div>
        </label>
        @endforeach

        <button type="submit" class="btn-auth mt-2" id="btnMasuk" disabled>
            Masuk
        </button>
    </form>

    <div class="text-center mt-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="link-muted border-0 bg-transparent" style="font-size:.85rem">
                <i class="bi bi-box-arrow-left me-1"></i>Logout dari akun ini
            </button>
        </form>
    </div>
</div>

<style>
    .role-card.disabled { opacity: .45; cursor: not-allowed; pointer-events: none; }
    .role-card.is-selected { border-color: var(--picked-color, #4f6ef7); background: #f7f9ff; }
    .custom-radio {
        width: 20px; height: 20px; border-radius: 50%;
        border: 2px solid #cdd5e0; position: relative; transition: all .2s;
    }
    .role-card.is-selected .custom-radio { border-color: var(--picked-color, #4f6ef7); }
    .role-card.is-selected .custom-radio::after {
        content: ''; position: absolute; inset: 3px;
        border-radius: 50%; background: var(--picked-color, #4f6ef7);
    }
</style>

<script>
document.querySelectorAll('.role-card:not(.disabled)').forEach(card => {
    card.addEventListener('click', () => {
        document.querySelectorAll('.role-card').forEach(c => {
            c.classList.remove('is-selected');
            c.style.removeProperty('--picked-color');
        });
        card.classList.add('is-selected');
        card.style.setProperty('--picked-color', card.dataset.color);
        card.querySelector('.role-input').checked = true;
        document.getElementById('btnMasuk').disabled = false;
    });
});

// Restore selection state if page reloaded after a validation error
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('.role-input:checked');
    if (checked) {
        checked.closest('.role-card').click();
    }
});
</script>
</x-guest-layout>