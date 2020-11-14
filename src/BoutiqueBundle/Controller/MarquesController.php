<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Marque;
use BoutiqueBundle\Entity\Marque_Boutique;
use BoutiqueBundle\Form\MarqueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class MarquesController extends Controller
{
    public function AjoutmarqueAction(Request $request,$username)
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


        $marque = new Marque();
        $form = $this->createForm('BoutiqueBundle\Form\MarqueType', $marque);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($marque);
            $em->flush();

            //enregistrement ds la table marque_boutique
            $m_b= new Marque_Boutique();
            $m_b->setBoutique($us);
            $m_b->setMarque($marque);

            $em->persist($m_b);
            $em->flush();

            $marquesduboutique = $em->getRepository(Marque_Boutique::class)->findBy(['boutique' => $usid]);

            return $this->render('@Boutique/bkndPages/marques.html.twig',[
                'username' => $username,
                'boutique' => $us,
                'marque' => $marquesduboutique
            ]);

        }

        return $this->render('@Boutique/bkndPages/ajout_marque_page.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'form' => $form->createView(),
        ]);
    }

    public function deletemarqueAction($id,$username){

        $em=$this->getDoctrine()->getManager();
        $marque=$em->getRepository(Marque::class)->find($id);
        $marqbd=$em->getRepository(Marque_Boutique::class)->findOneBy(['marque'=>$marque->getId()]);
        $em->remove($marqbd);
        $em->flush();
        return $this->redirectToRoute('bk_marques',[
            'username' => $username,
        ]);
    }

    public function editmarqueAction($id,$username, Request $request){
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
        $marque=$em->getRepository(Marque::class)->find($id);
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
