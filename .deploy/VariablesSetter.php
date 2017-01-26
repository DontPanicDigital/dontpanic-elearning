<?php

class VariablesSetter
{

    /** @var string */
    private $project;

    /** @var array */
    private $variables = [];

    const TIME_STAMP_KEY = 'TIME_STAMP';

    public function __construct($project = null)
    {
        $this->project = $project;
    }

    public function run()
    {
        $this->loadExternalConfig();
        $this->setVariables();
    }

    private function loadExternalConfig()
    {
        // LOAD FILE
        $content         = file_get_contents(sprintf('/home/digi/configs/%s/config.json', $this->project));
        $this->variables = json_decode($content, true);
    }

    private function setVariables()
    {
        foreach ($this->variables as $item) {
            $filePath = $this->checkTargetedFile($item['file']);
            if ($filePath) {
                file_put_contents($filePath, $this->replaceVariables(file_get_contents($filePath), $item['vars']));
            }
        }
    }

    private function checkTargetedFile($path)
    {
        $filePath = __DIR__ . '/..' . $path;
        if (file_exists($filePath)) {
            return $filePath;
        }

        return false;
    }

    private function replaceVariables($content, array $vars)
    {
        foreach ($vars as $key => $var) {
            if ($key === self::TIME_STAMP_KEY) {
                $var = strtotime('now');
            }
            $content = str_replace('{{' . $key . '}}', $var, $content);
        }

        return $content;
    }
}

$varibablesSetter = new VariablesSetter($argv[1]);
$varibablesSetter->run();