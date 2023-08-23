<?php

namespace App\Controllers\Commercial;

use App\Controllers\Commercial\CommercialController;
use App\Models\Blog;

class BlogPage extends CommercialController
{

    private $unauthorizedScheme;

    public function __construct()
    {

        // ** Command line below to use default unauthorized scheme in parent class - from this
        $this->unauthorizedScheme = function ($cbPar = []) {

            if (method_exists($this, '__unauthorizedScheme'))
                return $this->__unauthorizedScheme($cbPar);
        };
        // ** Command line above to use default unauthorized scheme in parent class - to this

        return Parent::__construct([
            'unauthorizedScheme' => $this->unauthorizedScheme
        ]);
    }


    // Function to override default unauthorized scheme in parent class
    private function __unauthorizedScheme($cbPar = [])
    {

        return $this->tryCatch(
            function ($cbPar) {

                // $err = new Error($this->request, $this->response);
                // return $err->error(500);
            },
            $cbPar
        );
    }

    // Server Response
    public function index($id = null)
    {

        if ($id != null) return $this->blogDetail($id);
        return $this->allBlog();
    }

    // Function to show all product category
    public function allBlog()
    {

        return $this->tryCatch(function ($cbPar) {

            $dbBlogList = $this->dbBlog(1);

            $this
                ->prepAddon([
                    'blog_list' => $dbBlogList
                ]);

            return $this->viewPage('blog/index');
        });
    }

    public function blogDetail($id = null)
    {

        return $this->tryCatch(
            function ($cbPar) {

                $dbBlogData = $this->dbBlog(2);
                $dbBlogList = $this->dbBlog(1);

                $this
                    ->prepMeta([
                        'img_url' => cdnURL('image/' . $dbBlogData['thumbnail_path'])
                    ])
                    ->prepAddon([
                        'catalog_slug' => 'product/' . $cbPar['id'] . '/',
                        'blog_data' => $dbBlogData,
                        'blog_list' => $dbBlogList
                    ]);

                return $this->viewPage('blog/detail');
            },
            ['id' => $id]
        );
    }


    ### Function to process data from database
    // Function to get list of blogs
    private function dbBlog(
        $condition = 1,
        $identifier = null
    ) {

        $blogData = new Blog();

        switch ($condition) {

            case 1:

                return $blogData->all([
                    'status' => ['SHOW']
                ]);
                break;

            case 2:

                return $blogData->data([
                    'id' => $identifier,
                    'status' => ['SHOW']
                ]);
                break;

            default:

                return null;
                break;
        }
    }
}
