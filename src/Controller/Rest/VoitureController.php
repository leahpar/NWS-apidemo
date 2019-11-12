<?php

namespace App\Controller\Rest;

use App\Entity\Voiture;
use App\Form\VoitureType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as REST;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class VoitureController extends AbstractFOSRestController
{
    /**
     * Retoure la liste de toutes les voitures
     *
     * @REST\Get("/voitures", name="cget_voiture")
     *
     * @param EntityManagerInterface $em
     * @return View
     */
    public function cgetVoiture(EntityManagerInterface $em)
    {
        $voitures = $em->getRepository(Voiture::class)->findAll();
        return View::create($voitures, Response::HTTP_OK);
    }

    /**
     * Retourne la voiture de l'ID passé en paramètre
     *
     * @REST\Get("/voitures/{id}", name="get_voiture")
     *
     * @param Voiture $voiture
     * @return View
     */
    public function getVoiture(Voiture $voiture)
    {
        $this->denyAccessUnlessGranted('view', $voiture);

        return View::create($voiture, Response::HTTP_OK);
    }

    /**
     * Créée une nouvelle voiture
     *
     * @REST\Post("/voitures/new", name="new_voiture")
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return View
     */
    public function postVoiture(Request $request, EntityManagerInterface $em)
    {
        $voiture = new Voiture();

        $form = $this->createForm(VoitureType::class, $voiture);

        $form->submit($request->request->all(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($voiture);
            $em->flush();
        }
        else {
            dump($form, $form->getErrors(true));
            return View::create(null, Response::HTTP_BAD_REQUEST);
        }

        return View::create($voiture, Response::HTTP_OK);
    }

    /**
     * Modifie une voiture
     *
     * @REST\Patch("/voitures/{id}", name="patch_voiture")
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Voiture $voiture
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return View
     */
    public function patchVoiture(Voiture $voiture, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(VoitureType::class, $voiture, ["method" => "PATCH"]);

        $form->submit($request->request->all(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($voiture);
            $em->flush();
        }
        else {
            dump($form, $form->getErrors(true));
            return View::create(null, Response::HTTP_BAD_REQUEST);
        }

        return View::create($voiture, Response::HTTP_OK);
    }

    /**
     * Supprime une voiture
     *
     * @REST\Delete("/voitures/{id}", name="delete_voiture")
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Voiture $voiture
     * @param EntityManagerInterface $em
     * @return View
     */
    public function deleteVoiture(Voiture $voiture, EntityManagerInterface $em)
    {
        $em->remove($voiture);
        $em->flush();

        return View::create(null, Response::HTTP_NO_CONTENT);
    }

}

