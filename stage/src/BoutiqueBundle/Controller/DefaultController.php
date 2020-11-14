<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Boutique;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //na9ra les noms de boutiques mel bd

        $query = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role'
            )->setParameter('role', '%"ROLE_AGENT"%' );

        $boutiques = $query->getResult();
        return $this->render('@Boutique/Default/index.html.twig',array(
            'boutiques' => $boutiques,
        )); //nab3eth les noms des boutiques
    }


    public function adminAction($username)
    {   $em = $this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);
        return $this->render('@Boutique/Default/backend.html.twig',[
            'username' => $username,
            'boutique' => $us,
        ]);
    }

    public function frontBoutiqueAction($username)
    {
        return $this->render('@Boutique/Default/frontBoutique.html.twig',[
            'username' => $username,
        ]);
    }
    public function frontloggedinAction($nomB,$cltname)
    {
        return $this->render('@Boutique/Default/frontclt.html.twig',[
            'nomB' => $nomB,
            'cltname' => $cltname,
        ]);
    }
}
