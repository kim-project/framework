<?php

namespace Kim\Support\Provider;

class View
{
    private string $view;

    private array $data;

    public function __construct(string $view, array $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function render(): void
    {
        $file = file_get_contents($this->view);
        $data = $this->data;
        eval("?> $file <?php");
    }
}
