jaem3l TemplateBundle for Symfony 4
===================================

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/77bafc12-418f-4148-8b94-12960475e6f5/mini.png)](https://insight.sensiolabs.com/projects/77bafc12-418f-4148-8b94-12960475e6f5)
[![Build Status](https://travis-ci.org/jaem3l/TemplateBundle.svg?branch=master)](https://travis-ci.org/jaem3l/TemplateBundle)
[![Maintainability](https://api.codeclimate.com/v1/badges/d5f218c9745a017417b5/maintainability)](https://codeclimate.com/github/jaem3l/TemplateBundle/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/d5f218c9745a017417b5/test_coverage)](https://codeclimate.com/github/jaem3l/TemplateBundle/test_coverage)

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

When using this bundle inside a Symfony 4 application bootstrapped with Flex,
make sure you have installed the twig-bundle and set it up with its recipe, e.g.
by using `composer req twig`.

Usage examples for Route-annotation
-----------------------------------

    <?php

    namespace App\Controller;

    use jæm3l\TemplateBundle\Annotation\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

    class HelloController
    {
        /**
         * @Route("/hello1")
         * @Template("Hello World")
         */
        public function simpleHelloAction()
        {
        }

        /**
         * @Route("/hello2 ")
         * @Template("Hello {{ name }}")
         */
        public function advancedHelloAction()
        {
            return [
                'name' => 'Santa Claus',
            ];
        }

        /**
         * @Route("/twig")
         * @Template("{{ 1 + 2 }}")
         */
        public function simpleExpressionAction()
        {
        }

        /**
         * @Route("/advanced_hello ")
         * @Template("
         * {% extends 'base.html.twig' %}
         *
         * {% block body %}
         * Hello {{ name }}!
         * {% endblock %}
         * ")
         */
        public function advancedTemplateAction()
        {
            return [
                'name' => 'Santa Claus',
            ];
        }
    }

Usage examples for TemplateController
-------------------------------------

In your routing you can now rely on this controller for directly rendering a
provided template:

```
# SF3: app/config/routing.yml
# SF4: config/routes.yaml

hello_world:
    path: /hello-world
    controller: jæm3l\TemplateBundle\Controller\TemplateController::template
    defaults:
        template: 'Hello World!'
        
twig_expression:
    path: /twig-expression
    controller: jæm3l\TemplateBundle\Controller\TemplateController::template
    defaults:
        template: '{{ 1 + 2 }}'
        
advanced_template:
    path: /advanced-template
    controller: jæm3l\TemplateBundle\Controller\TemplateController
    defaults:
        template: |
            {% extends 'base.html.twig' %}

            {% block body %}
            Hello World!
            {% endblock %}
```
