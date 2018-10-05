<?php

namespace FileStorage;

use FileStorage\Exceptions\DataException;
use FileStorage\Exceptions\NotFoundException;

class FileStorage
{
    private $pathStorage;
    private $hash;

    /**
     * FileStorage constructor.
     * @param  string $pathStorage
     * @throws DataException
     */
    public function __construct($pathStorage)
    {
        $this->createDir($pathStorage);
        $pathStorage .= substr($pathStorage, -1) === DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR;
        $this->pathStorage = $pathStorage;
    }

    public function has($name)
    {
        $this->getHash($name);
        try {
            $res = file_exists($this->getPath());
        } catch (DataException $e) {
            return false;
        }
        return $res;
    }

    /**
     * @param string $name
     * @param string|array $data
     * @throws DataException
     */
    public function save($name, $data)
    {
        $this->getHash($name);
        $d = new Data();
        $bytes = file_put_contents($this->getPath(), $d->encode($data));
        if ($bytes === false) {
            throw new DataException('Failed to save data');
        }
    }

    /**
     * @param $name
     * @param bool $asArray
     * @return mixed
     * @throws DataException
     * @throws NotFoundException
     */
    public function load($name, $asArray = true)
    {
        $this->getHash($name);
        $path = $this->getPath();
        if (file_exists($path)) {
            $fileString = file_get_contents($path);
            if ($fileString === false) {
                throw new DataException('Failed to get data');
            }
            $data = new Data();
            return $data->decode($fileString, $asArray);
        }
        throw new NotFoundException('Data not found');
    }


    private function getHash($name)
    {
        $this->hash = sha1($name);
        return $this->hash;
    }

    /**
     * @return string
     * @throws DataException
     */
    private function getPath()
    {
        $path = $this->pathStorage . substr(md5($this->hash), 0, 3) . DIRECTORY_SEPARATOR;
        $this->createDir($path);
        $path .= $this->hash;
        return $path;
    }

    /**
     * @param $path
     * @throws DataException
     */
    private function createDir($path)
    {
        if (!@mkdir($path, 0700, true) && !is_dir($path)) {
            throw new DataException('Can not create directory ' . $path);
        }
    }
}