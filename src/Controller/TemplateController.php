<?php declare(strict_types = 1);

namespace jæm3l\TemplateBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class TemplateController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function templateAction(string $template, ?int $maxAge, ?int $sharedMaxAge, ?bool $private): Response
    {
        $twigTemplate = $this->twig->createTemplate($template);

        $response = new Response($twigTemplate->render([]));

        if ($maxAge) {
            $response->setMaxAge($maxAge);
        }
        if ($sharedMaxAge) {
            $response->setSharedMaxAge($sharedMaxAge);
        }
        if ($private) {
            $response->setPrivate();
        } elseif (false === $private || (null === $private && ($maxAge || $sharedMaxAge))) {
            $response->setPublic();
        }

        return $response;
    }

    public function __invoke(string $template, ?int $maxAge, ?int $sharedMaxAge, ?bool $private): Response
    {
        return $this->templateAction($template, $maxAge, $sharedMaxAge, $private);
    }
}
