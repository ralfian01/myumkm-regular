<?php

namespace App\Models\Catalog;

use CodeIgniter\Model;

class Catalog extends Model
{

    protected $table            = 'myu_catalog';
    protected $primaryKey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['category_id', 'name', 'description', 'image_path', 'price', 'status'];
    protected $useAutoIncrement = true;
    protected $skipValidation   = true;
    protected $useTimestamps    = false;


    public function all(
        $find = [],
        $get = 'DATA' | 'LENGTH' | 'SUM'
    ) {

        $result = $this
            ->select("
                myu_catalog.id AS catalog_id,
                myu_catalog.name AS catalog_name,
                myu_catalog.description AS description,
                myu_catalog.image_path AS image_path,
                myu_catalog.price AS price,
                myu_catalog.status AS status
            ");

        // Filter by status
        if (isset($find['status'])) {

            if (!is_array($find['status'])) $find['status'] = [$find['status']];

            $result = $this->whereIn("myu_catalog.status", $find['status']);
        }

        // Filter by category_id
        if (isset($find['category_id'])) {

            if (!is_array($find['category_id'])) $find['category_id'] = [$find['category_id']];

            $result = $this->whereIn("myu_catalog.category_id", $find['category_id']);
        }

        // Filter by catalog_slug
        if (isset($find['category_slug'])) {

            if (!is_array($find['category_slug'])) $find['category_slug'] = [$find['category_slug']];

            // Convert array to string
            $find['category_slug'] = "'" . implode("', '", $find['category_slug']) . "'";

            $result = $this->where("
                myu_catalog.category_id IN (
                    SELECT myu_catalog_category.id
                    FROM myu_catalog_category

                    WHERE myu_catalog_category.slug IN ({$find['category_slug']})
                )
            ");
        }

        // Filter by catalog name
        if (isset($find['name'])) {

            $result = $this->like("myu_catalog.name", $find['name']);
        }

        // Filter by keyword
        if (isset($find['keyword'])) {

            $result = $this
                ->like("myu_catalog.name", $find['keyword'])
                ->like("myu_catalog.description", $find['keyword']);
        }

        switch ($get) {
            case 'SUM':
                $result = $this->select("SUM(myu_catalog.id) as sum_row")
                    ->groupBy("myu_catalog.type")
                    ->first();

                if ($result == null) return 0;
                return $result['sum_row'];
                break;
            case 'COUNT':
                $result = $this->select("COUNT(myu_catalog.id) as length_row")
                    ->groupBy("myu_catalog.type")
                    ->first();

                if ($result == null) return 0;
                return $result['length_row'];
                break;
        }

        $result = $this->find();

        if ($result == null) return null;

        // Parse the column that has JSON value
        foreach ($result as $key => $value) {

            $value['image_path'] = json_decode($value['image_path'], true);

            $result[$key] = $value;
        }

        return $result;
    }

    public function data($identifier = [])
    {

        $result = $this
            ->select("
                myu_catalog.id AS catalog_id,
                myu_catalog.name AS catalog_name,
                myu_catalog.description AS description,
                myu_catalog.image_path AS image_path,
                myu_catalog.price AS price,
                myu_catalog.status AS status,
                myu_catalog_category.id AS category_id,
                myu_catalog_category.category_name AS category_name,
                myu_catalog_category.slug AS category_slug
            ")
            ->join("myu_catalog_category", "myu_catalog_category.id = myu_catalog.category_id", "left");

        // Find by id
        if (isset($identifier['id'])) {

            $result = $this->where("myu_catalog.id", $identifier['id']);
        }

        $result = $this->get()->getResultArray();

        if ($result != null) {

            // Parse the column that has JSON value
            $result = $result[0];
            $result['image_path'] = json_decode($result['image_path'], true);
        }

        return $result;
    }
}
