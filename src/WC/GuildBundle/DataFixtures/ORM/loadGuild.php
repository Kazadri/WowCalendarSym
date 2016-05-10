<?php
namespace WC\Guild\Bundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WC\UserBundle\Entity\Characters;
use WC\GuildBundle\Entity\Guild;
use WC\GuildBundle\Entity\CharactersBlackGuild;
use WC\GuildBundle\Entity\CharactersPuGuild;
use WC\UserBundle\WCUserBundle;


class LoadGuild implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $guild = new Guild;
        $guild->setName("Chess Panda Squad");
        $guild->setServer("Les Sentinelles");

        $characters=$manager->getRepository('WCUserBundle:Characters')->findAll();
        $i=0; $y=0;
        foreach($characters as $character){
            switch ($i) {
                case 0 :
                    $character->setGuild($guild);
                    $character->setRank('Membre');
                    $manager->persist($character);
                    $i++;
                    break;
                case 1 :
                    $pu[$y] = new CharactersPuGuild();
                    $pu[$y]->setCharacters($character);
                    $pu[$y]->setGuilds($guild);
                    $pu[$y]->setState("Demande");
                    $manager->persist($pu[$y]);
                    $i++;
                    $y++;
                    break;
                case 2 :
                    $black[$y] = new CharactersBlackGuild();
                    $black[$y]->setGuilds($guild);
                    $black[$y]->setCharacters($character);
                    $black[$y]->setWhy("The cake is a lie");
                    $manager->persist($black[$y]);
                    $i = 0;
                    $y++;
                    break;
            }
        }
        $manager->flush();

    }

}