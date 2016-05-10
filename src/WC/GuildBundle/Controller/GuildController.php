<?php

namespace WC\GuildBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WC\GuildBundle\Entity\Guild;

class GuildController extends Controller
{
    public function listAction($name, $server)
    {
        $em = $this->getDoctrine()->getManager();
        $guild = $em->getRepository('WCGuildBundle:Guild')->findOneBy(array('name' => $name, 'server' => $server));
        $user = $this->getUser();
        $myCharactersInGuild = $em->getRepository('WCUserBundle:Characters')->findBy(array('guild' => $guild, 'user' => $user));
        $charactersInGuild = $em->getRepository('WCUserBundle:Characters')->findBy(array('guild' => $guild));
        if($myCharactersInGuild !== null);
        {
            return $this->render('WCGuildBundle::guild.html.twig',
                array('guild' => $guild,
                'characters' => $charactersInGuild,
                'myCharacters' => $myCharactersInGuild));
        }
    }
}
