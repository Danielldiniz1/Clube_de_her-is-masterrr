<?php

namespace Source\App;

use League\Plates\Engine;
use League\Plates\Extension\URI; // Import the URI extension class

class Admin
{
    private $view;

    public function __construct()
    {
        $this->view = new Engine(__DIR__ . "/../../themes/adm","php");
        // Load the URI extension to make $this->uri available in templates
        // Pass the current request URI to the URI extension
        $this->view->loadExtension(new URI($_SERVER['REQUEST_URI'])); //
    }

    public function home ()
    {
        //echo "<h1>Eu sou a Home...</h1>";
        echo $this->view->render("home",[]);
    }

    public function products () {
        echo $this->view->render("products",[]);
    }
    public function manageProducts () {
        echo $this->view->render("manage-products",[]);
    }
    public function manageUsers () {
        echo $this->view->render("manage-users",[]);
    }
    public function manageClubs () {
        echo $this->view->render("manage-clubs",[]);
    }



}