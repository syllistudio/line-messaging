<?php 
/**
 *	Line Manager
 *
 *	@link https://github.com/line/line-bot-sdk-php/blob/master/src/LINEBot/HTTPClient/CurlHTTPClient.php
 */

namespace Syllistudio\LineMessaging\Manager;

trait LineManagerTrait {

	/**
     * Sends GET request to LINE Messaging API.
     *
     * @param string $url Request URL.
     * @return Response Response of API request.
     */
	public function get($url) {
		return $this->sendRequest('GET', $url, [], []);
	}

	/**
     * Sends POST request to LINE Messaging API.
     *
     * @param string $url Request URL.
     * @param array $data Request body.
     * @return Response Response of API request.
     */
	public function post($url, array $data) {
		return $this->sendRequest('POST', $url, ['Content-Type: application/json; charset=utf-8'], $data);
	}

	/**
     * @param string $method
     * @param string $url
     * @param array $additionalHeader
     * @param array $reqBody
     * @return Response
     * @throws CurlExecutionException
     */
	private function sendRequest($method, $url, array $additionalHeader, array $reqBody) {
		$curl = new Curl($url);

		$headers = array_merge([
			'Authorization: Bearer ' . config('line-messaging.channel_access_token')
		], $additionalHeader);

		$options = [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_HEADER => true,
        ];

        if ($method === 'POST') {
            if (empty($reqBody)) {
                $options[CURLOPT_HTTPHEADER][] = 'Content-Length: 0';
            } else {
                $options[CURLOPT_POSTFIELDS] = json_encode($reqBody);
            }
        }

        $curl->setoptArray($options);
        $result = $curl->exec();
        if ($curl->errno()) {
            throw new CurlExecutionException($curl->error());
        }
        $info = $curl->getinfo();
        $httpStatus = $info['http_code'];

        return [true, $result, $httpStatus];
	}
}