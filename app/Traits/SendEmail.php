<?php 
namespace App\Traits;

use App\Shipping;
use App\Recipient;
use App\ShippingStatus;

trait SendEmail
{
    public function sendEmail($shipping)
    {   
        $recipient = Recipient::where("user_id", $shipping->recipient_id)->first();
        $to_name = $recipient->name;
        $to_email = $recipient->email;
        $status = ShippingStatus::find($shipping->shipping_status_id);
        $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking];

        \Mail::send("emails.notification", $data, function($message) use ($to_name, $to_email) {

            $message->to($to_email, $to_name)->subject("¡Paquete actualizado!");
            $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));

        });
    }

}