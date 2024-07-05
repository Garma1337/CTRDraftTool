<?php

declare(strict_types = 1);

namespace DraftTool\Lib;

use DraftTool\Services\Request;
use DraftTool\Services\Translator;
use Smarty;
use SmartyException;

/**
 * Base class for all controllers
 * @author Garma
 */
abstract class BaseController
{
    /**
     * @var Request
     */
    protected Request $request;
    
    /**
     * @var Smarty
     */
    protected Smarty $smarty;
    
    public function __construct()
    {
        $this->request = App::request();
        $this->smarty = App::smarty();
    }
    
    /**
     * This method is always executed before every action
     */
    public function preDispatch(): void
    {
        if ($this->request->has('language')) {
            $language = $this->request->getParam('language');
            
            if (!App::translator()->languageExists($language)) {
                $_SESSION['language'] = Translator::LANGUAGE_ENGLISH;
            } else {
                $_SESSION['language'] = $language;
            }
        }
    }
    
    /**
     * This method is always executed after every action
     * @throws SmartyException
     */
    public function postDispatch(): void
    {
        $this->renderTemplate();
    }
    
    /**
     * Renders the template
     * @param string|null $template
     * @throws SmartyException
     */
    protected function renderTemplate(?string $template = null): void
    {
        $controller = $this->request->getParam('controller', 'draft');
        $action = $this->request->getParam('action', 'index');
        
        if ($template === null) {
            $template = $action;
        }
        
        /* Imagine assigning objects to the view KEKW ... I am lazy */
        $this->smarty->assign([
            'controller'    => $controller,
            'action'        => $action,
            'router'        => App::router(),
            'translator'    => App::translator(),
        ]);
        
        $templateFile = $controller . '/' . $template . '.tpl';
        if (!$this->smarty->templateExists($templateFile)) {
            return;
        }
        
        $content = $this->smarty->fetch($templateFile);
        
        $layout = App::smarty();
        $layout->assign('content', $content);
        
        $layout->display('layout.tpl');
    }
    
    /**
     * Redirects a user to a given action
     * @param string $controller
     * @param string $action
     * @param array $params
     */
    protected function redirect(string $controller, string $action, array $params = []): void
    {
        $url = App::router()->generateUrl($controller, $action, $params);
        header('Location: ' . $url, true, 301);
    }
}
