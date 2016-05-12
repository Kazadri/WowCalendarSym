<?php

namespace WC\GuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use WC\GuildBundle\Entity\CharactersBlackGuild;
use WC\GuildBundle\Entity\CharactersPuGuild;
use WC\GuildBundle\Entity\Guild;
use WC\UserBundle\Entity\Characters;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GuildController extends Controller
{
    public function listAction($name, $server, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $guild = $em->getRepository('WCGuildBundle:Guild')->findOneBy(array('name' => $name, 'server' => $server)); //La guilde
        $user = $this->getUser();
        $myCharactersInGuild = $em->getRepository('WCUserBundle:Characters')->findBy(array('guild' => $guild, 'user' => $user)); //Mes personnages de la guilde
        if($myCharactersInGuild !== null);
        {
            $realmsName = $this->get('wc_user.bnet')->getAllRealms($this->getParameter('bnetKey'));
            //Formulaire
            $formMember = $this->get("form.factory")->createNamedBuilder("formMember")
                ->add('name', TextType::class)
                ->add(
                    'server',
                    ChoiceType::class,
                    array(
                        'choices' => $realmsName,
                    )
                )
                ->add('ajouter', SubmitType::class)
                ->getForm();
            $formBlack = $this->get("form.factory")->createNamedBuilder("formBlack")
                ->add('Name', TextType::class)
                ->add(
                    'Server',
                    ChoiceType::class,
                    array(
                        'choices' => $realmsName,
                    )
                )
                ->add('Why', TextareaType::class)
                ->add('Ajouter', SubmitType::class)
                ->getForm();
            if('POST' === $request->getMethod()) {
                if ($request->request->has('formMember')) { //Handle du formulaire de membre
                    $formMember->handleRequest($request);
                    if ($formMember->isSubmitted() && $formMember->isValid()) {
                        $data = $formMember->getData();
                        $characterToAdd = $em->getRepository('WCUserBundle:Characters')->findOneBy(array('name' => $data['name'], 'server' => $data['server']));
                        if($characterToAdd != null){
                            $characterToAdd->setGuild($guild);
                            $characterToAdd->setRank("Membre");
                            $em->persist($characterToAdd);
                            $em->flush();
                        } else {
                            $request->getSession()->getFlashBag()->add(
                                'notice',
                                'Personnage non référencé'
                            );
                        }
                    }
                } else if($request->request->has('formBlack')){ //Handle du formulaire de membre
                    $formBlack->handleRequest($request);
                    if ($formBlack->isSubmitted() && $formBlack->isValid()) {
                        $data = $formBlack->getData();
                        $characterToBlack = $em->getRepository('WCUserBundle:Characters')->findOneBy(array('name' => $data['Name'], 'server' => $data['Server']));
                        if($characterToBlack != null) {
                            $black = new CharactersBlackGuild();
                            $black->setCharacters($characterToBlack);
                            $black->setGuilds($guild);
                            $black->setWhy($data['Why']);
                            $em->persist($black);
                            $em->flush();
                        } else {
                            $request->getSession()->getFlashBag()->add(
                                'notice',
                                'Personnage non référencé'
                            );
                        }
                    }
                }
            }

            //Donnée
            $charactersInGuild = $em->getRepository('WCUserBundle:Characters')->findBy(
                array('guild' => $guild)
            ); // Tous les personnages de la guilde
            $puInGuild = $em->getRepository('WCGuildBundle:CharactersPuGuild')->findBy(array('guilds' => $guild));
            $blackInGuild = $em->getRepository('WCGuildBundle:CharactersBlackGuild')->findBy(array('guilds' => $guild));
            $myCharacters = $em->getRepository('WCUserBundle:Characters')->findBy(array('user' => $user));
            $myPu = $em->getRepository('WCGuildBundle:CharactersPuGuild')->findBy(
                array('guilds' => $guild, 'characters' => $myCharacters)
            );

            return $this->render(
                'WCGuildBundle::guild.html.twig',
                array(
                    'guild' => $guild,
                    'characters' => $charactersInGuild,
                    'myCharacters' => $myCharactersInGuild,
                    'listPu' => $puInGuild,
                    'blackList' => $blackInGuild,
                    'myPu' => $myPu,
                    'formMember' => $formMember->createView(),
                    'formBlack' => $formBlack->createView()
                )
            );
        }
    }

    public function newAction(Request $request)
    {
        //Data
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $realmsName = $this->get('wc_user.bnet')->getAllRealms($this->getParameter('bnetKey'));
        $myCharactersWithoutGuild = $em->getRepository('WCUserBundle:Characters')->findBy(
            array('guild' => null, 'user' => $user)
        );
        $myCharactersInDemand = $em->getRepository('WCUserBundle:Characters')->findBy(
            array('rank' => "Demande", 'user' => $user)
        );
        //var_dump($myCharactersWithoutGuild);
        $myCharactersWithoutGuildNameServer = null;
        foreach ($myCharactersWithoutGuild as $character) {
            $myCharactersWithoutGuildNameServer[$character->getName()."/".$character->getServer()] = $character->getName()."/".$character->getServer();
        }
        foreach ($myCharactersInDemand as $character) {
            $myCharactersWithoutGuildNameServer[$character->getName()."/".$character->getServer()] = $character->getName()."/".$character->getServer();
        }
        var_dump($myCharactersWithoutGuildNameServer);
        //Formulaire
        $formGuild = $this->get("form.factory")->createNamedBuilder("formGuild")
            ->add('name', TextType::class)
            ->add(
                'server',
                ChoiceType::class,
                array(
                    'choices' => $realmsName,
                )
            )
            ->add(
                'Chef',
                ChoiceType::class,
                array(
                    'choices' => $myCharactersWithoutGuildNameServer,
                )
            )
            ->add('Créer', SubmitType::class)
            ->getForm();

        //Handle
        if ('POST' === $request->getMethod()) {
            if ($request->request->has('formGuild')) {
                $formGuild->handleRequest($request);
                if ($formGuild->isSubmitted() && $formGuild->isValid()) {
                    $data = $formGuild->getData();
                    $guild = new Guild();
                    $guild->setName($data['name']);
                    $guild->setServer($data['server']);
                    if ($this->get('wc_user.bnet')->guildIsValid(
                        $this->getParameter('bnetKey'),
                        $data['name'],
                        $data['server']
                    )) {
                        $em->persist($guild);
                        $nameServer = explode('/', $data['Chef']);
                        $chef = $em->getRepository('WCUserBundle:Characters')->findOneBy(
                            array('name' => $nameServer[0], 'server' => $nameServer[1])
                        );
                        $chef->setGuild($guild);
                        $chef->setRank("Chef");
                        $em->persist($chef);
                        $em->flush();
                        return $this->redirect($this->generateUrl('wc_guild_homepage', array('name'=>$data['name'], 'server'=>$data['server'])));
                    } else {
                        $request->getSession()->getFlashBag()->add(
                            'notice',
                            'La guilde que vous essayer de référencer n\'existe pas'
                        );
                        return $this->redirect($this->generateUrl('wc_guild_new'));
                    }
                }
            }
        }
        return $this->render(
            'WCGuildBundle::new.html.twig',
            array(
                'formGuild' => $formGuild->createView()
            )
        );
    }

    public function askAction(Request $request)
    {
        //Data
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $realmsName = $this->get('wc_user.bnet')->getAllRealms($this->getParameter('bnetKey'));
        $myCharactersWithoutGuild = $em->getRepository('WCUserBundle:Characters')->findBy(
            array('guild' => null, 'user' => $user)
        );
        //var_dump($myCharactersWithoutGuild);
        $myCharactersWithoutGuildNameServer = null;
        foreach ($myCharactersWithoutGuild as $character) {
            $myCharactersWithoutGuildNameServer[$character->getName()."/".$character->getServer(
            )] = $character->getName()."/".$character->getServer();
        }
        if ($myCharactersWithoutGuildNameServer != null) {
            var_dump($myCharactersWithoutGuildNameServer);
            //Formulaire
            $formGuild = $this->get("form.factory")->createNamedBuilder("formGuild")
                ->add('name', TextType::class)
                ->add(
                    'server',
                    ChoiceType::class,
                    array(
                        'choices' => $realmsName,
                    )
                )
                ->add(
                    'Personnage',
                    ChoiceType::class,
                    array(
                        'choices' => $myCharactersWithoutGuildNameServer,
                    )
                )
                ->add('Créer', SubmitType::class)
                ->getForm();

            //Handle
            if ('POST' === $request->getMethod()) {
                if ($request->request->has('formGuild')) {
                    $formGuild->handleRequest($request);
                    if ($formGuild->isSubmitted() && $formGuild->isValid()) {
                        $data = $formGuild->getData();
                        $guild = new Guild();
                        $guild->setName($data['name']);
                        $guild->setServer($data['server']);
                        if ($this->get('wc_user.bnet')->guildIsValid(
                            $this->getParameter('bnetKey'),
                            $data['name'],
                            $data['server']
                        )
                        ) {
                            $em->persist($guild);
                            $nameServer = explode('/', $data['Personnage']);
                            $demande = $em->getRepository('WCUserBundle:Characters')->findOneBy(
                                array('name' => $nameServer[0], 'server' => $nameServer[1])
                            );
                            $demande->setGuild($guild);
                            $demande->setRank("Demande");
                            $em->persist($demande);
                            $em->flush();
                        } else {
                            $request->getSession()->getFlashBag()->add(
                                'notice',
                                'La guilde que vous essayer de rejoindre n\'existe pas'
                            );
                        }
                    }
                }
            }
            $myCharactersAsk = $em->getRepository('WCUserBundle:Characters')->findBy(
                array('rank' => "Demande", 'user' => $user)
            );

            return $this->render(
                'WCGuildBundle::ask.html.twig',
                array(
                    'formGuild' => $formGuild->createView(),
                    'listDemand' => $myCharactersAsk
                )
            );
        }
        else
        {
            $myCharactersAsk = $em->getRepository('WCUserBundle:Characters')->findBy(
                array('rank' => "Demande", 'user' => $user)
            );

            return $this->render(
                'WCGuildBundle::ask.html.twig',
                array(
                    'listDemand' => $myCharactersAsk
                )
            );
        }
    }
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $characters = $em->getRepository('WCUserBundle:Characters')->findBy(
            array('user' => $user)
        );
        $without = false;
        $guilds = null;
        foreach($characters as $character){
            if($character->getGuild() != null && $character->getRank() != "Demande")
            {
                $guilds[$character->getGuild()->getName()."/".$character->getGuild()->getServer()]= $character->getGuild();
            }
            else
            {
                $without = true;
            }
        }
        return $this->render(
            'WCGuildBundle::menu.html.twig',
            array(
                'listGuild' => $guilds,
                'without' => $without
            )
        );
    }
}
