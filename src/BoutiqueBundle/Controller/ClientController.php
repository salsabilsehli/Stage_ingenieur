<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Categorie;
use BoutiqueBundle\Entity\Categorie_Boutique;
use BoutiqueBundle\Entity\Commande;
use BoutiqueBundle\Entity\Filtre;
use BoutiqueBundle\Entity\Marque;
use BoutiqueBundle\Entity\Produit;
use BoutiqueBundle\Entity\Produit_Commande;
use BoutiqueBundle\Entity\WishList;
use BoutiqueBundle\Entity\WishList_Boutique;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;

class ClientController extends Controller
{

    public function frontloggedinAction(Request $request,$nomB,$cltname,SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
                 foreach ($cl as $clt) {
                     $client = $clt;
                 }




        //produits du boutique
        $queryids=$em->createQuery(
            'SELECT IDENTITY(p.produit)
    FROM BoutiqueBundle:Produit_Boutique p
    WHERE p.boutique = :usid'
        )->setParameter('usid', $usid);
        $prdsids=$queryids->getResult();



        //produits

        $query=$em->createQuery(
            'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) '
        )
            ->setParameter('prdsid',$prdsids)

        ;


        $prds = $query->getResult();

        //products pagination
        $prdspaginate=$this->get('knp_paginator')->paginate($prds,$request->query->get('page',1),9);
        //categories du boutique
        $categoriesduboutique=$em->getRepository(Categorie_Boutique::class)->findBy(['boutique' => $usid]);

        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();

        }

        //filtre form
        $filtre = new Filtre();
        $form = $this->createForm('BoutiqueBundle\Form\FiltreType', $filtre);
        $form->handleRequest($request);

