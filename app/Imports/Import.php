<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Import implements ToModel, WithHeadingRow
{
    private $model = null;
    private $fields = null;

    public function __construct($model, $fields)
    {
        if (class_exists("App\Models\\" . $model)) {
            $this->model = app("App\Models\\" . $model);
        }
        $this->fields = $fields;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $entry = [];
        foreach ($this->fields as $eachField) {
            $entry[$eachField] = $row[$eachField];
        }
        return new $this->model($entry);
    }
}
