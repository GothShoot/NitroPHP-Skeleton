<?php

namespace Module\AppModule\Controller;

use Alzundaz\NitroPHP\BaseClass\Controller;

class DefaultController extends Controller
{
    public function index()
    {
        $this->render('@AppModule/index.html.twig');
    }
}