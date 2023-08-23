<?php

namespace App\Models;

use CodeIgniter\Model;

class Blog extends Model
{

    protected $table            = 'myu_blog';
    protected $primaryKey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['author_id', 'thumbnail_page', 'tags', 'status', 'title', 'content', 'date', 'reputation'];
    protected $useAutoIncrement = true;
    protected $skipValidation   = true;
    protected $useTimestamps    = false;


    public function all(
        $find = [],
        $get = 'DATA' | 'LENGTH' | 'SUM'
    ) {

        $result = $this
            ->select("
                myu_blog.thumbnail_path AS thumbnail_path,
                myu_blog.tags AS tags,
                myu_blog.status AS status,
                myu_blog.title AS title,
                myu_blog.content AS content,
                myu_blog.date AS date,
                myu_blog.reputation AS reputation
            ");

        // Filter by status
        if (isset($find['status'])) {

            if (!is_array($find['status'])) $find['status'] = [$find['status']];

            $result = $this->whereIn("myu_blog.status", $find['status']);
        }

        // Filter by tags
        if (isset($find['tags'])) {

            if (!is_array($find['tags'])) $find['tags'] = [$find['tags']];

            $result = $this->whereIn("myu_blog.tags", $find['tags']);
        }

        // Filter by title
        if (isset($find['title'])) {

            $result = $this->like("myu_blog.title", $find['title']);
        }

        switch ($get) {
            case 'SUM':
                $result = $this->select("SUM(myu_blog.id) as sum_row")
                    // ->groupBy("myu_blog.type")
                    ->first();

                if ($result == null) return 0;
                return $result['sum_row'];
                break;
            case 'COUNT':
                $result = $this->select("COUNT(myu_blog.id) as length_row")
                    // ->groupBy("myu_blog.type")
                    ->first();

                if ($result == null) return 0;
                return $result['length_row'];
                break;
        }

        $result = $this->find();

        if ($result == null) return null;

        // Parse the column that has JSON value
        foreach ($result as $key => $value) {

            // $value['list'] = json_decode($value['list'], true);

            // $result[strtolower($value['catalog_type'])] = $value;
        }

        return $result;
    }

    public function data($identifier = [])
    {

        $result = $this
            ->select("
                myu_blog.thumbnail_path AS thumbnail_path,
                myu_blog.tags AS tags,
                myu_blog.status AS status,
                myu_blog.title AS title,
                myu_blog.content AS content,
                myu_blog.date AS date,
                myu_blog.reputation AS reputation
            ");

        // Find by id
        if (isset($identifier['id'])) {

            $result = $this->where("myu_blog.id", $identifier['id']);
        }

        $result = $this->get()->getResultArray();

        if ($result != null) return $result[0];

        return $result;
    }
}
