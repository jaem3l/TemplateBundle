<?php

namespace jæm3l\TemplateBundle\Tests\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use jæm3l\TemplateBundle\Annotation\Template;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\EventListener\ControllerListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class TemplateTest extends TestCase
{
    /**
     * @var ControllerListener
     */
    private $listener;

    public static function setUpBeforeClass()
    {
        class_exists(Template::class);
    }

    protected function setUp()
    {
        $reader = new AnnotationReader();
        $this->listener = new ControllerListener($reader);
    }

    public function test_annotation_is_handled_by_controller_listener()
    {
        $request = new Request();
        $controller = new DummyController();
        $event = new FilterControllerEvent(
            $this->getMockBuilder(HttpKernelInterface::class)->getMock(),
            [$controller, 'simpleAction'],
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->listener->onKernelController($event);

        $request = $event->getRequest();

        $this->assertInstanceOf(Template::class, $request->attributes->get('_template'));
        $this->assertEquals('Hello World!', $request->attributes->get('_template')->getBody());
    }

    public function test_multiline_annotation_is_handled_by_controller_listener()
    {
        $request = new Request();
        $controller = new DummyController();
        $event = new FilterControllerEvent(
            $this->getMockBuilder(HttpKernelInterface::class)->getMock(),
            [$controller, 'multilineAction'],
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->listener->onKernelController($event);

        $request = $event->getRequest();

        $this->assertInstanceOf(Template::class, $request->attributes->get('_template'));
        $this->assertEquals(' Hello World! ', $request->attributes->get('_template')->getBody());
    }
}

class DummyController
{
    /**
     * @Template("Hello World!")
     */
    public function simpleAction() {}

    /**
     * @Template("
     * Hello World!
     * ")
     */
    public function multilineAction() {}
}
