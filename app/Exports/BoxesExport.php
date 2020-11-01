<?php

namespace App\Exports;

use App\Box;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BoxesExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('excel.boxes', [
            'boxes' => Box::all()
        ]);
    }
}
