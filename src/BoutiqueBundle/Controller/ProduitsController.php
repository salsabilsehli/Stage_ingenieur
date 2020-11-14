<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Categorie;
use BoutiqueBundle\Entity\Produit;
use BoutiqueBundle\Entity\Produit_Boutique;
use BoutiqueBundle\Entity\Produitc;
use BoutiqueBundle\Form\ProduitcType;
use BoutiqueBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class ProduitsController extends Controller
{

    public function AjoutproduitAction(Request $request,$username)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $username);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }

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


            $produitsduboutique = $em->getRepository(Produit_Boutique::class)->findBy(['boutique' => $usid]);

                return $this->render('@Boutique/bkndPages/produits.html.twig',[
                    'username' => $username,
                    'boutique' => $us,
                    'produits' => $produitsduboutique,

                ]);



        }

        return $this->render('@Boutique/bkndPages/ajout_produit_page.twig',[
            'username' => $username,
            'boutique' => $us,
            'form' => $form->createView(),
        ]);
    }


    //ajout couleurs
    public function editcouleurproduitAction(Request $request,$username,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $username);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        $produitc=new Produitc();
        $formc=$this->createForm(ProduitcType::class,$produitc);
        $formc->handleRequest($request);
        if ($formc->isSubmitted() && $formc->isValid()) {
            $p=$em->getRepository(Produit::class)->find($id);
            $produitc->setProduit($p);
            $em->persist($produitc);
            $em->flush();

            $produitsduboutique = $em->getRepository(Produit_Boutique::class)->findBy(['boutique' => $usid]);

            //couleurs du produit
            $query=$em->createQuery(
                'SELECT c.couleurs
                 FROM BoutiqueBundle:Produitc c
             WHERE c.produit = :pid'
            )
                ->setParameter('pid',$id)
            ;
            $couleursduproduit=$query->getResult();


            return $this->redirectToRoute('bk_produits',[
                'username' => $username,

            ]);

                }
        return $this->render('@Boutique/bkndPages/ajout_couleur.html.twig',[
                    'username' => $username,
                    'boutique' => $us,
                    'formc' => $formc->createView()

                ]);


    }




    public function deleteproduitAction($id,$username){

        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->find($id);
        $prodbd=$em->getRepository(Produit_Boutique::class)->findOneBy(['produit'=>$produit->getId()]);
        $em->remove($prodbd);
        $em->flush();
        return $this->redirectToRoute('bk_produits',[
            'username' => $username,
        ]);
    }

    //m3aha el remise
    public function editproduitAction($id,$username, Request $request){
        $em=$this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $username);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }

        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->find($id);
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
