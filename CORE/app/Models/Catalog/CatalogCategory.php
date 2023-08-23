<?php

namespace App\Models\Catalog;

use CodeIgniter\Model;

class CatalogCategory extends Model
{

    protected $table            = 'myu_catalog_category';
    protected $primaryKey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['category_name', 'type', 'slug', 'description', 'image_path'];
    protected $useAutoIncrement = true;
    protected $skipValidation   = true;
    protected $useTimestamps    = false;

    public function all(
        $find = [],
        $get = 'DATA' | 'LENGTH' | 'SUM'
    ) {

        $result = $this
            ->select("
                CONCAT('[', GROUP_CONCAT(JSON_OBJECT(
                    'category_id', myu_catalog_category.id,
                    'category_name', myu_catalog_category.category_name,
                    'slug', myu_catalog_category.slug,
                    'description', myu_catalog_category.description,
                    'image_path', myu_catalog_category.image_path
                )), ']') AS list,
                myu_catalog_category.type AS catalog_type
            ")
            ->groupBy("myu_catalog_category.type");

        // Filter by type
        if (isset($find['type'])) {

            if (!is_array($find['type'])) $find['type'] = [$find['type']];
            $result = $this->whereIn("myu_catalog_category.type", $find['type']);
        }

        // Filter by slug
        if (isset($find['slug'])) {

            if (!is_array($find['slug'])) $find['slug'] = [$find['slug']];
            $result = $this->whereIn("myu_catalog_category.slug", $find['slug']);
        }

        // Filter by catalog category name
        if (isset($find['name'])) {

            $result = $this->like("myu_catalog_category.category_name", $find['name']);
        }

        switch ($get) {
            case 'SUM':
                $result = $this->select("SUM(myu_catalog_category.id) as sum_row")
                    ->groupBy("myu_catalog_category.type")
                    ->first();

                if ($result == null) return 0;
                return $result['sum_row'];
                break;
            case 'COUNT':
                $result = $this->select("COUNT(myu_catalog_category.id) as length_row")
                    ->groupBy("myu_catalog_category.type")
                    ->first();

                if ($result == null) return 0;
                return $result['length_row'];
                break;
        }

        $result = $this->find();

        if ($result == null) return null;

        // Parse the column that has JSON value
        foreach ($result as $key => $value) {

            $value['list'] = json_decode($value['list'], true);

            $result[strtolower($value['catalog_type'])] = $value;

            // Remove group key that use number
            unset($result[$key]);
        }

        return $result;
    }

    public function data($identifier)
    {

        $result = $this
            ->select("
                myu_catalog_category.id AS category_id,
                myu_catalog_category.category_name AS category_name,
                myu_catalog_category.slug AS slug,
                myu_catalog_category.description AS description,
                myu_catalog_category.image_path AS image_path,
                myu_catalog_category.type AS catalog_type
            ");

        // Find by id
        if (isset($identifier['id'])) {

            $result = $this->where("myu_catalog_category.id", $identifier['id']);
        }

        // Find by slug
        if (isset($identifier['slug'])) {

            $result = $this->where("myu_catalog_category.slug", $identifier['slug']);
        }

        $result = $this->get()->getResultArray();

        if ($result != null) {

            // Parse the column that has JSON value
            $result = $result[0];

            return $result;
        }

        return $result;
    }
}
