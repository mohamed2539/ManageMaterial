<?php

echo '<br>';
print_r(__DIR__);
echo '<br>';



class Controller {
    protected function view($view, $data = []) {
        extract($data);
        require_once "../app/views/$view.php";
    }
}