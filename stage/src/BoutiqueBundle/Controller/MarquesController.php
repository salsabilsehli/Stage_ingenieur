<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Marque;
use BoutiqueBundle\Form\MarqueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class MarquesController extends Controller
{
    public function AjoutmarqueAction(Request $request,$username)
    {
        $em = $this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);


        $marque = new Marque();
        $form = $this->createForm('BoutiqueBundle\Form\MarqueType', $marque);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($marque);
            $em->flush();
            $marques = $em->getRepository(Marque::class)->findAll();

            return $this->render('@Boutique/bkndPages/marques.html.twig',[
                'username' => $username,
                'boutique' => $us,
                'marque' => $marques
            ]);

        }

        return $this->render('@Boutique/bkndPages/ajout_marque_page.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'form' => $form->createView(),
        ]);
    }

    public function deletemarqueAction($nomMarque,$username){

        $em=$this->getDoctrine()->getManager();
        $marque=$em->getRepository(Marque::class)->find($nomMarque);
        $em->remove($marque);
        $em->flush();
        return $this->redirectToRoute('bk_marques',[
            'username' => $username,
        ]);
    }

    public function editmarqueAction($nomMarque,$username, Request $request){
        $em=$this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);

        $em=$this->getDoctrine()->getManager();
        $marque=$em->getRepository(Marque::class)->find($nomMarque);
        $form=$this->createForm(MarqueType::class,$marque);
        $form=$form->handleRequest($request);
        if($form->isValid()){
            $em->flush();
            return $this->redirectToRoute('bk_marques',[
                'username' => $username,
            ]);        }

        return $this->render('@Boutique/bkndPages/edit_marque_page.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'form' => $form->createView(),
        ]);
    }
}
