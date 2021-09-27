<?php

namespace App\Exports;

use Illuminate\Support\Str;
use App\Models\MergeField;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $user;

    protected $columns;

    function __construct(){
        $this->user = new User;
        $this->columns = collect($this->user->getFillable())
                        ->merge(MergeField::get()->pluck('name')->toArray()) 
                        ->filter(function($item, $key){
                            if($item == 'fields')
                                return false;
                            return true;
                        })->toArray();
    }
    /**
    * @var Invoice $invoice
    */
    public function query()
    {
        return $this->user->query()->whereNotNull('audience_id');
    }

    public function map($user): array
    {   
        $data = collect($user->toArray());
        
        collect($this->columns)->each(function($item) use(&$data){
            if(!in_array($item, ['email_address', 'tags', 'audience_id', 'fields']) ){
                $data->put($item,(isset($data->get('fields')[$item]) ? $data->get('fields')[$item] : ''));
            }

            if($item == 'tags' && !empty($data->get('tags')))
                $data->put('tags', implode(',', $data->get('tags')));

            if(empty($data->get($item)))
                $data->put($item, '');
        });

        $data = $data->except(['fields', 'created_at', 'updated_at', 'has_updated']);
        
        return $data->toArray();
    }

    public function headings(): array
    {
        return collect($this->columns)->map(function($item){
            return Str::title(str_replace('_', ' ',$item));
        })->toArray();
    }
}
