<?php


namespace HansSchouten\LaravelPageBuilder\Extended\Modules\GrapesJS\Block;


use PHPageBuilder\Modules\GrapesJS\Block\BaseController;
use PHPageBuilder\Modules\GrapesJS\Block\BaseModel;

class BlockRenderer extends \PHPageBuilder\Modules\GrapesJS\Block\BlockRenderer
{
    protected function renderDynamicBlock(ThemeBlock $themeBlock, $blockData)
    {
        $blockData = $blockData ?? [];
        $controller = new BaseController;
        $model = new BaseModel($themeBlock, $blockData, $this->page, $this->forPageBuilder);

        if ($themeBlock->getModelFile()) {
            require_once $themeBlock->getModelFile();
            $modelClass = $themeBlock->getModelClass();
            $model = new $modelClass($themeBlock, $blockData, $this->page, $this->forPageBuilder);
        }

        if ($themeBlock->getControllerFile()) {
            require_once $themeBlock->getControllerFile();
            $controllerClass = $themeBlock->getControllerClass();
            $controller = new $controllerClass;
        }
        $controller->init($model, $this->page, $this->forPageBuilder);
        $controller->handleRequest();

        // init additional variables that should be accessible in the view
        $renderer = $this;
        $page = $this->page;
        $block = $model;

        // add required laravel blade resources
        $__env = app(\Illuminate\View\Factory::class);

        // unset variables that should be inaccessible inside the view
        unset($controller, $model, $blockData);
        ob_start();

        require $themeBlock->getViewFile();
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}