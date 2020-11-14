<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;

class GestionClientController extends Controller
{
    //commandes du client
    public function commandesClientAction($username,$id){

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

        $client=$em->getRepository(User::class)->find($id);

        //commandes du client

        $query=$em->createQuery(
            'SELECT  c
    FROM BoutiqueBundle:Commande c 
    WHERE c.boutique = :usid AND c.client = :cltid '
        )->setParameter('usid', $usid)
            ->setParameter('cltid', $id);

        $cmds=$query->getResult();


        $query1=$em->createQuery(
            'SELECT  c.id
    FROM BoutiqueBundle:Commande c 
    WHERE c.boutique = :usid AND c.client = :cltid '
        )->setParameter('usid', $usid)
            ->setParameter('cltid', $id);

        $cmdids=$query1->getResult();


        $query2=$em->createQuery(
            'SELECT  pc 
    FROM BoutiqueBundle:Produit_Commande pc 
    WHERE pc.commande IN (:cmdids)  '

        )->setParameter('cmdids', $cmdids);

        $q2=$query2->getResult();


        return $this->render('@Boutique/bkndPages/commandesclients.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'client'=>$client,
            'commandes'=>$cmds,
            'pcs'=>$q2
        ]);

    }


    //details d'une commande
    public function DetailCmdAction($username,$id,$cltid){

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

        $commande=$em->getRepository(Commande::class)->find($id);



        $query2=$em->createQuery(
            'SELECT  pc 
    FROM BoutiqueBundle:Produit_Commande pc 
    WHERE pc.commande = :cmdid  '

        )->setParameter('cmdid', $id);

        $pc=$query2->getResult();


        return $this->render('@Boutique/bkndPages/DetailCmd.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'cltid'=>$cltid,
            'pcs'=>$pc,
            'commande'=>$commande,
        ]);

    }

    //verifier commande
    public function validerCmdAction($username,$id,$cltid){

        $em = $this->getDoctrine()->getManager();

        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $username);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }


        $commande=$em->getRepository(Commande::class)->find($id);
        $commande->setValide(1);
        $commande->setNnvalide(0);

        $em->persist($commande);
        $em->flush();


        $query2=$em->createQuery(
            'SELECT  pc 
    FROM BoutiqueBundle:Produit_Commande pc 
    WHERE pc.commande = :cmdid  '

        )->setParameter('cmdid', $id);

        $pc=$query2->getResult();


        return $this->render('@Boutique/bkndPages/DetailCmd.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'cltid'=>$cltid,
            'pcs'=>$pc,
            'commande'=>$commande
        ]);

    }
}