        $panier=$session->get('pp',[]);
        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }
        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }


        if ($form->isSubmitted() && $form->isValid()) {

            $categorie= $filtre->getCategorie();
            $idc=$categorie->getId();
            //0 non
            $livraison=$filtre->isLivgrat();
            //prix min et max

            $prixmin=$request->request->get('prixmin');
            $prixmax=$request->request->get('prixmax');


            if($filtre->isLivgrat()=='rien'){
                if($prixmin==null && $prixmax==null){

                    //produits filtrés

                    $query=$em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid'
                    )
                        ->setParameter('prdsid',$prdsids)
                        ->setParameter('catid',$idc)

                    ;



                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre=$this->get('knp_paginator')->paginate($prodsfiltres,$request->query->get('page',1),9);

                    return $this->render('@Boutique/cltpages/frontBoutiqueCatclt.html.twig',[
                        'username' => $nomB,
                        'boutique' => $us,
                        'client'=>$client,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'panier' => $session->get('pp'),
                        'prixTT' => $prixtot,
                        'nngrat'=>$nngrat,
                        'theme'=>$theme,
                        'nomclt' => $cltname,


                    ]);




                }
                elseif ($prixmax==null && $prixmin!=null){
                    //produits filtrés

                    $query=$em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.prix >= :prixmin'
                    )
                        ->setParameter('prdsid',$prdsids)
                        ->setParameter('catid',$idc)
                        ->setParameter('prixmin',$prixmin)
                    ;



                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre=$this->get('knp_paginator')->paginate($prodsfiltres,$request->query->get('page',1),9);


                    return $this->render('@Boutique/cltpages/frontBoutiqueCatclt.html.twig',[
                        'username' => $nomB,
                        'boutique' => $us,
                        'client'=>$client,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'panier' => $session->get('pp'),
                        'prixTT' => $prixtot,
                        'nngrat'=>$nngrat,
                        'theme'=>$theme,
                        'nomclt' => $cltname,


                    ]);


                }
                elseif ($prixmin==null && $prixmax!=null){
                    //produits filtrés

                    $query=$em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.prix <= :prixmax'
                    )
                        ->setParameter('prdsid',$prdsids)
                        ->setParameter('catid',$idc)
                        ->setParameter('prixmax',$prixmax)
                    ;



                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre=$this->get('knp_paginator')->paginate($prodsfiltres,$request->query->get('page',1),9);


                    return $this->render('@Boutique/cltpages/frontBoutiqueCatclt.html.twig',[
                        'username' => $nomB,
                        'boutique' => $us,
                        'client'=>$client,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'panier' => $session->get('pp'),
                        'prixTT' => $prixtot,
                        'nngrat'=>$nngrat,
                        'theme'=>$theme,
                        'nomclt' => $cltname,


                    ]);

                }
                elseif ($prixmin!=null && $prixmax!=null){
                    //produits filtrés

                    $query=$em->createQuery(
                        'SELECT p
                         FROM BoutiqueBundle:Produit p
                         WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.prix <= :prixmax AND p.prix >= :prixmin'
                    )
                        ->setParameter('prdsid',$prdsids)
                        ->setParameter('catid',$idc)
                        ->setParameter('prixmax',$prixmax)
                        ->setParameter('prixmin',$prixmin)
                    ;



                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre=$this->get('knp_paginator')->paginate($prodsfiltres,$request->query->get('page',1),9);

                    return $this->render('@Boutique/cltpages/frontBoutiqueCatclt.html.twig',[
                        'username' => $nomB,
                        'boutique' => $us,
                        'client'=>$client,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'panier' => $session->get('pp'),
                        'prixTT' => $prixtot,
                        'nngrat'=>$nngrat,
                        'theme'=>$theme,
                        'nomclt' => $cltname,


                    ]);

                }


            }

            elseif($filtre->isLivgrat()!='rien'){
                if($prixmin==null && $prixmax==null){

                    //produits filtrés

                    $query=$em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.livgrat = :liv'
                    )
                        ->setParameter('prdsid',$prdsids)
                        ->setParameter('catid',$idc)
                        ->setParameter('liv',$livraison)

                    ;



                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre=$this->get('knp_paginator')->paginate($prodsfiltres,$request->query->get('page',1),9);


                    return $this->render('@Boutique/cltpages/frontBoutiqueCatclt.html.twig',[
                        'username' => $nomB,
                        'boutique' => $us,
                        'client'=>$client,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'panier' => $session->get('pp'),
                        'prixTT' => $prixtot,
                        'nngrat'=>$nngrat,
                        'theme'=>$theme,
                        'nomclt' => $cltname,


                    ]);

                }
                elseif ($prixmax==null && $prixmin!=null){
                    //produits filtrés

                    $query=$em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.prix >= :prixmin AND p.livgrat = :liv'
                    )
                        ->setParameter('prdsid',$prdsids)
                        ->setParameter('catid',$idc)
                        ->setParameter('prixmin',$prixmin)
                        ->setParameter('liv',$livraison)
                    ;



                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre=$this->get('knp_paginator')->paginate($prodsfiltres,$request->query->get('page',1),9);


                    return $this->render('@Boutique/cltpages/frontBoutiqueCatclt.html.twig',[
                        'username' => $nomB,
                        'boutique' => $us,
                        'client'=>$client,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'panier' => $session->get('pp'),
                        'prixTT' => $prixtot,
                        'nngrat'=>$nngrat,
                        'theme'=>$theme,
                        'nomclt' => $cltname,


                    ]);


                }
                elseif ($prixmin==null && $prixmax!=null){
                    //produits filtrés

                    $query=$em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.prix <= :prixmax AND p.livgrat = :liv'
                    )
                        ->setParameter('prdsid',$prdsids)
                        ->setParameter('catid',$idc)
                        ->setParameter('prixmax',$prixmax)
                        ->setParameter('liv',$livraison)
                    ;



                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre=$this->get('knp_paginator')->paginate($prodsfiltres,$request->query->get('page',1),9);


                    return $this->render('@Boutique/cltpages/frontBoutiqueCatclt.html.twig',[
                        'username' => $nomB,
                        'boutique' => $us,
                        'client'=>$client,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'panier' => $session->get('pp'),
                        'prixTT' => $prixtot,
                        'nngrat'=>$nngrat,
                        'theme'=>$theme,
                        'nomclt' => $cltname,


                    ]);

                }
                elseif ($prixmin!=null && $prixmax!=null){
                    //produits filtrés

                    $query=$em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid p.prix <= :prixmax AND p.prix >= :prixmin AND p.livgrat = :liv'
                    )
                        ->setParameter('prdsid',$prdsids)
                        ->setParameter('catid',$idc)
                        ->setParameter('prixmax',$prixmax)
                        ->setParameter('prixmin',$prixmin)
                        ->setParameter('liv',$livraison)
                    ;



                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre=$this->get('knp_paginator')->paginate($prodsfiltres,$request->query->get('page',1),9);


                    return $this->render('@Boutique/cltpages/frontBoutiqueCatclt.html.twig',[
                        'username' => $nomB,
                        'boutique' => $us,
                        'client'=>$client,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'panier' => $session->get('pp'),
                        'prixTT' => $prixtot,
                        'nngrat'=>$nngrat,
                        'theme'=>$theme,
                        'nomclt' => $cltname,


                    ]);

                }


            }






        }

        return $this->render('@Boutique/cltpages/frontclt.html.twig',[
            'username' => $nomB,
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'produits' => $prdspaginate,
            'produitss' => $prds,
            'categories' => $categoriesduboutique,
            'form' => $form->createView(),
            'nomclt' => $cltname,
            'client' => $client,
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'theme'=>$theme,



        ]);
    }

    public function comptecltAction($nomB,$cltname,SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }

        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }

        $panier=$session->get('pp',[]);
        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }
        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }
        return $this->render('@Boutique/cltpages/compteclt.html.twig',[
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'username' => $nomB,
            'client' => $client,
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'theme'=>$theme

        ]);
    }

    //adding main informations

    public function ajoutinfocltAction(Request $request,$nomB,$cltname,SessionInterface $session){

        $em=$this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }
        $form=$this->createForm(UserType::class,$client);
        $form->handleRequest($request);

        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }
        $panier=$session->get('pp',[]);
        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }
        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();


            return $this->render('@Boutique/cltpages/compteclt.html.twig',[
                'boutique' => $us,
                'num' => $num,
                'email' => $email,
                'username' => $nomB,
                'client' => $client,
                'panier' => $session->get('pp'),
                'prixTT' => $prixtot,
                'nngrat'=>$nngrat,
                'theme'=>$theme
            ]);

        }


        return $this->render('@Boutique/cltpages/ajoutinfoclt.html.twig',[
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'username' => $nomB,
            'client' => $client,
            'form' => $form->createView(),
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'theme'=>$theme
        ]);
    }

    //editing main informations

    public function editinfocltAction(Request $request,$nomB,$cltname,SessionInterface $session){

        $em=$this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }
        $form=$this->createForm(UserType::class,$client);
        $form=$form->handleRequest($request);
        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }
        $panier=$session->get('pp',[]);
        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }
        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }
        if($form->isSubmitted()){
            $em->flush();
            return $this->redirectToRoute('compte_clt',[
                'cltname' => $cltname,
                'nomB' => $nomB,
                'boutique' => $us,
                'num' => $num,
                'email' => $email,
                'username' => $nomB,
                'client' => $client,
                'panier' => $session->get('pp'),
                'prixTT' => $prixtot,
                'nngrat'=>$nngrat,
                'theme'=>$theme
            ]);       }


        return $this->render('@Boutique/cltpages/ajoutinfoclt.html.twig',[
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'username' => $nomB,
            'client' => $client,
            'form' => $form->createView(),
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'theme'=>$theme
        ]);
    }

    //wishlist
    public function mesSouhaitsAction(Request $request,$nomB, $cltname,SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }
        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }
        foreach ($cl as $clt) {

            $cltid=$clt->getId();
        }


        //get wishlist du client dans le boutique from db

        $query=$em->createQuery(
            'SELECT IDENTITY(t.wishlist)
             FROM BoutiqueBundle:WishList_Boutique t
             WHERE t.boutique = :usid'
        )
            ->setParameter('usid',$usid)
        ;



        $wishlistids = $query->getResult();

        $query2=$em->createQuery(
            'SELECT w
             FROM BoutiqueBundle:WishList w
             WHERE w.id IN (:wids) AND w.client = :cltid'
        )
            ->setParameter('wids',$wishlistids)
            ->setParameter('cltid',$cltid)
        ;

        $products = $query2->getResult();
        //products pagination
        $prdspaginate=$this->get('knp_paginator')->paginate($products,$request->query->get('page',1),3);
        $panier=$session->get('pp',[]);
        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }
        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }

        return $this->render('@Boutique/cltpages/mesSouhaits.html.twig',[
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'username' => $nomB,
            'client' => $client,
            'wishlist'=> $products,
            'produits' =>$prdspaginate,
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'theme'=>$theme

        ]);
    }

    //add to wishlist
    public function ajoutSouhaitsAction(Request $request,$nomB,$cltname,$id,SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }

        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }
        foreach ($cl as $clt) {

            $cltid=$clt->getId();
        }

        $produit = $em->getRepository(Produit::class)->find($id);



            //enregistrement ds la table Wishlist et wishlist_boutique
            $p_c= new WishList();
            $p_c->setProduit($produit);
            $p_c->setClient($client);

        $em->persist($p_c);
        $em->flush();

        $wishlist=$em->getRepository(WishList::class)->findOneBy(['client'=>$cltid,'produit'=>$id]);

            $w_b=new WishList_Boutique();
            $w_b->setBoutique($us);
            $w_b->setWishlist($wishlist);


            $em->persist($w_b);
            $em->flush();

        $panier=$session->get('pp',[]);
        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }
        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }
        return $this->redirectToRoute('cltloggedin_index',[
            'nomB' => $nomB,
            'cltname' => $cltname,  'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'theme'=>$theme

        ]);


    }

    //delete from wishlist
    public function deleteSouhaitsAction(Request $request,$nomB,$cltname,$id,SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }
        $w=$em->getRepository(WishList::class)->find($id);
      $em->remove($w);
        $em->flush();
        $panier=$session->get('pp',[]);
        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }
        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }

        return $this->redirectToRoute('mes_souhaits',[
            'nomB' => $nomB,
            'cltname' => $cltname,
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'theme'=>$theme
        ]);


    }

    //product-details
    public function detailsproduitAction(Request $request,$nomB, $cltname,$id,SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }

        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }
        foreach ($cl as $clt) {

            $cltid=$clt->getId();
        }
        $produit=$em->getRepository(Produit::class)->find($id);
        $categorieid=$produit->getCategorie();
        $categorie=$em->getRepository(Categorie::class)->find($categorieid);


        //couleurs du produit
        $query=$em->createQuery(
            'SELECT c.couleurs
                 FROM BoutiqueBundle:Produitc c
             WHERE c.produit = :pid'
        )
            ->setParameter('pid',$id)
        ;
        $couleursduproduit=$query->getResult();

        $marque1=$produit->getMarque();
     $marque2=$marque1->getMarque();

     //produits du meme marque
        $query2=$em->createQuery(
            'SELECT p
             FROM BoutiqueBundle:Produit p
             WHERE p.marque = :marqueid AND p.id != :pid'
        )
            ->setParameter('marqueid',$marque1)
            ->setParameter('pid',$id)
        ;
     $produitsmememarque=$query2->getResult();
        $panier=$session->get('pp',[]);

        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }

        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }

        return $this->render('@Boutique/cltpages/product_details.html.twig',[
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'username' => $nomB,
            'client' => $client,
            'produit' =>$produit,
            'marque' => $marque2,
            'categorie' => $categorie,
            'couleurs' => $couleursduproduit,
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'produitsmmmarque' => $produitsmememarque,
            'msgerror'=>null,
            'theme'=>$theme

        ]);
    }

    //add to panier
    public function addtopanierAction(Request $request,$id,SessionInterface $session, $nomB, $cltname)
    {


        $em = $this->getDoctrine()->getManager();

        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }
        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }
        foreach ($cl as $clt) {

            $cltid=$clt->getId();
        }

        $qte = $request->request->get('quanitySniper');
        $taille = $request->request->get('taille');


        $couleur = $request->request->get('couleur');
        $produit = $em->getRepository(Produit::class)->find($id);
        $image = $produit->getImage();
        $nomProd = $produit->getNomProd();
        $prix = $produit->getPrix();
        $remise = $produit->getRemise();
        $livr = $produit->isLivgrat();

        $categorieid=$produit->getCategorie();
        $categorie=$em->getRepository(Categorie::class)->find($categorieid);
        //couleurs du produit
        $query=$em->createQuery(
            'SELECT c.couleurs
                 FROM BoutiqueBundle:Produitc c
             WHERE c.produit = :pid'
        )
            ->setParameter('pid',$id)
        ;
        $couleursduproduit=$query->getResult();

        $marque1=$produit->getMarque();
        $marque2=$marque1->getMarque();

        //produits du meme marque
        $query2=$em->createQuery(
            'SELECT p
             FROM BoutiqueBundle:Produit p
             WHERE p.marque = :marqueid AND p.id != :pid'
        )
            ->setParameter('marqueid',$marque1)
            ->setParameter('pid',$id)
        ;
        $produitsmememarque=$query2->getResult();

        $panier = $session->get('pp', []);

        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']*(100- $array['remise'])  / 100;
        }

        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }
        $qtestock=$produit->getQteStock();


        if($qtestock==0){
            return $this->render('@Boutique/cltpages/product_details.html.twig',[
                'boutique' => $us,
                'num' => $num,
                'email' => $email,
                'username' => $nomB,
                'client' => $client,
                'produit' =>$produit,
                'marque' => $marque2,
                'categorie' => $categorie,
                'couleurs' => $couleursduproduit,
                'panier' => $session->get('pp'),
                'prixTT' => $prixtot,
                'nngrat'=>$nngrat,
                'produitsmmmarque' => $produitsmememarque,
                'msgerror'=>'Ce produit est en rupture de stock !',
                'theme'=>$theme

            ]);
        }
       else if($qte==0){
            return $this->render('@Boutique/cltpages/product_details.html.twig',[
                'boutique' => $us,
                'num' => $num,
                'email' => $email,
                'username' => $nomB,
                'client' => $client,
                'produit' =>$produit,
                'marque' => $marque2,
                'categorie' => $categorie,
                'couleurs' => $couleursduproduit,
                'panier' => $session->get('pp'),
                'prixTT' => $prixtot,
                'nngrat'=>$nngrat,
                'produitsmmmarque' => $produitsmememarque,
                'msgerror'=>'Le champs quantité ne doit pas etre null !',
                'theme'=>$theme

            ]);
        }

       else if($qte > $qtestock){
           return $this->render('@Boutique/cltpages/product_details.html.twig',[
               'boutique' => $us,
               'num' => $num,
               'email' => $email,
               'username' => $nomB,
               'client' => $client,
               'produit' =>$produit,
               'marque' => $marque2,
               'categorie' => $categorie,
               'couleurs' => $couleursduproduit,
               'panier' => $session->get('pp'),
               'prixTT' => $prixtot,
               'nngrat'=>$nngrat,
               'produitsmmmarque' => $produitsmememarque,
               'msgerror'=>'Il reste seulement ' . $qtestock . ' exemplaires de ce produit !',
               'theme'=>$theme

           ]);
       }

       else if($taille=='null'){
           return $this->render('@Boutique/cltpages/product_details.html.twig',[
               'boutique' => $us,
               'num' => $num,
               'email' => $email,
               'username' => $nomB,
               'client' => $client,
               'produit' =>$produit,
               'marque' => $marque2,
               'categorie' => $categorie,
               'couleurs' => $couleursduproduit,
               'panier' => $session->get('pp'),
               'prixTT' => $prixtot,
               'nngrat'=>$nngrat,
               'produitsmmmarque' => $produitsmememarque,
               'msgerror'=>'Le champs taille doit etre rempli !',
               'theme'=>$theme

           ]);
       }





        $tab = array(
            'id' => $id,
            'qte' => $qte,
            'taille' => $taille,
            'couleur' => $couleur,
            'image' => $image,
            'nomProd' => $nomProd,
            'prix' => $prix,
            'remise' => $remise,
            'livr' => $livr
        );


        $panier[$id] = $tab;

        $session->set('pp', $panier);
        $prixtot = 0;
        foreach ($panier as $array) {
            if ($array['remise'] == 0) {
                $prixtot += $array['prix'] * $array['qte'];
            } elseif ($array['remise'] != 0)
                $prixtot += $array['prix'] * $array['qte'] * (100- $array['remise']) / 100;
        }
        $nngrat = 0;
        foreach ($panier as $array) {
            if ($array['livr'] == 0) {
                //nn gratuite
                $nngrat += 1;
            }

        }

        //produits du boutique
        $queryids = $em->createQuery(
            'SELECT IDENTITY(p.produit)
    FROM BoutiqueBundle:Produit_Boutique p
    WHERE p.boutique = :usid'
        )->setParameter('usid', $usid);
        $prdsids = $queryids->getResult();


        //produits

        $query = $em->createQuery(
            'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) '
        )
            ->setParameter('prdsid', $prdsids);


        $prds = $query->getResult();
        return $this->render('@Boutique/cltpages/panier.html.twig', [
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'username' => $nomB,
            'client' => $client,
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat' => $nngrat,
            'produits' => $prds,
            'theme'=>$theme

        ]);
    }

    //delete from panier
    public function deletefrompanierAction($id,SessionInterface $session, $nomB, $cltname)
{
    $em = $this->getDoctrine()->getManager();
    $q = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
        )->setParameter('role', '%"ROLE_AGENT"%')
        ->setParameter('username', $nomB);

    $user = $q->getResult();
    foreach ($user as $u) {
        $us = $u;
    }
    foreach ($user as $u) {
        $usid = $u->getId();
    }
    foreach ($user as $u) {
        $num=$u->getNum();
    }
    foreach ($user as $u) {
        $email=$u->getEmail();
    }
    foreach ($user as $u) {
        $theme=$u->getTheme();

    }
    $q2 = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
        )->setParameter('role', '%"ROLE_CLIENT"%')
        ->setParameter('username', $cltname);
    $cl=$q2->getResult();
    foreach ($cl as $clt) {
        $client = $clt;
    }
    foreach ($cl as $clt) {

        $cltid=$clt->getId();
    }
    $panier = $session->get('pp', []);

    unset($panier[$id]);

    $session->set('pp', $panier);
    $panier = $session->get('pp');
    $prixtot = 0;
    $prixremise = 0;
    foreach ($panier as $array) {
        $prixtot += $array['prix'];
    }
    foreach ($panier as $array) {
        $prixremise += $array['remise'];
    }
    $prixTT = $prixtot - $prixremise;
    $nngrat = 0;
    foreach ($panier as $array) {
        if ($array['livr'] == 0) {
            //nn gratuite
            $nngrat += 1;
        }

    }

    //produits du boutique
    $queryids = $em->createQuery(
        'SELECT IDENTITY(p.produit)
    FROM BoutiqueBundle:Produit_Boutique p
    WHERE p.boutique = :usid'
    )->setParameter('usid', $usid);
    $prdsids = $queryids->getResult();


    //produits

    $query = $em->createQuery(
        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) '
    )
        ->setParameter('prdsid', $prdsids);


    $prds = $query->getResult();
    return $this->render('@Boutique/cltpages/panier.html.twig', [
        'boutique' => $us,
        'num' => $num,
        'email' => $email,
        'username' => $nomB,
        'client' => $client,
        'panier' => $session->get('pp'),
        'prixTT' => $prixTT,
        'nngrat' => $nngrat,
        'produits' => $prds,
        'theme'=>$theme
    ]);
}

    //maj panier
    public function majpanierAction(SessionInterface $session, $nomB, $cltname)
    {
        $em = $this->getDoctrine()->getManager();

        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }
        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }
        foreach ($cl as $clt) {

            $cltid=$clt->getId();
        }
        $panier=$session->get('pp');

        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }
        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }

        $session->set('pp',[]);

        return $this->render('@Boutique/cltpages/panier.html.twig',[
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'username' => $nomB,
            'client' => $client,
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'theme'=>$theme
        ]);
    }

    //show panier
    public function showpanierAction(SessionInterface $session, $nomB, $cltname)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }

        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }
        foreach ($cl as $clt) {

            $cltid=$clt->getId();
        }
        $panier=$session->get('pp');

        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }
        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }


        //produits du boutique
        $queryids=$em->createQuery(
            'SELECT IDENTITY(p.produit)
    FROM BoutiqueBundle:Produit_Boutique p
    WHERE p.boutique = :usid'
        )->setParameter('usid', $usid);
        $prdsids=$queryids->getResult();



        //produits

        $query=$em->createQuery(
            'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) '
        )
            ->setParameter('prdsid',$prdsids)

        ;


        $prds = $query->getResult();


        return $this->render('@Boutique/cltpages/panier.html.twig',[
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'username' => $nomB,
            'client' => $client,
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'produits'=>$prds,
            'theme'=>$theme

        ]);


    }

    //commander

    public function commandeAction(SessionInterface $session, $nomB, $cltname)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }
        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }
        foreach ($cl as $clt) {

            $cltid=$clt->getId();
        }

        $panier=$session->get('pp');

        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }


        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }

        $prixtot=$prixtot*1.055;

        $commande=new Commande();
        $commande->setBoutique($us);
        $commande->setClient($client);
        $commande->setValide(0);
        $commande->setNnvalide(1);
        $commande->setPrix($prixtot);
        if($nngrat >= 1){
            $commande->setLivgrat(0);
        }
        else{
            $commande->setLivgrat(1);
        }


        $em->persist($commande);
        $em->flush();


        foreach ($panier as $array){
            $id=$array['id'];
            $couleur=$array['couleur'];
            $taille=$array['taille'];
            $qte=$array['qte'];
            $produit=$em->getRepository(Produit::class)->find($id);
            $qtestock=$produit->getQteStock();
            $produit->setQteStock($qtestock - $qte);
            $em->persist($produit);
            $em->flush();
           $p_c=new Produit_Commande();
           $p_c->setCommande($commande);
           $p_c->setProduit($produit);
           $p_c->setCouleur($couleur);
           $p_c->setTaille($taille);
           $p_c->setQuantite($qte);

           $em->persist($p_c);
           $em->flush();


        }




        $session->set('pp',[]);

        $idcmd=$commande->getId();

        //produits du commande

        $queryids=$em->createQuery(
            'SELECT p
    FROM BoutiqueBundle:Produit_Commande p
    WHERE p.commande = :cmdid'
        )->setParameter('cmdid', $idcmd);

        $prds=$queryids->getResult();





        $prixtot=0;



        return $this->render('@Boutique/cltpages/merciCommande.html.twig',[
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'username' => $nomB,
            'client' => $client,
            'panier' => $session->set('pp',[]),
            'prixTT' => $prixtot,
            'produits'=>$prds,
            'commande'=>$commande,
            'theme'=>$theme

        ]);


    }

    //historique des commandes
    public function HistocommandeAction(SessionInterface $session, $nomB, $cltname)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }

        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }
        foreach ($cl as $clt) {

            $cltid=$clt->getId();
        }

        $panier=$session->get('pp',[]);
        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }
        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }

        $query=$em->createQuery(
            'SELECT c
    FROM BoutiqueBundle:Commande c
    WHERE c.client = :cltid AND c.boutique = :usid'
        )->setParameter('cltid', $cltid)
            ->setParameter('usid', $usid);

        $commandes=$query->getResult();






        return $this->render('@Boutique/cltpages/mesCommandes.html.twig',[
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'username' => $nomB,
            'client' => $client,
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'commandes'=>$commandes,
            'theme'=>$theme

        ]);

    }

    //details comamnde
    public function detailcommandeAction(SessionInterface $session, $nomB, $cltname,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $nomB);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        foreach ($user as $u) {
            $num=$u->getNum();
        }
        foreach ($user as $u) {
            $email=$u->getEmail();
        }
        foreach ($user as $u) {
            $theme=$u->getTheme();

        }
        $q2 = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_CLIENT"%')
            ->setParameter('username', $cltname);
        $cl=$q2->getResult();
        foreach ($cl as $clt) {
            $client = $clt;
        }
        foreach ($cl as $clt) {

            $cltid=$clt->getId();
        }


        $panier=$session->get('pp',[]);
        $prixtot=0;
        foreach ($panier as $array){
            if($array['remise']==0){
                $prixtot += $array['prix']*$array['qte'];
            }
            elseif ($array['remise']!=0)
                $prixtot +=$array['prix']*$array['qte']* (100- $array['remise']) / 100;
        }
        $nngrat=0;
        foreach ($panier as $array){
            if($array['livr']==0){
                //nn gratuite
                $nngrat+=1;
            }

        }

        $commande=$em->getRepository(Commande::class)->find($id);

        $query2=$em->createQuery(
            'SELECT  pc 
    FROM BoutiqueBundle:Produit_Commande pc 
    WHERE pc.commande = :cmdid  '

        )->setParameter('cmdid', $id);

        $pc=$query2->getResult();



        return $this->render('@Boutique/cltpages/detailsCommande.html.twig',[
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'username' => $nomB,
            'client' => $client,
            'panier' => $session->get('pp'),
            'prixTT' => $prixtot,
            'nngrat'=>$nngrat,
            'pcs'=>$pc,
            'commande'=>$commande,
            'theme'=>$theme

        ]);

    }


}

