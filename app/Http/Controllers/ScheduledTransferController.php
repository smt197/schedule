<?php

namespace App\Http\Controllers;

use App\Models\ScheduledTransfer;
use Illuminate\Http\Request;


class ScheduledTransferController extends Controller
{


    public function scheduleTransfer(Request $request)
    {
        $validated = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'scheduled_at' => 'required|date|after:now',
        ]);

        $transfer = ScheduledTransfer::create($validated);

        return response()->json([
            'message' => 'Transfert programmé avec succès',
            'transfer' => $transfer,
        ], 201);
    }
}
