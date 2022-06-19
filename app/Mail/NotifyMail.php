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
        if(isset($this->data['icslink'])){
            // dd($_SERVER["DOCUMENT_ROOT"]);
            $filename = $_SERVER["DOCUMENT_ROOT"].'images/users/invite.ics';
            $meeting_duration = (3600); // 1 hour
            $meetingstamp = strtotime( '2022-06-20 08:00:00' . " UTC");
            $dtstart = gmdate('Ymd\THis\Z', $meetingstamp);
            $dtend =  gmdate('Ymd\THis\Z', $meetingstamp + $meeting_duration);
            $todaystamp = gmdate('Ymd\THis\Z');
            $uid = date('Ymd').'T'.date('His').'-'.rand().'@uptick.co.il';
            $description = strip_tags('Testing');
            $location = "Telefone ou video conference";
            $titulo_invite = "Your meeting title";
            $organizer = "CN=Organizer name:test@test.com";

            $mail[0]  = "BEGIN:VCALENDAR";
            $mail[1] = "PRODID:-//Google Inc//Google Calendar 70.9054//EN";
            $mail[2] = "VERSION:2.0";
            $mail[3] = "CALSCALE:GREGORIAN";
            $mail[4] = "METHOD:REQUEST";
            $mail[5] = "BEGIN:VEVENT";
            $mail[6] = "DTSTART;TZID=America/Sao_Paulo:" . $dtstart;
            $mail[7] = "DTEND;TZID=America/Sao_Paulo:" . $dtend;
            $mail[8] = "DTSTAMP;TZID=America/Sao_Paulo:" . $todaystamp;
            $mail[9] = "UID:" . $uid;
            $mail[10] = "ORGANIZER;" . $organizer;
            $mail[11] = "CREATED:" . $todaystamp;
            $mail[12] = "DESCRIPTION:" . $description;
            $mail[13] = "LAST-MODIFIED:" . $todaystamp;
            $mail[14] = "LOCATION:" . $location;
            $mail[15] = "SEQUENCE:0";
            $mail[16] = "STATUS:CONFIRMED";
            $mail[17] = "SUMMARY:" . $titulo_invite;
            $mail[18] = "TRANSP:OPAQUE";
            $mail[19] = "END:VEVENT";
            $mail[20] = "END:VCALENDAR";

            $mail = implode('\r\n', $mail);
            header("text/calendar");
            file_put_contents($filename, $mail);

            return $this->view('emails.'.$this->filename)->with('data', $this->data)->attach($filename, array('mime' => "text/calendar"));
        }else
            return $this->view('emails.'.$this->filename)->with('data', $this->data);
        // $this->data['icslink'];
        // if(isset($this->data['icslink']))
        //     return $this->view('emails.'.$this->filename)->with('data', $this->data)->attach($this->data['icslink'], ['as' => 'invite.ics', 'mime' => 'text/calendar; charset=utf-8; method=REQUEST; name=invite.ics']);
        // else
        //     return $this->view('emails.'.$this->filename)->with('data', $this->data);
    }
}
