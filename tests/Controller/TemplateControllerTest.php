<?php declare(strict_types = 1);

namespace jæm3l\TemplateBundle\Tests\Controller;

use jæm3l\TemplateBundle\Controller\TemplateController;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TemplateControllerTest extends TestCase
{
    /**
     * @var TemplateController
     */
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new ArrayLoader([]);
        $twig = new Environment($loader);

        $this->controller = new TemplateController($twig);
    }

    public function test_it_renders_basic_template()
    {
        $template = 'Hello World!';

        $response = $this->controller->templateAction($template, null, null, null);

        $this->assertSame($template, $response->getContent());
        $this->assertSame('no-cache, private', $response->headers->get('Cache-Control'));
    }

    public function test_it_sets_max_age_on_response()
    {
        $template = 'Hello World!';

        $response = $this->controller->templateAction($template, 360, null, null);

        $this->assertContains('max-age=360', $response->headers->get('Cache-Control'));
        $this->assertContains('public', $response->headers->get('Cache-Control'));
    }

    public function test_it_sets_shared_max_age_on_response()
    {
        $template = 'Hello World!';

        $response = $this->controller->templateAction($template, null, 360, null);

        $this->assertContains('s-maxage=360', $response->headers->get('Cache-Control'));
        $this->assertContains('public', $response->headers->get('Cache-Control'));
    }

    public function test_it_sets_cache_private_on_response()
    {
        $template = 'Hello World!';

        $response = $this->controller->templateAction($template, null, null, true);

        $this->assertContains('private', $response->headers->get('Cache-Control'));
    }

    public function test_it_sets_cache_public_on_response()
    {
        $template = 'Hello World!';

        $response = $this->controller->templateAction($template, null, null, false);

        $this->assertContains('public', $response->headers->get('Cache-Control'));
    }
}
