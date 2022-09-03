<?php

namespace CodeBuds\MattermostPublicationBundle;

use CodeBuds\MattermostPublicationBundle\DependencyInjection\MattermostPublicationExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MattermostPublicationBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new MattermostPublicationExtension();
        }

        return $this->extension;
    }
}
