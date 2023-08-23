<?php

namespace App\Controllers\CDN;

use App\Controllers\ControllerPreProcessor;
use CodeIgniter\Files\File;

class Files extends ControllerPreProcessor
{

    public function index()
    {

        // Set header rule
        Parent::CORS();
        $path = WRITEPATH . '../assets/' . $this->getFullPath();

        // Validate path
        if (!$this->validatePath($path))
            return $this->response
                ->setStatusCode(400)
                ->setBody('File not found');

        $file = $this->getFile($path);
        return $this->response
            ->setStatusCode(200)
            ->setContentType($file->mimeType)
            ->setBody($file->file);
    }

    /**
     * Function to compile url segments
     * @return string
     */
    private function getFullPath()
    {

        $URISegment = $this->request->getUri()->getSegments();
        $path = null;

        foreach ($URISegment as $part) {

            if (!in_array($part, ['myu_cdn']))
                $path .= $path == null ? $part : "/{$part}";
        }

        return $path;
    }

    /**
     * Function to validate CDN URL path
     * @param string $path
     * 
     * @return bool
     */
    private function validatePath(string $path)
    {

        return file_exists($path);
    }

    /**
     * Function to get file
     * @param string $path
     * 
     * @return object
     */
    private function getFile(string $path)
    {

        $file = new File($path);
        $ext = last_item(explode('.', $file->getFilename()));
        $mime = last_item(explode('/', $file->getMimeType()));

        $mimeType = $file->getMimeType();
        if ($mime == 'plain') $mimeType = str_replace($mime, $ext, $mimeType);

        $return = new \stdClass();
        $return->file = file_get_contents($path);
        $return->mimeType = $mimeType;
        return $return;
    }
}
