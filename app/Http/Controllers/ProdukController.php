<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Http\Requests\StoreProdukRequest;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::orderBy('stok')->paginate(12);

        $ringkasan = [
            'total_produk' => Produk::count(),
            'total_stok'   => Produk::sum('stok'),
            'stok_kritis'  => Produk::where('stok', '<=', 5)->count(),
        ];

        return view('produks.index', compact('produks', 'ringkasan'));
    }

    public function create()
    {
        return view('produks.create');
    }

    public function store(StoreProdukRequest $request)
    {
        Produk::create($request->validated());
        return redirect()->route('produks.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        return view('produks.edit', compact('produk'));
    }

    public function update(StoreProdukRequest $request, Produk $produk)
    {
        $produk->update($request->validated());
        return redirect()->route('produks.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produks.index')->with('success', 'Produk berhasil dihapus.');
    }
}