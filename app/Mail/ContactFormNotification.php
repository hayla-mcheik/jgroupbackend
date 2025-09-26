<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;

class ContactFormNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

public function build(): ContactFormNotification
{
    return $this->subject('New Contact Form Submission')
                ->view('emails.contact-notification')
                ->from('mcheikhayla26@gmail.com', 'JGroup Contact Form')
                ->replyTo($this->contact->email, $this->contact->name);
}
}