<?php

namespace WC\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WC\BaseBundle\Entity\Recovery;

class IndexController extends Controller
{
    public function indexAction(Request $request)
    {
    	$session = $request->getSession();

    	$userId = $session->get('user_id');

    	$content = $this->get('templating')->render('WCBaseBundle:Index:index.html.twig');
    	return $this->get('templating')->renderResponse(
	      	'WCBaseBundle:Index:index.html.twig',
	      	array('user_id'  => $userId)
    	);
    }

    public function connexionAction()
    {
    	if ($request->isXmlHttpRequest())
		{

		  	// C'est une requête AJAX, retournons du JSON, par exemple
		  	return new JsonResponse(array('reponse' => "Done"));
			return $reponse;
		}
    	return new Response("Vous essayer de vous connecter par l'ancienne méthode de connexion");
    }

    public function inscriptionAction()
    {
    	return new Response("Vous essayer de vous inscrire");
    }

    public function recuperationAction($token, Request $request)
    {
    	$pseudo = $request->request->get('pseudo');
		$mail = $request->request->get('mail');
		$em = $this->getDoctrine()->getManager();
    	// $pseudo = "Kazadri";
		// $mail = "t.chieux@gmail.com";
		$pass = $request->request->get('new_password');
		$submit = $request->request->get('submit_recup');

    	if($submit != "")
    	{
    		// $em = $this->getDoctrine()->getManager();
	    	// $recovryRepository = $em->getRepository('WCBaseBundle:Recovery');
    		// $recovery = $recovryRepository->findByToken($token);
    		// $em->remove($recovery);
    		// $em->flush();
    		return $this->redirect($this->get('router')->generate('wc_index_base'));
    	}
    	else if($pseudo != "" && $mail != "")
    	{
    		$em = $this->getDoctrine()->getManager();
	    	$recovryRepository = $em->getRepository('WCBaseBundle:Recovery');
	    	$recovery = new Recovery();
	    	$recovery->setUsername($pseudo);
			$em->persist($recovery);
			$em->flush();

    		$mailer = $this->get('mailer');
    		$message = \Swift_Message::newInstance()
    		    ->setSubject('Wow-calendar : Recuperation de votre mot de passe')
    		    ->setFrom('wow.calendar.fa@gmail.com')
    		    ->setTo($mail)
    		    ->setBody(
    		    	'Lien : www.wowcalendar.net/recuperation.php?token='.$recovery->getToken(),
    		    	'text/html'
    		    	)
    		   	;
    		$mailer->send($message);
    		return $this->get('templating')->renderResponse(
	      		'WCBaseBundle:Index:recuperation.html.twig',
	      		array('etape'  => "1")
	    	);
    	}
    	else
    	{
    		return $this->get('templating')->renderResponse(
	      		'WCBaseBundle:Index:recuperation.html.twig',
	      		array('etape'  => "2")
	    	);
    	}
    }
}
