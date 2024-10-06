<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\YourModel;

class DataExport implements FromCollection
{
    public function collection()
    {
        return Customer::all();
    }
}

