<?php

namespace App\Exports;

use App\CheckInCheckOut;
use Maatwebsite\Excel\Concerns\FromCollection;

class CheckinCheckoutExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CheckInCheckOut::all();
    }
}
