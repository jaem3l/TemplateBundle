jaem3l TemplateBundle for Symfony 4
===================================

This bundle provides a single `@Template` annotation that can be used in favor of
SensioFrameworkExtraBundle's annotation with the same name.

How it works
------------

You can see the DummyController inside tests/Annotation/TemplateTest.php as a reference.
Twig is fully supported, which should make writing your templates easier.

Requirements
------------

 - PHP 7.1
 - SensioFrameworkExtraBundle 5.1
 - Twig 2.4
 
Installation
------------

The bundle can be installed via Composer:

    composer require jaem3l/template-bundle

Usage Examples
--------------

    <?php

    namespace App\Controller;

    use jæm3l\TemplateBundle\Annotation\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

    class HelloController
    {
        /**
         * @Route("/")
         * @Template("Hello World")
         */
        public function phpHelloAction()
        {
        }

        /**
         * @Route("/twig")
         * @Template("{{ 1 + 2 }}")
         */
        public function twigAction()
        {
        }

        /**
         * @Route("/twig_name ")
         * @Template("Hello {{ name }}")
         */
        public function twigNamedHelloAction()
        {
            return [
                'name' => 'Santa Claus',
            ];
        }
    }