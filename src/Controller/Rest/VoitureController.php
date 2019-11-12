<?php

namespace App\Controller\Rest;

use App\Entity\Voiture;
use App\Form\VoitureType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation as Doc;
use FOS\RestBundle\Controller\Annotations as REST;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


/**
 * @RouteResource("Voiture")
 */
class VoitureController extends AbstractFOSRestController
{
    /**
     * Retoure la liste de toutes les voitures
     *
     * @REST\Get("/voitures", name="cget_voiture")
     * @View
     *
     * @param EntityManagerInterface $em
     * @return Voiture[]
     */
    public function cgetVoiture(EntityManagerInterface $em)
    {
        $voitures = $em->getRepository(Voiture::class)->findAll();
        return $voitures;
    }

    /**
     * @REST\Get("/test/{id}", name="test")
     * @param Voiture $voiture
     */
    public function toto(Voiture $voiture) {
        $json = json_encode($voiture);
        return new Response($json);
    }

    /**
     * Retourne la voiture de l'ID passé en paramètre
     *
     * @REST\Get("/voitures/{id}", name="get_voiture")
     * @View
     *
     * @SWG\Response(
     *     response=200,
     *     description="une voiture",
     *     @Doc\Model(type=Voiture::class)
     * )

     *
     * @param Voiture $voiture
     * @return Voiture
     */
    public function getVoiture(Voiture $voiture)
    {
        return $voiture;
    }

    /**
     * Créée une nouvelle voiture
     *
     * @REST\Post("/voitures/new", name="new_voiture")
     * @View
     *
     * @SWG\Parameter(
     *     name="voiture",
     *     in="body",
     *     description="Les infos de la voiture",
     *     @Doc\Model(type=Voiture::class)
     * ),
     * @SWG\Response(
     *     response=200,
     *     description="nouvelle voiture",
     *     @Doc\Model(type=Voiture::class)
     * )
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Voiture
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
            throw new BadRequestHttpException();
        }

        return $voiture;
    }


    /**
     * Modifie une voiture
     *
     * @REST\Patch("/voitures/{id}", name="patch_voiture")
     * @View
     *
     * @param Voiture $voiture
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Voiture
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
            throw new BadRequestHttpException();
        }

        return $voiture;
    }

    /**
     * Supprime une voiture
     *
     * @REST\Delete("/voitures/{id}", name="delete_voiture")
     * @View
     *
     * @param Voiture $voiture
     * @param EntityManagerInterface $em
     * @return Voiture
     */
    public function deleteVoiture(Voiture $voiture, EntityManagerInterface $em)
    {
        $em->remove($voiture);
        $em->flush();

        return null;
    }

}

