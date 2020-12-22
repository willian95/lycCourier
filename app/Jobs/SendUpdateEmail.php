<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\StoreShippingHistory;
use App\Shipping;
use App\Recipient;
use App\ShippingStatus;
use Illuminate\Support\Facades\Log;
use App\User;

class SendUpdateEmail implements ShouldQueue
{
    
    use StoreShippingHistory;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $shippingId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shippingId)
    {
        $this->shippingId = $shippingId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{

            $shipping = Shipping::find($this->shippingId);
            
            Log::info($shipping->recipient_id." ".$shipping->client_id);

            if($shipping->recipient_id != null){
                $recipient = Recipient::find($shipping->recipient_id);
                $to_name = $recipient->name;
                $to_email = $recipient->email;
            }else if($shipping->client_id != null){
                $recipient = User::find($shipping->user_id);
                $to_name = $recipient->name;
                $to_email = $recipient->email;
            }
            
            $status = ShippingStatus::find($shipping->shipping_status_id);
    
            $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking];

            \Mail::send("emails.notification", $data, function($message) use ($to_name, $to_email, $shipping, $status) {
    
                $message->to($to_email, $to_name)->subject("¡Paquete ".$shipping->tracking." en ".$status->name."!");
                $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
    
            });

            //return response()->json(["success" => true, "msg" => "Envío Actualizado exitosamente"]);


        }catch(\Exception $e){

            //return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }
    }
}
