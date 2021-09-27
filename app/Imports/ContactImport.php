<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Row;
use App\Jobs\PushToMailchimp;
use Maatwebsite\Excel\Concerns\{OnEachRow, WithHeadingRow};

class ContactImport implements OnEachRow, WithHeadingRow
{
    /**
    * @param array $row
    */
    public function onRow(Row $row)
    {
        $row      = $row->toArray();

        $tags = (!empty($row['tags']) ? explode(',', $row['tags']): '');

        $data = collect($row);

        
        if(!empty($row['email_address'])){
            $user = User::firstOrCreate([
                'email_address' => $row['email_address']
            ]);
    
            $data = $data->each(function($item, $key) use(&$user){
                if($key != 'email_address' && !empty($item)){
                    if($key == 'tags')
                        $user->tags = explode(',', $item);
                    else{
                        $user->fields = [$key => $item];
                    }
                }
            });
            $user->has_updated = true;
            $user->save();

            PushToMailchimp::dispatch($user);
        }
    }


}
