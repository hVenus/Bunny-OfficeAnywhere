<?php
namespace Ticket\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TicketController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}