<?php

namespace CodeBuds\MattermostPublicationBundle\Model;


class Message
{
    private ?string $webhookUrl;

    private ?string $text;

    private ?string $username;

    private ?string $iconUrl;

    private ?string $channel;

    public function __construct()
    {
        $this->webhookUrl = null;
        $this->text = null;
        $this->username = null;
        $this->iconUrl = null;
        $this->channel = null;
    }

    public function getWebhookUrl(): ?string
    {
        return $this->webhookUrl;
    }

    public function setWebhookUrl(?string $webhookUrl): Message
    {
        $this->webhookUrl = $webhookUrl;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): Message
    {
        $this->text = $text;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): Message
    {
        $this->username = $username;

        return $this;
    }

    public function getIconUrl(): ?string
    {
        return $this->iconUrl;
    }

    public function setIconUrl(?string $iconUrl): Message
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(?string $channel): Message
    {
        $this->channel = $channel;

        return $this;
    }

    public function toArray()
    {
        $array = [
            'webhook_url' => $this->getText(),
            'text' => $this->getText(),
            'username' => $this->getUsername(),
            'icon_url' => $this->getIconUrl(),
            'channel' => $this->getChannel()
        ];

        return array_filter($array, fn($var) => $var !== null);
    }
}