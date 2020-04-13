<?php


namespace CodeBuds\MattermostPublicationBundle;

use CodeBuds\MattermostPublicationBundle\DependencyInjection\MattermostPublicationExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MattermostPublicationBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new MattermostPublicationExtension();
        }

        return $this->extension;
    }
}