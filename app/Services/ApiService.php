<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

abstract class ApiService {
    public $name = '';
    protected $fields = [];
    public $validation = [];

    abstract protected function handle(Model $model);

    public function getName()
    {
        return !empty($this->name) ? $this->name : __CLASS__;
    }

    public function getFields()
    {
        $fields = $this->fields;

        foreach ($fields as $key => $value) {
            if (is_string($value)) {
                $fields[$key] = [
                    'type' => $value
                ];
            }

            if (!empty($value['data'])) {
                $fields[$key]['data'] = $this->getData();
            }
        }

        return $fields;
    }

    protected function initCurl(string $url = null, $headers = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate'); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        return $ch;
    }

    protected function throwException(string $classname, string $message, string $result = null, array $curlInfo = null)
    {
        throw new \Exception(json_encode([
            '__CLASS__' => $classname,
            'message' => $message,
            'result' => $result,
            'curlInfo' => json_encode($curlInfo),
        ]));
    }

    protected function execute($ch, $timeout = 5, $try = 5)
    {
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        $result = null;

        do {
            $result = curl_exec($ch);
            $try--;
        } while (empty($result) && $try > 0);

        return trim($result);
    }
}
