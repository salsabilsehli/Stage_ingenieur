<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Categorie;
use BoutiqueBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class CategoriesController extends Controller

{

    public function AjoutcategorieAction(Request $request,$username)
    {
        $em = $this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);


        $categorie = new Categorie();
        $form = $this->createForm('BoutiqueBundle\Form\CategorieType', $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            $categories = $em->getRepository(Categorie::class)->findAll();

            return $this->render('@Boutique/bkndPages/categories.html.twig',[
                'username' => $username,
                'boutique' => $us,
                'categorie' => $categories
            ]);

        }

        return $this->render('@Boutique/bkndPages/ajout_categorie_page.twig',[
            'username' => $username,
            'boutique' => $us,
            'form' => $form->createView(),
        ]);
    }

    public function deletecategorieAction($nomCategorie,$username){

            $em=$this->getDoctrine()->getManager();
            $categorie=$em->getRepository(Categorie::class)->find($nomCategorie);
            $em->remove($categorie);
            $em->flush();
            return $this->redirectToRoute('bk_categories',[
                'username' => $username,
            ]);
        }

    public function editcategorieAction($nomCategorie,$username, Request $request){
        $em=$this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);

        $em=$this->getDoctrine()->getManager();
        $categorie=$em->getRepository(Categorie::class)->find($nomCategorie);
        $form=$this->createForm(CategorieType::class,$categorie);
        $form=$form->handleRequest($request);
        if($form->isValid()){
            $em->flush();
            return $this->redirectToRoute('bk_categories',[
                'username' => $username,
            ]);        }

        return $this->render('@Boutique/bkndPages/edit_categorie_page.twig',[
            'username' => $username,
            'boutique' => $us,
            'form' => $form->createView(),
        ]);
    }
}
