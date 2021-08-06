<?php

namespace App\Extra;

use Maatwebsite\Excel\Concerns\ToModel;
use Modules\Campaign\models\Campaign;

class CampaignImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Campaign([

        ]);
    }
}
