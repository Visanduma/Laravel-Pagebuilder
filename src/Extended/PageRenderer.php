<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 11/23/21
 * Time: 1:10 PM
 */

namespace HansSchouten\LaravelPageBuilder\Extended;


use PHPageBuilder\Modules\GrapesJS\Block\BlockRenderer;

class PageRenderer extends \PHPageBuilder\Modules\GrapesJS\PageRenderer
{
    public function renderBlock($slug, $id = null, $context = null, $maxDepth = 25)
    {
        $themeBlock = new ThemeBlock($this->theme, $slug); // Changed to extended ThemeBlock class

        $id = $id ?? $themeBlock->getSlug();
        $context = $context[$id] ?? $this->pageBlocksData[$id] ?? [];

        $blockRenderer = new BlockRenderer($this->theme, $this->page, $this->forPageBuilder);
        $renderedBlock = $blockRenderer->render($themeBlock, $context ?? [], $id);

        // determine the context for rendering nested blocks
        // if the current block is an html block, the context starts again at full page data
        // if the current block is a dynamic block, use the nested block data inside the current block's context
        $context = $context['blocks'] ?? [];
        if ($themeBlock->isHtmlBlock()) {
            $context = $this->pageBlocksData;
        }

        return $this->shortcodeParser->doShortcodes($renderedBlock, $context, $maxDepth - 1);
    }


}