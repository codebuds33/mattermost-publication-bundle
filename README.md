# Mattermost Publication Bundle

This bundle allows you to easily publish text to a Mattermost webhook.

In order to do so you need to add configuration to your symfony by, for example, adding a `mattermost_publication.yaml` file to the config/packages containing :

```yaml
mattermost_publication:
  webhook_url: '%env(MATTERMOST_WEBHOOK_URL)%'
```

and adding the webhook URL to your environment variables :

```
###> codebuds/mattermost-publication###
MATTERMOST_WEBHOOK_URL="http://{your-mattermost-site}/hooks/xxx-generatedkey-xxx"
###< codebuds/mattermost-publication###
```

You can use the publisher directly inside a controller function by adding it as a parameter : 

```php
/**
 * @Route("/submit-donation")
 * @param MattermostPublication $publication
 * @return JsonResponse
 */
public function submitDonationForm(MattermostPublication $publication)
{
    try {
        $publication->publish(
            "# donation made"
        );
    } catch (\Exception $e) {
        return new JsonResponse(['message' => $e->getMessage()], 500);
    }
}
```

In an eventSubscriber you can inject the publisher in the constructor :

```php
public function __construct(MattermostPublication $publication)
{
    $this->publication = $publication;
}
```

and then, inside any function, you can publish a message thanks to the bundle :

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

You can also use twig to create message templates, if you create the following template file in templates/mattermost/message.twig :
```twig
# New contact form submission

**Name : ** {{ message.name }}

**Email : ** {{ message.email }}

### Message

{{ message.message }}
```

You can render it and use it like the following : 

```php
$messageText = $this->twig->render('mattermost/message.twig', ['message' => $message]);
$this->publication->publish($messageText);
```