<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(15)->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with([
            'user',
            'pickupRequest.wasteCategory',
            'pickupRequest.bank',
        ])->findOrFail($id);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function exportPdf($id)
    {
        $transaction = Transaction::with([
            'user',
            'pickupRequest.wasteCategory',
            'pickupRequest.bank',
        ])->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.transactions.pdf', compact('transaction'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('bukti-transaksi-' . $transaction->transaction_id . '.pdf');
    }
}