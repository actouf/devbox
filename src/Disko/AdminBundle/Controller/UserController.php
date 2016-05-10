<?php

/**
 * Admin controller for manager a module
 *
 * @author Adrien Jerphagnon <adrien.jerphagnon@disko.fr>
 */

namespace Disko\AdminBundle\Controller;

use Disko\UserBundle\Entity\User;
use Disko\UserBundle\Form\Model\SearchUser;
use Disko\UserBundle\Form\Type\SearchUserType;
use Disko\UserBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 *
 * @package Disko\AdminBundle\Controller
 */
class UserController extends Controller
{
    /**
     * User's list
     *
     * @param Request $request Request
     */
    public function listAction(Request $request)
    {
        // init search
        $data = $this->initSearch($request);

        // Init form
        $form = $this->createForm(SearchUserType::class, $data);

        // Breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Tableau de bord', $this->get("router")->generate("admin_homepage"));
        $breadcrumbs->addItem('Liste des utilisateurs');

        // Generate query
        $query = $this->get('dk.user')->getQueryForSearch($data->getSearchData());

        // Paginate transform
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );

        $subscribes = $this->get('dk.user')->getUsersByWeek(20);

        // Render view
        return $this->render('AdminBundle:User:index.html.twig', array(
            'pagination' => $pagination,
            'subscribes' => $subscribes,
            'form' => $form->createView()
        ));
    }

    /**
     * Init Search object
     *
     * @param Request $request Request
     */
    protected function initSearch(Request $request)
    {
        // Filters get
        $filters = $request->query->get('search', array());

        // Init form
        $data = new SearchUser();
        $data->setId((isset($filters['id'])) ? $filters['id'] : '');
        $data->setEmail((isset($filters['email']))   ? $filters['email'] : '');
        $data->setFirstName((isset($filters['firstName'])) ? $filters['firstName'] : '');
        $data->setLastName((isset($filters['lastName'])) ? $filters['lastName'] : '');

        return $data;
    }

    /**
     * Create a user
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        // Breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        $breadcrumbs->addItem('Tableau de bord', $this->get("router")->generate("admin_homepage"));
        $breadcrumbs->addItem("Liste des utilisateurs", $this->get("router")->generate("admin_user_list"));
        $breadcrumbs->addItem("Création");

        // Init form
        $user = new User();
        $user->setPlainPassword('password');
        $user->setPassword('password');

        $form = $this->createForm(new UserType(), $user);

        // Update method
        if ('POST' === $request->getMethod()) {

            // Bind value with form
            $form->handleRequest($request);

            if ($form->isValid()) {
                // Save
                $this->get('dk.user')->save($user);

                // Launch the message flash
                $this->get('session')->getFlashBag()->set(
                    'notice',
                    "Création terminée"
                );

                return $this->redirect($this->generateUrl('admin_user_edit', array(
                    'id' => $user->getId()
                )));
            }

            // Launch the message flash
            $this->get('session')->getFlashBag()->set(
                'error',
                "Le formulaire contient des erreurs"
            );
        }

        // View
        return $this->render('AdminBundle:User:create.html.twig',
            array(
                'form'           => $form->createView(),
                'user'           => $user,
            ));
    }

    /**
     * Update a user
     *
     * @param Request $request Request
     * @param integer $id      Id of user
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        // Find it
        $user =  $this->get('dk.user')->findOneToEdit($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Tableau de bord', $this->get("router")->generate("admin_homepage"));
        $breadcrumbs->addItem("Liste des utilisateurs", $this->get("router")->generate("admin_user_list"));
        $breadcrumbs->addItem("Modification");

        // Init form
        $form = $this->createForm(UserType::class, $user);

        // Update method
        if ('POST' === $request->getMethod()) {

            // Bind value with form
            $form->handleRequest($request);

            if ($form->isValid()) {

                // Save
                $this->get('dk.user')->save($user);

                // Launch the message flash
                $this->get('session')->getFlashBag()->set(
                    'notice',
                    "Mise à jour effectuée"
                );

                return $this->redirect($this->getRequest()->headers->get('referer'));
            }

            // Launch the message flash
            $this->get('session')->getFlashBag()->set(
                'error',
                "Le formulaire contient des erreurs"
            );
        }

        // View
        return $this->render('AdminBundle:User:edit.html.twig',
            array(
                'form'           => $form->createView(),
                'user'           => $user,
            ));
    }

    /**
     * Delete on user
     *
     * @param Request $request Request
     * @param integer $id      Id of user
     */
    public function deleteAction(Request $request, $id)
    {
        // Find it
        /** @var User $user */
        $user =  $this->get('dk.user')->findOneToEdit($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $this->get('dk.user')->remove($user);

        // Launch the message flash
        $this->get('session')->getFlashBag()->set(
            'notice',
            'L\'utilisateur "'.$user->getFirstName().' '.$user->getLastName().'" a bien été supprimé'
        );

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Export csv
     *
     * @param Request $request
     */
    public function exportAction(Request $request)
    {
        $data = $this->initSearch($request);
        $query = $this->get('dk.user')->getQueryForSearch($data->getSearchData());
        $results = $query->getResult();

        $handle = fopen('php://memory', 'r+');

        fputcsv($handle, [
            '#',
            'Prénom',
            'Nom',
            'E-mail',
            'Date de création',
            'Activé',
            'Bloqué'
        ], ';');

        foreach ($results as $result)
        {
            $fileRow = [
                $result->getId(),
                $result->getFirstName(),
                $result->getLastName(),
                $result->getEmail(),
                $result->getCreated()->format('d/m/Y'),
                $result->isEnabled(),
                $result->isLocked()
            ];
            fputcsv($handle, $fileRow, ';');
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="'.date('Y-m-d').'_users_export.csv"'
        ));
    }
}
