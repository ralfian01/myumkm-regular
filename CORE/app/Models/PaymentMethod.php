<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethod extends Model
{

    protected $table            = 'myu_payment_method';
    protected $primaryKey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['method', 'name', 'number', 'bank_name'];
    protected $useAutoIncrement = true;
    protected $skipValidation   = true;
    protected $useTimestamps    = false;


    public function all(
        $find = [],
        $get = 'DATA' | 'LENGTH' | 'SUM'
    ) {

        $result = $this
            ->select("
                myu_payment_method.id AS payment_method_id,
                myu_payment_method.method AS method,
                myu_payment_method.name AS name,
                myu_payment_method.number AS number,
                myu_payment_method.bank_name AS bank_name
            ");

        // Filter by method
        if (isset($find['method'])) {

            if (!is_array($find['method'])) $find['method'] = [$find['method']];

            $result = $this->whereIn("myu_payment_method.method", $find['method']);
        }

        // Filter by name
        if (isset($find['name'])) {

            if (!is_array($find['name'])) $find['name'] = [$find['name']];

            $result = $this->whereIn("myu_payment_method.name", $find['name']);
        }

        // Filter by number
        if (isset($find['number'])) {

            if (!is_array($find['number'])) $find['number'] = [$find['number']];

            $result = $this->whereIn("myu_payment_method.number", $find['number']);
        }

        switch ($get) {
            case 'SUM':
                $result = $this->select("SUM(myu_payment_method.id) as sum_row")
                    ->groupBy("myu_payment_method.type")
                    ->first();

                if ($result == null) return 0;
                return $result['sum_row'];
                break;
            case 'COUNT':
                $result = $this->select("COUNT(myu_payment_method.id) as length_row")
                    ->groupBy("myu_payment_method.type")
                    ->first();

                if ($result == null) return 0;
                return $result['length_row'];
                break;
        }

        $result = $this->find();

        if ($result == null) return null;

        // Parse the column that has JSON value
        foreach ($result as $key => $value) {

            $result[$key] = $value;
        }

        return $result;
    }

    public function data($identifier = [])
    {

        $result = $this
            ->select("
                myu_payment_method.id AS payment_method_id,
                myu_payment_method.method AS method,
                myu_payment_method.name AS name,
                myu_payment_method.number AS number,
                myu_payment_method.bank_name AS bank_name
            ");

        // Find by id
        if (isset($identifier['id'])) {

            $result = $this->where("myu_payment_method.id", $identifier['id']);
        }

        $result = $this->get()->getResultArray();

        if ($result != null) {

            // Parse the column that has JSON value
            $result = $result[0];
        }

        return $result;
    }
}
