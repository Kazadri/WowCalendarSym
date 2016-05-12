<?php
namespace WC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pwnraid\Bnet;
use WC\UserBundle\Entity\Characters;

class CharacterController extends Controller
{
    public function listAction(Request $request)
    {
        //Va chercher la liste des personnages du compte courant
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $listCharacters = $em
            ->getRepository('WCUserBundle:Characters')
            ->findBy(array('user' => $user));

        //Liste des serveurs
        $realmsName = $this->get('wc_user.bnet')->getAllRealms($this->getParameter('bnetKey'));
        //var_dump($realmsName);
        
        //Cree le formulaire de création de nouveaux personnages
        $character = new Characters();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $character);
        $formBuilder
            ->add('name', TextType::class)
            ->add(
                'server',
                ChoiceType::class,
                array(
                    'choices' => $realmsName,
                )
            )
            ->add('save', SubmitType::class);
        $form = $formBuilder->getForm();

        //Validation des données
        $form->handleRequest($request);
        if ($form->isValid()) { //Soit request est de type Post + est valide
            if ($this->get('wc_user.bnet')->characterIsValid($this->getParameter('bnetKey'), $character->getName(), $character->getServer())) {
                $character->setUser($user);
                $character->setClass($this->get('wc_user.bnet')->getClass($this->getParameter('bnetKey'), $character->getName(), $character->getServer()));
                $em = $this->getDoctrine()->getManager();
                $em->persist($character);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Personnage bien enregistrée.');
            } else {
                $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Personnage inexistant dans la base de données Battle.net'
                );
            }

            return $this->redirect($this->generateUrl('wc_character_list'));
        }

        //Sinon j'affiche le formulaire
        return $this->render(
            'WCUserBundle:Characters:list.html.twig',
            array('listCharacters' => $listCharacters, 'formCreateCharacter' => $form->createView())
        );
    }

    public function removeAction(Request $request){
        if($request->isXmlHttpRequest())
        {
            $name=$request->get('name');
            $server=$request->get('server');
            $em = $this->getDoctrine()->getManager();
            $character = $em->getRepository('WCUserBundle:Characters')->findOneBy(array('name'=>$name, 'server'=>$server));
            if($character->getRank()=="Chef"){
                return new JsonResponse(array('sucess' => false, 'why'=>"Chef"));
            }
            else{
                $em->remove($character);
                $em->flush();
                return new JsonResponse(array('sucess' => true));
            }
        }
    }
}