<?php


/**
 * Admin controller for manager a module
 *
 * @author Adrien Jerphagnon <adrien.jerphagnon@disko.fr>
 */

namespace Disko\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 *
 * @package Disko\AdminBundle\Controller
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

        // Breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Tableau de bord', $this->get("router")->generate("homepage"));

        // Last subscribes
        $subscribes = $this->get('dk.user')->getUsersByWeek(20);

        return $this->render('AdminBundle:Home:index.html.twig', array(
            'subscribes' => $subscribes
        ));
    }
}
