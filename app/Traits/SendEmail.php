<?php 
namespace App\Traits;

use App\Shipping;
use App\Recipient;
use App\ShippingStatus;
use App\User;

trait SendEmail
{
    public function sendEmail($shipping)
    {   
        if($shipping->recipient_id != null){
            $recipient = Recipient::find($shipping->recipient_id);
            $to_name = $recipient->name;
            $to_email = $recipient->email;
        }else if($shipping->user_id != null){
            $recipient = User::find($shipping->user_id);
            $to_name = $recipient->name;
            $to_email = $recipient->email;
        }
        
        $status = ShippingStatus::find($shipping->shipping_status_id);

        $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking];

        \Mail::send("emails.notification", $data, function($message) use ($to_name, $to_email) {

            $message->to($to_email, $to_name)->subject("¡Paquete actualizado!");
            $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));

        });
    }

}