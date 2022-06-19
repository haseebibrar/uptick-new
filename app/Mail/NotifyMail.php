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
    // public $icsname;
    // public function __construct($data, $filename, $icsname)
    public function __construct($data, $filename)
    {
        $this->data     = $data;
        $this->filename = $filename;
        // $this->icsname  = $icsname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dd($this->data['icslink']);
        if(isset($this->data['icslink'])){
            // $dataMail = implode('\r\n', $this->data['icslink']);
            // dd($dataMail);
            header("text/calendar");
            file_put_contents(asset('invite.ics'), '\xEF\xBB\xBF'.  $this->data['icslink']);
            return $this->view('emails.'.$this->filename)->with('data', $this->data)->attach('invite.ics', ['mime' => 'text/calendar; charset=UTF-8; method=REQUEST']);
        }else
            return $this->view('emails.'.$this->filename)->with('data', $this->data);
    }
}
