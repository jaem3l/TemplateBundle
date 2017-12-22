<?php

namespace jæm3l\TemplateBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Template implements ConfigurationInterface
{
    private $body;

    public function __construct(array $options)
    {
        $this->body = (string) $options['value'];
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getAliasName(): string
    {
        return 'template';
    }

    public function allowArray(): bool
    {
        return false;
    }
}
