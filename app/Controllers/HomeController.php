<?php
// app/Controllers/HomeController.php

class HomeController
{
    public function index(): void
    {
        view('dashboard');
    }
}
