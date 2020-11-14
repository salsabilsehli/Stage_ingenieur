<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Produit;
use BoutiqueBundle\Entity\Produit_Boutique;
use BoutiqueBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class ProduitsController extends Controller
{

    public function AjoutproduitAction(Request $request,$username)
    {
        $em = $this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);
        $usid = $us->getId();


        $produit = new Produit();
        $form = $this->createForm('BoutiqueBundle\Form\ProduitType',$produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            //enregistrement ds la table produit_boutique
            $p_b= new Produit_Boutique();
            //seulement les objets en parametre
            $p_b->setBoutique($us);
            $p_b->setProduit($produit);
            $em->persist($p_b);
            $em->flush();

            $produits = $em->getRepository(Produit::class)->findAll();

            return $this->render('@Boutique/bkndPages/produits.html.twig',[
                'username' => $username,
                'boutique' => $us,
                'produit' => $produits
            ]);

        }

        return $this->render('@Boutique/bkndPages/ajout_produit_page.twig',[
            'username' => $username,
            'boutique' => $us,
            'form' => $form->createView(),
        ]);
    }



    public function deleteproduitAction($nomProd,$username){

        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->find($nomProd);
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('bk_produits',[
            'username' => $username,
        ]);
    }

    public function editproduitAction($nomProd,$username, Request $request){
        $em=$this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);

        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->find($nomProd);
        $form=$this->createForm(ProduitType::class,$produit);
        $form=$form->handleRequest($request);
        if($form->isValid()){
            $em->flush();
            return $this->redirectToRoute('bk_produits',[
                'username' => $username,
            ]);        }

        return $this->render('@Boutique/bkndPages/edit_produit_page.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'form' => $form->createView(),
        ]);
    }
}
