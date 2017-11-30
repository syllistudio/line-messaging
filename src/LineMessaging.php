<?php

namespace Syllistudio\LineMessaging;

use Syllistudio\LineMessaging\Manager\LineManagerTrait;
use Syllistudio\LineMessaging\MessageBuilder\MessageBuilder;

trait LineMessaging {
	use LineManagerTrait;

	private $api_url = 'https://api.line.me/v2/bot/message/';
	
	/**
	 * Send Reply Message
	 * 
	 * @param string $replyToken received via webhook
	 * @param MessageBuilder $message
	 * @return Response status code 200 and an empty JSON object.
	 */
	public function replyMessage($replyToken, MessageBuilder $messages) {
		$url = $this->api_url . 'reply';
		return $this->post($url, array(
			'replyToken' => $replyToken,
			'messages'   => $messages->buildMessage()
		));
	}

	/**
	 * Send Push Message
	 * 
	 * @param string $to ID of the target recipient. Use a userId, groupId, or roomId value returned in a webhook event object.
	 * @param MessageBuilder $message
	 * @return Response status code 200 and an empty JSON object.
	 */
	public function pushMessage($to, MessageBuilder $messages) {
		$url = $this->api_url . 'push';
		return $this->post($url, array(
			'to' => $to,
			'messages'   => $messages->buildMessage()
		));
	}

	/**
	 * Send Multicast Messages
	 * 
	 * @param array $to array of user IDs. Use userId values which are returned webhook event objects.
	 * @param MessageBuilder $message
	 * @return Response status code 200 and an empty JSON object.
	 */
	public function multicastMessages(array $to, MessageBuilder $messages) {
		$url = $this->api_url . 'multicast';
	}
}