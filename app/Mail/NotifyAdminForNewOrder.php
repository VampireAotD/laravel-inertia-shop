<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyAdminForNewOrder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Details for email
     *
     * @var object
     */
    private $details;

    /**
     * Create a new message instance.
     *
     * @param object $details
     */
    public function __construct(object $details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to($this->details->to)
            ->subject('New user order')
            ->markdown('emails.admin.notify-admins-for-new-order', [
                'details' => $this->details
            ]);
    }
}
