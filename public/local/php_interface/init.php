<?php

use Dotenv\Dotenv;

$localDir = $_SERVER['DOCUMENT_ROOT'].'/local';

// composer
require $localDir.'/vendor/autoload.php';

// load environment variables from .env to getenv(), $_ENV and $_SERVER
if (file_exists($localDir.'/.env')) {
    $dotenv = new Dotenv($localDir);
    $dotenv->load();
}

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
// TODO rename to `asset` for consistency
$twig->addFunction(new Twig_SimpleFunction('asset', 'Hendrix\View::asset'));
$twig->addFunction(new Twig_SimpleFunction('partial', 'Hendrix\View::partial'));
$twig->addGlobal('SITE_DIR', SITE_DIR);
