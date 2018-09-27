<?php

namespace MarsupilamiBundle\Controller;

use MarsupilamiBundle\Form\MarsupilamiType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $em=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $utilisateur=$em->getRepository("MarsupilamiBundle:Marsupilami")->find($user);
        return $this->render('MarsupilamiBundle:Default:index.html.twig',array("marsupilami"=>$utilisateur));
    }

    public function  ModifierAction($id, Request $request) {

        $em=$this->getDoctrine()->getManager() ;
        $marsupilami=$em->getRepository("MarsupilamiBundle:Marsupilami")->find($id);
        $Form=$this->createForm(MarsupilamiType::class, $marsupilami) ;
        $Form->handleRequest($request);
        if($Form->isValid()) {
            $em->persist($marsupilami) ;
            $em->flush() ;
            return $this->redirectToRoute('marsupilami_homepage') ; //thezni lel affiche
        }
        return $this->render('MarsupilamiBundle:Default:modifier.html.twig',
            array('form'=>$Form->createView())) ;


    }

    public function AmisAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $all=$em->getRepository("MarsupilamiBundle:Marsupilami")->findAll();
        $amis=$em->getRepository("MarsupilamiBundle:Marsupilami")->find($user->getId());
        $user->setAmis($amis);
        return $this->render("MarsupilamiBundle:Default:amis.html.twig", array("amis"=>$all)) ;
    }

    public function AddamisAction($id,Request $request){
        $em=$this->getDoctrine()->getManager();
        $amis=$em->getRepository("MarsupilamiBundle:Marsupilami")->find($id);
        $user=$this->getUser();

            $user->setAmis($amis);
            $amis->setAmis($user);
            $em=$this->getDoctrine()->getManager() ;
            $em->persist($user);
            $em->flush() ;
           return $this->redirectToRoute("marsupilami_amis");
        //return $this->render("MarsupilamiBundle:Default:amis.html.twig", array("amis"=>$all)) ;
    }

    public function SuppAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $amis= $em->getRepository('MarsupilamiBundle:Marsupilami')->findOneBy(array('id'=>$id)) ;
        $all=$em->getRepository("MarsupilamiBundle:Marsupilami")->findAll();
        $user=$this->getUser();
        $user->removeAmis($amis);
        $amis->removeAmis($user);
        $em->flush();
       return $this->redirectToRoute("marsupilami_amis");
        //return $this->render("MarsupilamiBundle:Default:amis.html.twig", array("amis"=>$all)) ;
    }



}
