<?php

namespace jæm3l\TemplateBundle\Listener;

use jæm3l\TemplateBundle\Annotation\Template;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TemplateListener implements EventSubscriberInterface
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onKernelView']
        ];
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        /** @var Template $template */
        $template = $request->attributes->get('_template');
        if (!$template instanceof Template) {
            return;
        }

        $body = $this->renderTemplate($template, $this->handleControllerResult($event->getControllerResult()));
        $event->setResponse(new Response($body));
    }

    private function handleControllerResult($controllerResult): array
    {
        if ($controllerResult === null) {
            return [];
        }
        if (!is_array($controllerResult) || $this->isNumericArray($controllerResult)) {
            $controllerResult = ['result' => $controllerResult];
        }

        return $controllerResult;
    }

    /**
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    private function renderTemplate(Template $template, array $controllerResult): string
    {
        $twigTemplate = $this->twig->createTemplate($template->getBody());

        return $twigTemplate->render($controllerResult);
    }

    private function isNumericArray($controllerResult): bool
    {
        return is_array($controllerResult) && array_keys($controllerResult) === range(0, \count($controllerResult) -1);
    }
}
