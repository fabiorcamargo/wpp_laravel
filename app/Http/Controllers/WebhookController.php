<?php

namespace App\Http\Controllers;

use App\Models\WppMessage;
use App\Models\WppMessageReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{


        /**
     * UPDATE YOUR ACCESS TOKEN
     * This will be the Access Token value needed to send messages via WhatsApp
     **/




    public function handleWebhook(Request $request)
    {
        // Parse the request body from the POST
        $body = $request->all();

        // Check the Incoming webhook message
        Log::info(json_encode($body, JSON_PRETTY_PRINT));

        // Check if the request is from WhatsApp API
        if ($body['object']) {
            if (
                $body['entry'] &&
                $body['entry'][0]['changes'] &&
                $body['entry'][0]['changes'][0] &&
                $body['entry'][0]['changes'][0]['value']['messages'] &&
                $body['entry'][0]['changes'][0]['value']['messages'][0]
            ) {
                $phoneNumberId = $body['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];
                $from = $body['entry'][0]['changes'][0]['value']['messages'][0]['from'];
                $msgBody = $body['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];

                // Send acknowledgment message
                $response = Http::post("https://graph.facebook.com/v12.0/{$phoneNumberId}/messages?access_token=" . env('WHATSAPP_TOKEN'), [
                    'messaging_product' => 'whatsapp',
                    'to' => $from,
                    'text' => ['body' => "Ack: {$msgBody}"],
                ]);

                // Log the response
                Log::info(json_encode($response->json(), JSON_PRETTY_PRINT));
            }

            return response()->json(['success' => true], 200);
        } else {
            // Return a '404 Not Found' if the event is not from the WhatsApp API
            return response()->json(['error' => 'Not Found'], 404);
        }
    }

    public function webhookVerification(Request $request)
    {
        // Parse params from the webhook verification request
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        // Check if a token and mode were sent
        if ($mode && $token) {
            // Check the mode and token sent are correct
            if ($mode === "subscribe" && $token === env('VERIFY_TOKEN')) {
                // Respond with 200 OK and challenge token from the request
                Log::info("WEBHOOK_VERIFIED");
                return response($challenge, 200);
            } else {
                // Responds with '403 Forbidden' if verify tokens do not match
                return response()->json(['error' => 'Forbidden'], 403);
            }
        } else {
            // Responds with '400 Bad Request' if mode or token is missing
            return response()->json(['error' => 'Bad Request'], 400);
        }
    }


    public function register(Request $request){

        //dd($request->all());

        $data = json_decode(json_encode($request->all()));

        $return = new WppMessageReturn;
        $return->create(['body' => json_encode($data)]);

         if(isset($data->entry[0]->changes[0]->value->statuses[0])){
             $status = $data->entry[0]->changes[0]->value->statuses[0];
             $this->status($status);
        }
     


    }
   

    public function status($data){


        if(WppMessage::where('wppid', $data->id)->first()){
            $msg = WppMessage::where('wppid', $data->id)->first();

            $status = $data->status;
    
            $msg->status = $status;
            
            $msg->save();
        }
        
    }

}
