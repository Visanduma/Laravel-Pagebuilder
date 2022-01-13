<?php


namespace HansSchouten\LaravelPageBuilder\Extended;


class ThemeBlock
{
    public function getViewFile()
    {

        if ($this->isBladeBlock()) {

            // compiled blades are saved here
            $temp_path = storage_path('framework/views/');
            $temp_name = md5($this->getFolder()) . ".php";

            //cache view if production mode
            if (App::isProduction() && File::exists($temp_path . $temp_name)) {
                return $temp_path . $temp_name;
            }

            $content = file_get_contents($this->getFolder() . '/view.blade.php');
            $php_content = Blade::compileString($content);

            file_put_contents($temp_path . $temp_name, $php_content);

            return $temp_path . $temp_name;
        }

        if ($this->isPhpBlock()) {
            return $this->getFolder() . '/view.php';
        }

        return $this->getFolder() . '/view.html';
    }

    public function isBladeBlock()
    {
        return file_exists($this->getFolder() . '/view.blade.php');
    }

    public function isHtmlBlock()
    {
        return (!$this->isPhpBlock()) && (!$this->isBladeBlock());
    }
}