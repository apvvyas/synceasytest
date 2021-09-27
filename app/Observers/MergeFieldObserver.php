<?php

namespace App\Observers;

use App\Models\MergeField;

class MergeFieldObserver
{
    protected $mailchimp = '';
    /**
     * Create a new observer instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mailchimp = new \MailchimpMarketing\ApiClient();
        $this->mailchimp->setConfig([
            'apiKey' => env('MAILCHIMP_API_KEY'),
            'server' => env('MAILCHIMP_API_SERVER')
        ]);

    }

    /**
     * Handle the MergeField "created" event.
     *
     * @param  \App\Models\MergeField  $mergeField
     * @return void
     */
    public function created(MergeField $mergeField)
    {
        $this->mailchimp->lists->addListMergeField(env('MAILCHIMP_LIST_ID'), [
            'name' => $mergeField->name,
            'type' => $mergeField->type
        ]);
    }

    /**
     * Handle the MergeField "updated" event.
     *
     * @param  \App\Models\MergeField  $mergeField
     * @return void
     */
    public function updated(MergeField $mergeField)
    {
        //
    }

    /**
     * Handle the MergeField "deleted" event.
     *
     * @param  \App\Models\MergeField  $mergeField
     * @return void
     */
    public function deleted(MergeField $mergeField)
    {
        //
    }

    /**
     * Handle the MergeField "restored" event.
     *
     * @param  \App\Models\MergeField  $mergeField
     * @return void
     */
    public function restored(MergeField $mergeField)
    {
        //
    }

    /**
     * Handle the MergeField "force deleted" event.
     *
     * @param  \App\Models\MergeField  $mergeField
     * @return void
     */
    public function forceDeleted(MergeField $mergeField)
    {
        //
    }
}
