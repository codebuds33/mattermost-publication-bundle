<?php

namespace CodeBuds\MattermostPublicationBundle;

use CodeBuds\MattermostPublicationBundle\Model\Message;
use Exception;
use RuntimeException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MattermostPublication
{
    public function __construct(
        private readonly ?string $webhookUrl,
        private readonly ?string $username,
        private readonly ?string $iconUrl,
        private readonly ?string $channel,
    ) {
    }

	/**
	 * @throws TransportExceptionInterface
	 */
    public function publish(Message|string $message): Message
    {
        $message instanceof Message
            ?: $message = (new Message())->setText($message);

        $message->setIconUrl($message->getIconUrl() ?? $this->iconUrl);
        $message->setUsername($message->getUsername() ?? $this->username);
        $message->setChannel($message->getChannel() ?? $this->channel);
        $message->setWebhookUrl($message->getWebhookUrl() ?? $this->webhookUrl);

        $this->publishRequest($message);

				return $message;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    private function publishRequest(Message $message): void
    {
        if ($message->getWebhookUrl() === null) {
            $errors[] = "No webhook URL set for message";
        }

        if ($message->getText() === null) {
            $errors[] = "No text set for message";
        }

        if (isset($errors)) {
            $message = array_reduce($errors, static function ($carry, $error) {
                $carry .= "Error: {$error} ";
                return $carry;
            });
            throw new RuntimeException($message);
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
            throw new RuntimeException("Publication failed, verify the channel and the settings for the webhook", $request->getStatusCode());
        }
    }
}
