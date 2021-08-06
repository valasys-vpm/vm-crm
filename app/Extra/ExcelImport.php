<?php

namespace App\Extra;

use Maatwebsite\Excel\Concerns\ToModel;
use Modules\Campaign\models\Campaign;

class ExcelImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Campaign([
            'name'     => $row['name'],
            'introduction'    => $row['introduction'],
            'location'    => $row['location'],
            'cost'    => $row['cost']
        ]);
    }
}
