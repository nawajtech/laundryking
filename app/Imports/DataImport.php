<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Data; // Replace with your model

class DataImport implements ToModel
{
    public function model(array $row)
    {
        // Implement your logic here to process each row of data
        return new Data([
            'column1' => $row[0],
            'column2' => $row[1],
            // ... map other columns
        ]);
    }
}
