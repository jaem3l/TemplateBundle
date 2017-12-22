<?php

namespace jæm3l\TemplateBundle\DependencyInjection;

use jæm3l\TemplateBundle\Listener\TemplateListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class TemplateExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $definition = $container->register(TemplateListener::class, TemplateListener::class);
        $definition->setAutowired(true);
        $definition->setAutoconfigured(true);
    }
}
