<?php

namespace App\Console\Commands;

use App\Models\ScheduledTransfer;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ExecuteScheduledTransfers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:transfers:execute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execution de transferts plannifiees';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        Cache::put('last_schedule_run', now(), now()->addHours(1));

        $now = now();

        $transfers = ScheduledTransfer::where('status', 'pending')
            ->where('scheduled_at', '<=', $now)
            ->get();
    
        foreach ($transfers as $transfer) {
            try {
                // Exemple de logique pour effectuer un transfert
                $sender = User::findOrFail($transfer->sender_id);
                $receiver = User::findOrFail($transfer->receiver_id);
    
                if ($sender->balance >= $transfer->amount) {
                    // Déduire du solde de l'expéditeur
                    $sender->balance -= $transfer->amount;
                    $sender->save();
    
                    // Ajouter au solde du destinataire
                    $receiver->balance += $transfer->amount;
                    $receiver->save();
    
                    // Mettre à jour le statut
                    $transfer->status = 'completed';
                } else {
                    $transfer->status = 'failed'; // Solde insuffisant
                }
    
                $transfer->executed_at = $now;
                $transfer->save();
            } catch (\Exception $e) {
                // Marquer comme échoué en cas d'erreur
                $transfer->status = 'failed';
                $transfer->executed_at = $now;
                $transfer->save();
    
                Log::error("Erreur lors du transfert ID {$transfer->id}: {$e->getMessage()}");
            }
        }
    }
}
