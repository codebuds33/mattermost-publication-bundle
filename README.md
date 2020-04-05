# Mattermost Publication Bundle

This bundle allows you to easily publish text to a Mattermost webhook.

In order to do so you need to add configuration to your symfony by, for example, adding a `mattermost_publication.yaml` file to the config/packages containing :

```yaml
mattermost_publication:
  webhook_url: '%env(MATTERMOST_WEBHOOK_URL)%'
```

and adding the webhook URL toe your environment variables :

```
###> codebuds/mattermost-publication###
MATTERMOST_WEBHOOK_URL="http://{your-mattermost-site}/hooks/xxx-generatedkey-xxx"
###< codebuds/mattermost-publication###
```

Then you can publish a message thanks to the bundle :

```php
try {
    $this->publication->publish(
        "# New contact form submission : " .
        "\n " .
        "**Name : **" . $message->getName() .
        "\n " .
        "**Email : **" . $message->getEmail() .
        "\n" .
        "### Message" .
        "\n" .
        $message->getMessage()
    );
} catch (\Exception $e) {
    return new JsonResponse(['message' => $e->getMessage()], 500);
}
```