<?php

namespace App\Exports;

use App\Shipping;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ShippingsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function forFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
        
        return $this;
    }

    public function forToDate($toDate)
    {
        $this->toDate = $toDate;
        
        return $this;
    }

    public function view(): View
    {
        return view('excel.shippings', [
            'shippings' => Shipping::whereDate('created_at', '>=', $this->fromDate)->whereDate("created_at", '<=', $this->toDate)->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['recipient' => function ($q) {
                $q->withTrashed();
            }])->get()
        ]);
    }
}
