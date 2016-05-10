<?php


/**
 * Admin controller for manager a module
 *
 * @author Adrien Jerphagnon <adrien.jerphagnon@disko.fr>
 */

namespace Disko\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 *
 * @package Disko\FrontBundle\Controller
 */
class HomeController extends Controller
{
    /**
     * Homepage
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('FrontBundle:Home:index.html.twig', array());
    }
}
