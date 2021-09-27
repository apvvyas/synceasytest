<?php

namespace App\Jobs;

use MailchimpMarketing\ApiClient;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushToMailchimp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user = '';
    protected $mailchimp = '';
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailchimp = new ApiClient();
        $mailchimp->setConfig([
            'apiKey' => env('MAILCHIMP_API_KEY'),
            'server' => env('MAILCHIMP_API_SERVER')
        ]);

        if($this->user && $this->user->has_updated){
            
            if(empty($this->user->audience_id)){

                Log::info("Create OBJ:".json_encode($this->filterNullData([
                    'email_address' => $this->user->email_address,
                    'tags' => $this->user->tags,
                    'merge_fields' => $this->user->fields,
                    'status' => 'subscribed'
                ])));

                $response = $mailchimp->lists->addListMember(
                    env('MAILCHIMP_LIST_ID'),
                    $this->filterNullData([
                        'email_address' => $this->user->email_address,
                        'tags' => $this->user->tags,
                        'merge_fields' => $this->user->fields,
                        'status' => 'subscribed'
                    ])
                );
            }
            else{

                Log::info("Update OBJ:".json_encode($this->filterNullData( [
                    'tags' => $this->user->tags,
                    'merge_fields' => $this->user->fields 
                ])));
                $response = $mailchimp->lists->updateListMember(
                    env('MAILCHIMP_LIST_ID'), $this->user->audience_id,
                    $this->filterNullData( [
                        'tags' => $this->user->tags,
                        'merge_fields' => $this->user->fields 
                    ]), 
                );
            }
    
            $this->user->audience_id = $response->contact_id;

            $this->user->save();
        }
        
    }

    private function filterNullData($data){
        return  collect($data)->filter(function($item){
            return !empty($item);
        })->toArray();
    }
}