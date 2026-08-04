<?php
namespace App\Mail;
use App\Models\LeadMagnet;
use Illuminate\Mail\Mailable;
class LeadMagnetDownload extends Mailable { public function __construct(public LeadMagnet $leadMagnet) {} public function build() { return $this->subject('Tài liệu: '.$this->leadMagnet->name)->view('emails.lead-magnet-download'); } }
