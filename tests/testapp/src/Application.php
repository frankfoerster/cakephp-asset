<?php
declare(strict_types=1);

namespace TestApp;

class Application extends \Cake\Http\BaseApplication
{
    public function middleware(\Cake\Http\MiddlewareQueue $middlewareQueue): \Cake\Http\MiddlewareQueue
    {
        return $middlewareQueue;
    }
}
