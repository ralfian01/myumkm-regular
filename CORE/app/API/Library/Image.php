<?php

namespace App\API\Library;

use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Files\File;

/**
 * Class to process image file
 */
class Image
{

    private string $baseDirectory = '';
    private string $directoryTarget = '';
    private string $fullBaseDirectory = '';
    private object $source;
    private $imageSave = [
        'name' => '',
        'prefix' => '',
        'extension' => ''
    ];

    /**
     * Tells where the source image files to be processed are located
     * 
     * @param UploadedFile $source
     * @return mixed
     */
    public function __construct(UploadedFile $source)
    {
        // Set source file
        $this->sourceFile($source);

        // Default value
        $this->setBaseDirectory(self::defaultBaseDirectory());
        $this->setImageName(str_replace('.', '', microtime(true)), 'MYU');
        $this->extraCompress(false);

        return $this;
    }

    /**
     * Tells where the source image files to be processed are located
     * 
     * @param string $dir
     * @return mixed
     */
    private function sourceFile(UploadedFile $source)
    {

        if (!file_exists($source->getPathname()))
            throw new \Error("Image file not found in {$source->getPathName()}");

        $this->source = $source;
        return $this;
    }

    /**
     * Function to get directory target
     * 
     * @return string
     */
    public static function defaultBaseDirectory()
    {
        return WRITEPATH . "..\assets\image";
    }

    /**
     * Function to get directory target
     * 
     * @return string
     */
    public function getBaseDirectory()
    {
        return $this->baseDirectory;
    }

    /**
     * Set base directory
     * 
     * @param string $dir
     * @return mixed
     */
    public function setBaseDirectory(string $dir)
    {
        // Reformat directory path writing
        $dir = str_replace('\\', '/', $dir);
        if (substr($dir, -1) == '/') $dir = substr($dir, 0, -1);

        // If directory does not exist
        if (!file_exists($dir)) {

            mkdir($dir); // Create directory

            // Check directory again
            if (!file_exists($dir)) throw new \Error("Failed to create directory {$dir}");
        }

        $this->baseDirectory = $dir;
        return $this;
    }

    /**
     * Tell the system in which directory to save processed files
     * 
     * @param string $dir
     * @return mixed
     */
    public function directoryTarget(string $dir)
    {

        // Reformat directory path writing
        $dir = str_replace('\\', '/', $dir);
        if (substr($dir, -1) == '/') $dir = substr($dir, 0, -1);

        // If directory does not exist
        if (!file_exists("{$this->baseDirectory}/{$dir}")) {

            mkdir("{$this->baseDirectory}/{$dir}"); // Create directory

            // Check directory again
            if (!file_exists("{$this->baseDirectory}/{$dir}"))
                throw new \Error("Failed to create directory \"{$dir}\" in {$this->baseDirectory}");
        }

        $this->fullBaseDirectory = "{$this->baseDirectory}/{$dir}";
        $this->directoryTarget = $dir;
        return $this;
    }

    /**
     * Function to get directory target
     * 
     * @return string
     */
    public function getDirectoryTarget()
    {
        return $this->directoryTarget;
    }

    /**
     * Function to set name and prefix of image file when it saved
     * 
     * @param string $name
     * @return mixed
     */
    public function setImageName(string $name, string $prefix = null)
    {
        $this->imageSave['name'] = $name;
        $this->imageSave['prefix'] = $prefix ?? $this->imageSave['prefix'];

        return $this;
    }

    private const ext_mime = [
        'webp' => IMAGETYPE_WEBP,
        'jpeg' => IMAGETYPE_JPEG,
        'jpg' => IMAGETYPE_JPEG,
        'png' => IMAGETYPE_PNG,
        'gif' => IMAGETYPE_PNG,
    ];
    private const ext_support = ['webp', 'jpeg', 'jpg', 'png', 'gif'];

    /** 
     * Function to set format of image file when it saved
     * 
     * @param string $format
     * @return mixed
     */
    public function setImageExtension(string $format)
    {
        if (!in_array(strtolower($format), self::ext_support))
            throw new \Error("Can only choose one extension between " . implode(', ', self::ext_support));

        $this->imageSave['extension'] = strtolower($format);
        return $this;
    }


    /**
     * @var array $compress
     * 
     * @limit: Limit the size of the image file to be compressed
     * @percentage: Files that exceed the limit will be compressed by the specified percentage
     * @extra: Compress image files until they are under the specified limiter 
     */
    private $compress = [
        'limit' => null,
        'percentage' => null,
        'extra' => false,
    ];

