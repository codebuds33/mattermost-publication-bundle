<?php


namespace CodeBuds\MattermostPublicationBundle;


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

        $this->webhookUrl = $webhookUrl;
    }

    public function publish(string $message)
    {
        $client = HttpClient::create();

        try{
            $request = $client->request('POST', $this->webhookUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'text' => $message
                ]
            ]);
            return $request->getStatusCode();
        } catch (TransportExceptionInterface $exception) {
            return $exception;
        }
    }

}