<?php 

namespace Syllistudio\LineMessaging;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

trait LineWebhookReceiver {

	private $linesignature = 'X-LINE-SIGNATURE';

	/**
     * Line Webhook
     *
     * Receiving request sent by Line Platform 
     *
     * @param Request $request
     * @return Response
	 */
	public function handleWebhook(Request $request) {
		// Validation request sent by Line Platform ?
		if (!$this->signatureValidation($request)) {
			return response()->json(['success' => false, 'message' => 'Unauthorized'], 200);
		}

		// Handle events
		$events = $request->input('events', '');
		$this->handleEvent($events);

		return response()->json(['success' => true], 200);
	}

	/**
	 * You need to implement all this method when you include this trait
	 *
	 */
	abstract function onMessageEvent($replyToken, $source, $message, $timestamp);
	abstract function onFollowEvent($replyToken, $source, $timestamp);
	abstract function onUnfollowEvent($source, $timestamp);
	abstract function onJoinEvent($replyToken, $source, $timestamp);
	abstract function onLeaveEvent($source, $timestamp);

	/**
	 * Signature validation
	 * 
	 * The signature in the X-Line-Signature request 
	 * header must be verified to confirm that 
	 * the request was sent from the LINE Platform
	 * 
	 * @param Request $request
	 * @return bool
	 */
	private function signatureValidation(Request $request) {
		$channelSecret = config('line-messaging.channel_secret'); // Channel secret string
		$httpRequestBody = $request->getContent(); // Request body string
		$signature = $request->header($this->linesignature);

		if (empty($signature)) {
			throw new \Exception("Signature must not empty");
		}

		return hash_equals(base64_encode(hash_hmac('sha256', $httpRequestBody, $channelSecret, true)), $signature);
	}

	/**
	 * Handle Event
	 *
	 * @param array $event
	 * @return void
	 */
	private function handleEvent(array $events) {
		if (empty($events)) return;

		foreach ($events as $event) {
			// common args
			$type = $event['type'];
			$timestamp = $event['timestamp'];
			$source = $event['source'];
			
			if ($type == 'message') {
				$this->onMessageEvent($event['replyToken'], $source, $event['message'], $timestamp);
			}
			elseif ($type == 'follow') {
				$this->onFollowEvent($event['replyToken'], $source, $timestamp);
			}
			elseif ($type == 'unfollow') {
				$this->onUnfollowEvent($source, $timestamp);
			}
			elseif ($type == 'join') {
				$this->onJoinEvent($event['replyToken'], $source, $timestamp);
			}
			elseif ($type == 'leave') {
				$this->onLeaveEvent($source, $timestamp);
			}
		}
	}
}