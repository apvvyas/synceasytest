<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return \Excel::download(new \App\Exports\ContactExport(), 'contacts.xlsx');
    // $client = new MailchimpMarketing\ApiClient();

    // $client->setConfig([
    //     'apiKey' => env('MAILCHIMP_API_KEY'),
    //     'server' => env('MAILCHIMP_API_SERVER')
    // ]);
    
    // collect($client->lists->getListMergeFields(
    //         env('MAILCHIMP_LIST_ID'), null, null, 50
    //     )->merge_fields)->each(function($item) use($client){
    //     dump($client->lists->deleteListMergeField(env('MAILCHIMP_LIST_ID'), $item->merge_id));
    // });

    // dd('');
    return view('welcome');
});
