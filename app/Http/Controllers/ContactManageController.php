<?php

namespace App\Http\Controllers;

use Excel;
use Illuminate\Http\Request;
use App\Imports\ContactImport;
use App\Models\MergeField;
use Maatwebsite\Excel\HeadingRowImport;

class ContactManageController extends Controller
{
    function processImport(Request $request)
    {
        $headings = collect((new HeadingRowImport)->toArray($request->file('file')))->flatten()->unique();

        $mergeFields = MergeField::whereIn('name', $headings->toArray())->get()->pluck('name');

        $headings->filter(function($item){
            if(in_array($item,['email_address', 'tags']))
                return false;

            return true;
        })->diff($mergeFields)->each(function($item) use($mergeFields){
            MergeField::create([
                'list_id' => env('MAILCHIMP_LIST_ID'),
                'name' => $item,
                'type' => 'text'
            ]);
        });

        Excel::import(new ContactImport, $request->file('file'));
    }

    function export(Request $request)
    {
        return \Excel::download(new \App\Exports\ContactExport(), 'contacts.csv');
    }
}
