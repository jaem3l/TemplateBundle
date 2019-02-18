<?php

namespace jæm3l\TemplateBundle\Tests\Listener;

use jæm3l\TemplateBundle\Annotation\Template;
use jæm3l\TemplateBundle\Listener\TemplateListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TemplateListenerTest extends TestCase
{
    /**
     * @var TemplateListener
     */
    private $listener;

    public static function setUpBeforeClass(): void
    {
        class_exists(Template::class);
    }

    protected function setUp(): void
    {
        $loader = new ArrayLoader([]);
        $twig = new Environment($loader);
        $this->listener = new TemplateListener($twig);
    }

    /**
     * @dataProvider provideTemplates
     */
    public function test_it_renders_templates(string $templateBody, $controllerResult, string $expectedContent)
    {
        $request = new Request();
        $request->attributes->set('_template', new Template(['value' => $templateBody]));
        $event = new GetResponseForControllerResultEvent(
            $this->getMockBuilder(HttpKernelInterface::class)->getMock(),
            $request,
            HttpKernelInterface::MASTER_REQUEST,
            $controllerResult
        );

        $this->listener->onKernelView($event);

        $response = $event->getResponse();

        $this->assertEquals($expectedContent, $response->getContent());
    }

    public function provideTemplates()
    {
        return [
            'simple string is rendered' => [
                'Hello World!',
                null,
                'Hello World!',
            ],
            'twig is parsed' => [
                '{{ 1 + 2 }}',
                null,
                '3'
            ],
            'controller array result is passed to twig' => [
                'Hello {{ name }}',
                [
                    'name' => 'You',
                ],
                'Hello You'
            ],
            'controller scalar result is passed to twig' => [
                'Hello {{ result }}',
                'Someone Else',
                'Hello Someone Else'
            ],
        ];
    }
}
