<?php
namespace WC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
        $bNet = new Bnet\ClientFactory($this->getParameter('bnetkey'));
        $armory = $bNet->warcraft(new Bnet\Region("eu"));
        $realms = $armory->realms()->all();
        $realmsName = array();
        foreach ($realms as $realm) {
            $realmsName[$realm->__get('name')] = $realm->__get('name'); //Texte affiché => Valeur
        }
        $realmsName['Suramar'] = 'Suramar'; //Ajout du serveur manquant
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
            $existingCharacter = $armory->characters()
                ->on($character->getServer())
                ->find($character->getName());
            if ($existingCharacter != null) {
                $character->setUser($user);
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
}