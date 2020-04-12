<?php


namespace CodeBuds\MattermostPublicationBundle;


use CodeBuds\MattermostPublicationBundle\Model\Message;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MattermostPublication
{

    private string $webhookUrl;

    private ?string $username;

    private ?string $iconUrl;

    private ?string $channel;

    public function __construct(string $webhookUrl, ?string $username, ?string $iconUrl, ?string $channel)
    {
        $this->webhookUrl = $webhookUrl;
        $this->username = $username;
        $this->iconUrl = $iconUrl;
        $this->channel = $channel;
    }

    /**
     * @param Message|string $message
     * @return void
     * @throws TransportExceptionInterface
     */
    public function publish($message)
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
     * @throws \Exception
     */
    private function publishRequest(Message $message)
    {
        if($message->getWebhookUrl() === null){
            $errors[]= "No webhook URL set for message";
        }

        if($message->getText() === null){
            $errors[]= "No text set for message";
        }

        if(isset($errors)) {
            $message = array_reduce($errors, function($carry, $error){
                $carry .= "Error: {$error} ";
                return $carry;
            });
            throw new \Exception($message);
        }

        $client = HttpClient::create();
        $request = $client->request('POST', $message->getWebhookUrl(), [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $message->toArray()
        ]);
        if($request->getStatusCode() !== 200){
            throw new \Exception("Publication failed, verify the channel and the settings for the webhook");
        };
    }

}