<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = auth()->user()->notifikasis()
            ->with('pesanan')
            ->latest()
            ->paginate(15);

        auth()->user()->unreadNotifikasis()->update(['is_read' => true]);

        return view('notifikasis.index', compact('notifikasis'));
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifikasis()->update(['is_read' => true]);
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function count()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifikasis()->count()
        ]);
    }
}