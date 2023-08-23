<?php

namespace App\Models;

use CodeIgniter\Model;

class Account extends Model
{

    protected $table            = 'myu_account';
    protected $primaryKey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['uuid', 'username', 'password', 'email', 'authority', 'registration_data'];
    protected $useAutoIncrement = true;
    protected $skipValidation   = true;
    protected $useTimestamps    = false;

    public function all(
        $find = [],
        $get = 'DATA' | 'LENGTH' | 'SUM'
    ) {

        $result = $this
            ->select("
                myu_account.id AS account_id,
                myu_account.uui AS uuid,
                myu_account.username AS username,
                myu_account.email AS email
            ");

        // Filter by account id
        if (isset($find['account_id'])) $result = $this->where('myu_account.id', $find['account_id']);

        // Filter by username
        if (isset($find['username'])) $result = $this->where('myu_account.username', $find['username']);

        // Filter by password
        if (isset($find['password'])) $result = $this->where('myu_account.password', $find['password']);

        // Filter by email
        if (isset($find['email'])) $result = $this->where('myu_account.email', $find['email']);

        // Filter by utm_code
        if (isset($find['utm_code'])) $result = $this->where('myu_account.utm_code', $find['utm_code']);

        // Filter by uuid
        if (isset($find['uuid'])) $result = $this->where('myu_account.uuid', $find['uuid']);

        switch ($get) {
            case 'SUM':
                $result = $this->select("SUM(myu_account.id) as sum_row")
                    ->first();

                if ($result == null) return 0;
                return $result['sum_row'];
                break;
            case 'COUNT':
                $result = $this->select("COUNT(myu_account.id) as length_row")
                    ->first();

                if ($result == null) return 0;
                return $result['length_row'];
                break;
        }

        $result = $this->find();

        if ($result == null) return null;

        // Parse the column that has JSON value
        foreach ($result as $key => $value) {

            // $value['status'] = json_decode($value['status'], true);

            $result[$key] = $value;
        }

        return $result;
    }

    public function data($identifier = [])
    {

        $result = $this
            ->select("
                myu_account.id AS account_id,
                myu_account.uuid AS uuid,
                myu_account.username AS username,
                myu_account.email AS email,
                myu_account.authority AS authority,
                myu_account.registration_data AS registration_data
            ");

        // Find by id
        if (isset($identifier['id']))
            $result = $this->where("myu_account.id", $identifier['id']);

        // Find by uuid
        if (isset($identifier['uuid']))
            $result = $this->where("myu_account.uuid", $identifier['uuid']);

        if (isset($identifier['username'], $identifier['email'])) {

            // Find by username & email
            $result = $this->where("(myu_account.email = '{$identifier['email']}' OR myu_account.username = '{$identifier['username']}')");
        } else {

            // Find by username
            if (isset($identifier['username']))
                $result = $this->where("myu_account.username", $identifier['username']);

            // Find by email
            if (isset($identifier['email']))
                $result = $this->where("myu_account.email", $identifier['email']);
        }

        // Find by password (With requirement)
        if ((isset($identifier['username']) || isset($identifier['email']) || isset($identifier['uuid']))
            && isset($identifier['password'])
        ) $result = $this->where("myu_account.password", $identifier['password']);

        $result = $this->get()->getResultArray();

        if ($result != null) {

            // Parse the column that has JSON value
            $result = $result[0];
            $result['authority'] = json_decode($result['authority'], true);
            $result['registration_data'] = json_decode($result['registration_data'], true);

            return $result;
        }

        return null;
    }
}
