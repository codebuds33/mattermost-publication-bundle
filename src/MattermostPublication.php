<?php


namespace CodeBuds\MattermostPublicationBundle;


use CodeBuds\MattermostPublicationBundle\Model\Message;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MattermostPublication
{
	public function __construct(
		private ?string $webhookUrl,
		private ?string $username,
		private ?string $iconUrl,
		private ?string $channel,
	)
	{
	}

	/**
	 * @param Message|string $message
	 * @throws TransportExceptionInterface
	 */
	public function publish(Message|string $message): void
	{
		$message instanceof Message
			?: $message = (new Message())->setText($message);

		$message->setIconUrl($message->getIconUrl() ?? $this->iconUrl);
		$message->setUsername($message->getUsername() ?? $this->username);
		$message->setChannel($message->getChannel() ?? $this->channel);
		$message->setWebhookUrl($message->getWebhookUrl() ?? $this->webhookUrl);

		$this->publishRequest($message);
	}

	/**
	 * @param Message $message
	 * @return void
	 * @throws TransportExceptionInterface
	 * @throws Exception
	 */
	private function publishRequest(Message $message)
	{
		if ($message->getWebhookUrl() === null) {
			$errors[] = "No webhook URL set for message";
		}

		if ($message->getText() === null) {
			$errors[] = "No text set for message";
		}

		if (isset($errors)) {
			$message = array_reduce($errors, function ($carry, $error) {
				$carry .= "Error: {$error} ";
				return $carry;
			});
			throw new Exception($message);
		}

		$request = HttpClient::create()
			->request(
				'POST',
				$message->getWebhookUrl(),
				[
					'headers' =>
						[
							'Content-Type' => 'application/json',
						],
					'json' => $message->toArray()
				]
			);

		if ($request->getStatusCode() !== 200) {
			throw new Exception("Publication failed, verify the channel and the settings for the webhook");
		};
	}

}
