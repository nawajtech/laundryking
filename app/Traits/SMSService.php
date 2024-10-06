<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait SMSService
{
    protected $BaseUri = 'https://control.msg91.com/api/v5/';
    protected $authkey = '345153A21USUbw3yx05faac6dfP1';

    /**
     * Send a request to any service
     * @return array|string
     */
    public function makeSMSRequest($method, $requestUrl, $bodyType = null, $queryParams = null, $formParams = null, $headers = null, $hasFile = false)
    {
        $client = new Client([
            'verify'  => false,
            'base_uri' => $this->BaseUri,
            'headers' => [
                'accept' => 'application/json',
                'authkey' => $this->authkey,
                'content-type' => 'application/json',
            ],
        ]);
        $bodyType = $bodyType ? $bodyType : 'form_params';

        if ($hasFile) {
            $bodyType = 'multipart';
            $multipart = [];

            foreach ($formParams as $name => $contents) {

                if(file_exists($contents)) {
                    $image_path = $contents->getPathname();
                    $image_mime = $contents->getmimeType();
                    $image_org = $contents->getClientOriginalName();

                    $multipart[] = [
                        'name' => $name,
                        'filename' => $image_org,
                        'Mime-Type' => $image_mime,
                        'contents' => fopen($image_path, 'r'),
                    ];
                } else {
                    $multipart[] = [
                        'name' => $name,
                        'contents' => $contents,
                    ];
                }
            }
        }

        try {
            $response = $client->request($method, $requestUrl, [
                'query' => $queryParams,
                $bodyType => $hasFile ? $multipart : $formParams,
                'headers' => $headers ?? [],
            ]);
        } catch (\Exception $e) {
            dd($e);
            return [];
        }

        return $response->getBody()->getContents();
    }
}
