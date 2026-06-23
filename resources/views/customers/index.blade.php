@extends('layouts.app')
@section('title', 'Data Customer')
@section('page-title', 'Customer')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="text-muted" style="font-size:.9rem">Daftar semua customer terdaftar</div>
    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Customer
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive-wrap">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama Customer</th>
                    <th>No. WhatsApp</th>
                    <th>Alamat</th>
                    <th>Pesanan</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $c->nama_customer }}</td>
                    <td>{{ $c->no_whatsapp }}</td>
                    <td class="text-muted">{{ Str::limit($c->alamat, 40) }}</td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            {{ $c->pesanans_count }} pesanan
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('customers.show', $c) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            <a href="{{ route('customers.edit', $c) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('customers.destroy', $c) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Hapus customer {{ $c->nama_customer }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-2 d-block mb-2"></i>
                        Belum ada customer terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $customers->links() }}</div>

@endsection