<?php
require_once '../application/bootstrap.'.$_SERVER["HTTP_HOST"].'.php';
if(!$a->checkAuth()) {
    Tools::redirect('login.php');
}