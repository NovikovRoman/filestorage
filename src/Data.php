<?php

namespace FileStorage;

use FileStorage\Exceptions\DataException;

class Data
{
    /**
     * @param mixed $data
     * @return false|string
     * @throws DataException
     */
    public function encode($data)
    {
        $data = json_encode($data);
        if ($data === false) {
            throw new DataException($this->getError());
        }
        return $data;
    }

    /**
     * @param string $data
     * @param bool $asArray
     * @return mixed
     * @throws DataException
     */
    public function decode($data, $asArray = true)
    {
        $data = $asArray ? json_decode($data, true) : json_decode($data);
        if ($data === false) {
            throw new DataException($this->getError());
        }
        return $data;
    }

    private function getError()
    {
        $error = 'JSON: ';
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error .= 'No errors';
                break;
            case JSON_ERROR_DEPTH:
                $error .= 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error .= 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error .= 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $error .= 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $error .= 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $error .= 'Unknown error';
                break;
        }
        return $error;
    }
}