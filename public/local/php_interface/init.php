<?php

// composer
require $_SERVER['DOCUMENT_ROOT'].'/local/vendor/autoload.php';

// https://github.com/ajgarlag/AjglBreakpointTwigExtension/blob/0dfa4f0ae3bbeb6c8036e3e6d6c204c43b090155/src/BreakpointExtension.php
class BreakpointExtension extends Twig_Extension
{
    public function getName()
    {
        return 'breakpoint';
    }
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('breakpoint', array($this, 'setBreakpoint'), array('needs_environment' => true, 'needs_context' => true)),
        );
    }
    /**
     * If XDebug is detected, makes the debugger break.
     *
     * @param Twig_Environment $environment the environment instance
     * @param mixed            $context     variables from the Twig template
     */
    public function setBreakpoint(Twig_Environment $environment, $context)
    {
        if (function_exists('xdebug_break')) {
            $arguments = array_slice(func_get_args(), 2);
            xdebug_break();
        }
    }
}

// TODO tools.twig onAfterTwigTemplateEngineInited
$twig = \Maximaster\Tools\Twig\TemplateEngine::getInstance()->getEngine();
$twig->addExtension(new BreakpointExtension());
$twig->addFunction(new Twig_SimpleFunction('asset', 'Hendrix\View::asset'));
$twig->addFunction(new Twig_SimpleFunction('partial', 'Hendrix\View::partial'));
$twig->addFunction(new Twig_SimpleFunction('path', 'Hendrix\View::path'));
$twig->addFunction(new Twig_SimpleFunction('layout', 'Hendrix\View::layout'));
$twig->addFunction(new Twig_SimpleFunction('add_editing_actions', 'Hendrix\NewsListLike::addEditingActions'));
