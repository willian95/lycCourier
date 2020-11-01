<?php

namespace App\Exports;

use App\Recipient;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class RecipientsExport implements FromView, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('excel.recipients', [
            'recipients' => Recipient::all()
        ]);
    }
    
    public function columnFormats(): array
    {
        return [
            'E' => "0"
        ];
        
    }
    
}
