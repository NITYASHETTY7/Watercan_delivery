<?php

namespace App\Listeners;

use App\Events\ErrorOccurred;
use Illuminate\Support\Facades\Log;

class LogError
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\ErrorOccurred  $event
     * @return void
     */
    public function handle(ErrorOccurred $event)
    {
        $exception = $event->exception;;
        // return new PrivateChannel('channel-name');
        Log::info("############################ ERROR: ".$exception->getMessage());
        $project_register_id = env('HQ_PROJECT_REGISTER_ID');
        $request_link = request()->url();
        if($project_register_id){
            try {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://hq.defenzelite.com/api/v1/task/add-exception',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('project_register_id' => $project_register_id,'error_msg' => $exception->getMessage(),'request_link'=>$request_link),
                ));
    
                $response = curl_exec($curl);
                curl_close($curl);
    
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
}