    /**
     * Compress the image file if its size exceeds the specified limit
     * 
     * @param int $limit Compression limit in MB
     * @param int|float $compress Compression percentage (from 0.1 to 1.0)
     * @return mixed
     */
    public function setCompressSize(int $limit = 1, $compress = 0.5)
    {
        if ($limit < 0.1)
            throw new \Error("Compress limit must be more than 1");

        if ($compress < 0.1)
            throw new \Error("Compress percentage must be more than 0.1");

        $this->compress['limit'] = ($limit * (1024 * 1024));
        $this->compress['percentage'] = $compress;
        return $this;
    }

    /**
     * Function to set to use extra compress method or not
     * 
     * @param bool $active
     * @return mixed
     */
    public function extraCompress(bool $active)
    {
        $this->compress['extra'] = $active;
        return $this;
    }

    /**
     * Serves to resize images based on a predetermined compress size
     * 
     * @param array $imageSize
     * @param variable $newSize
     * @return array
     */
    private function resizeImage(array $imageSize, &$newSize)
    {
        if (isset($this->compress['limit'])) {

            // If image size too big
            if ($this->source->getSizeByUnit() > $this->compress['limit']) {

                // Resize image to smaller size
                $imageSize['width'] *= $this->compress['percentage'];
                $imageSize['height'] *= $this->compress['percentage'];
            }
        }

        $newSize = [
            'width' => $imageSize['width'],
            'height' => $imageSize['height']
        ];
    }


    public const START = 'START';
    public const END = 'END';
    public const CENTER = 'CENTER';

    /**
     * Function to crop image into a square
     * 
     * @param string $position
     * @return mixed
     */
    public function cropSquare($position = self::CENTER)
    {

        // 
    }

    /**
     * Start save image
     * 
     * @return mixed
     */
    public function save()
    {
        // Check image
        $this->check();

        // Set image file name, prefix, and extension (Prefix_Imagename.extension)
        $imgName = "{$this->imageSave['name']}.{$this->imageSave['extension']}";
        if (!empty($this->imageSave['prefix'])) $imgName = "{$this->imageSave['prefix']}_{$imgName}";

        $status = false;
        $loop = 0;
        $source = $this->source->getPathName();

        do {
            $image = Services::image();
            $image->withFile($source); // Image source

            // Save image in progress mode
            $protoName = "[proto-{$loop}]{$imgName}";

            // Resize image
            $this->resizeImage($image->getFile()->getProperties(true), $size);

            // Save image settings
            $image->flatten(255, 255, 255);
            $image->resize($size['width'], $size['height'], true, 'height');
            $image->convert(self::ext_mime[$this->imageSave['extension']]);
            $status = $image->save("{$this->fullBaseDirectory}/{$protoName}");

            // Delete previous prog image
            if ($loop >= 1) {

                $prevProtoName = "[proto-" . ($loop - 1) . "]{$imgName}";
                self::delete("{$this->fullBaseDirectory}/{$prevProtoName}");
            }

            $source = "{$this->fullBaseDirectory}/{$protoName}";

            // Check saved image size
            if (((new File($source))->getSizeByUnit() < $this->compress['limit'])
                || !$this->compress['extra']
            ) {

                rename(
                    "{$this->fullBaseDirectory}/{$protoName}",
                    "{$this->fullBaseDirectory}/{$imgName}"
                );
                break; // Stop the loop
            }

            $loop++;
        } while ($this->compress['extra']);

        $return = new \stdClass();
        $return->status = $status;
        $return->saveDirectory = $this->directoryTarget;
        $return->baseDirectory = $this->baseDirectory;
        $return->imageName = $imgName;
        $return->imageLocation = "{$this->fullBaseDirectory}/{$imgName}";
        return $return;
    }

    // Check image
    private function check()
    {

        // Check image source
        if (empty($this->source))
            throw new \Error("The source image file cannot be empty");

        // Check image file extension
        if (empty($this->imageSave['extension'])) {
            $parts = explode('.', $this->source->getName());
            $this->setImageExtension(end($parts));
        }

        return $this;
    }

    /**
     * Function to delete image file
     * 
     * @param string $path
     * @return bool
     */
    public static function delete(string $path)
    {

        if (file_exists($path))
            unlink($path);

        // Check if the file has been deleted
        return !file_exists($path);
    }
}
