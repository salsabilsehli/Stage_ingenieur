<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Categorie;
use BoutiqueBundle\Entity\Categorie_Boutique;
use BoutiqueBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class CategoriesController extends Controller

{

    public function AjoutcategorieAction(Request $request,$username)
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
        $categorie = new Categorie();
        $form = $this->createForm('BoutiqueBundle\Form\CategorieType', $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            //enregistrement ds la table categorie_boutique
            $c_b= new Categorie_Boutique();
            $c_b->setBoutique($us);
            $c_b->setCategorie($categorie);

            $em->persist($c_b);
            $em->flush();

            $categoriesduboutique = $em->getRepository(Categorie_Boutique::class)->findBy(['boutique' => $usid]);

            return $this->render('@Boutique/bkndPages/categories.html.twig',[
                'username' => $username,
                'boutique' => $us,
                'categorie' => $categoriesduboutique
            ]);

        }

        return $this->render('@Boutique/bkndPages/ajout_categorie_page.twig',[
            'username' => $username,
            'boutique' => $us,
            'form' => $form->createView(),
        ]);
    }

    public function deletecategorieAction($id,$username){

            $em=$this->getDoctrine()->getManager();
            $categorie=$em->getRepository(Categorie::class)->find($id);
            $catbd=$em->getRepository(Categorie_Boutique::class)->findOneBy(['categorie'=>$categorie->getId()]);

        $em->remove($catbd);
            $em->flush();
            return $this->redirectToRoute('bk_categories',[
                'username' => $username,
            ]);
        }

    public function editcategorieAction($id,$username, Request $request){
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
        $categorie=$em->getRepository(Categorie::class)->find($id);
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
