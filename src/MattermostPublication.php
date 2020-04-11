<?php


namespace CodeBuds\MattermostPublicationBundle;


use CodeBuds\MattermostPublicationBundle\Model\Message;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MattermostPublication
{

    private string $webhookUrl;

    public function __construct(string $webhookUrl)
    {
        if(!$webhookUrl){
            throw new Exception("Please provide the mattermost webhook url");
        }

        if($webhookUrl === 'http://{your-mattermost-site}/hooks/xxx-generatedkey-xxx'){
            throw new Exception("Please provide a real mattermost webhook url");
        }

        $this->webhookUrl = $webhookUrl;
    }

    /**
     * @param Message|string $message
     * @return void
     * @throws TransportExceptionInterface
     */
    public function publish($message)
    {
        $message instanceof Message
            ? $this->publishRequest($message->toArray())
            : $this->publishRequest(['text' => $message]);
    }

    /**
     * @param array $body
     * @return int
     * @throws \Exception
     * @throws TransportExceptionInterface
     */
    private function publishRequest(array $body)
    {
        $client = HttpClient::create();
        $request = $client->request('POST', $this->webhookUrl, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $body
        ]);
        if($request->getStatusCode() !== 200){
            throw new \Exception("Publication failed, verify the channel and the settings for the webhook");
        };
    }

}