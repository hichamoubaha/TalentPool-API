<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidatureStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $candidature;

    /**
     * Create a new message instance.
     *
     * @param  Candidature  $candidature
     * @return void
     */
    public function __construct(Candidature $candidature)
    {
        $this->candidature = $candidature;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Mise à jour du statut de votre candidature')
                    ->view('emails.candidature-status-updated');
    }
}
