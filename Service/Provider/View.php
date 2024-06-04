<?php

namespace Kim\Support\Provider;

class View
{
    /**
     * @var string The view file
     */
    private string $view;

    /**
     * @var array The data to be passed to the view
     */
    private array $data;

    /**
     * Get the view
     *
     * @param string $view The view file name
     * @param array $data The data to be passed
     */
    public function __construct(string $view, array $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * Render the view
     *
     * @return void
     */
    public function render(): void
    {
        $file = file_get_contents($this->view);
        $data = $this->data;
        eval("?> $file <?php");
    }
}
