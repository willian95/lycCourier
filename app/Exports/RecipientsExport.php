<?php

namespace App\Exports;

use App\Recipient;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RecipientsExport implements FromView
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
}
