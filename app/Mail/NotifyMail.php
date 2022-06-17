<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $filename;
    public $icsname;
    public function __construct($data, $filename, $icsname)
    {
        $this->data     = $data;
        $this->filename = $filename;
        $this->icsname  = $icsname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return $this->view('emails.'.$this->filename)->with('data', $this->data);
    }
}
