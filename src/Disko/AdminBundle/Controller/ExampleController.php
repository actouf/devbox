<?php

/**
 * Admin controller for manager a module
 *
 * @author Adrien Jerphagnon <adrien.jerphagnon@disko.fr>
 */

namespace Disko\AdminBundle\Controller;

use Disko\ExampleBundle\Entity\Example;
use Disko\ExampleBundle\Form\Model\SearchExample;
use Disko\ExampleBundle\Form\Type\SearchExampleType;
use Disko\ExampleBundle\Form\Type\ExampleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExampleController
 *
 * @package Disko\AdminBundle\Controller
 */
class ExampleController extends Controller
{
    /**
     * Example's list
     *
     * @param Request $request Request
     */
    public function listAction(Request $request)
    {
        // init search
        $data = $this->initSearch($request);

        // Init form
        $form = $this->createForm(SearchExampleType::class, $data);

        // Breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Tableau de bord', $this->get("router")->generate("admin_homepage"));
        $breadcrumbs->addItem('Liste des exemples');

        // Generate query
        $query = $this->get('dk.example')->getQueryForSearch($data->getSearchData());

        // Paginate transform
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );

        // Render view
        return $this->render('AdminBundle:Example:index.html.twig', array(
            'pagination' => $pagination,
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
        $data = new SearchExample();
        $data->setId((isset($filters['id'])) ? $filters['id'] : '');
        $data->setName((isset($filters['name']))   ? $filters['name'] : '');

        return $data;
    }

    /**
     * Create a example
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
        $breadcrumbs->addItem("Liste des exemples", $this->get("router")->generate("admin_example_list"));
        $breadcrumbs->addItem("Création");


        // Init form
        $example = new Example();

        $form = $this->createForm(ExampleType::class, $example);

        // Update method
        if ('POST' === $request->getMethod()) {

            // Bind value with form
            $form->handleRequest($request);

            if ($form->isValid()) {

                // Save
                $this->get('dk.example')->save($example);

                // Launch the message flash
                $this->get('session')->getFlashBag()->set(
                    'notice',
                    "Création terminée"
                );

                return $this->redirect($this->generateUrl('admin_example_edit', array(
                    'id' => $example->getId()
                )));
            }

            // Launch the message flash
            $this->get('session')->getFlashBag()->set(
                'error',
                "Le formulaire contient des erreurs"
            );
        }

        // View
        return $this->render('AdminBundle:Example:create.html.twig',
            array(
                'form'           => $form->createView(),
                'example'           => $example,
            ));
    }

    /**
     * Update a example
     *
     * @param Request $request Request
     * @param integer $id      Id of example
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        // Find it
        $example =  $this->get('dk.example')->findOneToEdit($id);
        if (!$example) {
            throw $this->createNotFoundException('Example not found');
        }

        // Breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Tableau de bord', $this->get("router")->generate("admin_homepage"));
        $breadcrumbs->addItem("Liste des exemples", $this->get("router")->generate("admin_example_list"));
        $breadcrumbs->addItem("Modification");

        // Init form
        $form = $this->createForm(ExampleType::class, $example);

        // Update method
        if ('POST' === $request->getMethod()) {

            // Bind value with form
            $form->handleRequest($request);

            if ($form->isValid()) {

                // Save
                $this->get('dk.example')->save($example);

                // Launch the message flash
                $this->get('session')->getFlashBag()->set(
                    'notice',
                    "Mise à jour effectuée"
                );

                return $this->redirect($request->headers->get('referer'));
            }

            // Launch the message flash
            $this->get('session')->getFlashBag()->set(
                'error',
                "Le formulaire contient des erreurs"
            );
        }

        // View
        return $this->render('AdminBundle:Example:edit.html.twig',
            array(
                'form'           => $form->createView(),
                'example'           => $example,
            ));
    }

    /**
     * Delete one example
     *
     * @param Request $request Request
     * @param integer $id      Id of example
     */
    public function deleteAction(Request $request, $id)
    {
        // Find it
        /** @var Example $example */
        $example =  $this->get('dk.example')->findOneToEdit($id);
        if (!$example) {
            throw $this->createNotFoundException('Example not found');
        }

        $this->get('dk.example')->remove($example);

        // Launch the message flash
        $this->get('session')->getFlashBag()->set(
            'notice',
            'Le exemple "'.$example->getName().'"" a bien été supprimé'
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
        $query = $this->get('dk.example')->getQueryForSearch($data->getSearchData());
        $results = $query->getResult();

        $handle = fopen('php://memory', 'r+');

        fputcsv($handle, [
            '#',
            'Nom',
            'Date de création',
            'Photo',
            'Activé'
        ], ';');

        foreach ($results as $result)
        {
            $fileRow = [
                $result->getId(),
                $result->getName(),
                $result->getCreated()->format('d/m/Y'),
                $result->getWebPath('Keyword'),
                $result->getActive()
            ];
            fputcsv($handle, $fileRow, ';');
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="'.date('Y-m-d').'_examples_export.csv"'
        ));
    }
}
