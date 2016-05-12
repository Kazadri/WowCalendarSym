<?php
namespace WC\User\Bundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WC\UserBundle\Entity\Characters;
use WC\UserBundle\Entity\User;
use WC\UserBundle\WCUserBundle;


class LoadCharacters implements FixtureInterface
{
  // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
  public function load(ObjectManager $manager)
  {
      $user = new User;
      $user->setUsername("Fztheman");
      $user->setPlainPassword("motdepasse");
      $user->setRoles(array('ROLE_ADMIN'));
      $user->setEmail("t.chieux@gmail.com");
      $user->setEnabled(true);

      $user2 = new User;
      $user2->setUsername("TrueBoss");
      $user2->setPlainPassword("Pie1942Pie");
      $user2->setRoles(array('ROLE_ADMIN'));
      $user2->setEmail("wow.calendar.fa@gmail.com");
      $user2->setEnabled(true);

      // Liste des noms de catégorie à ajouter
      $character1 = new Characters();
      $character1->setName("Kazadri");
      $character1->setServer("Les Clairvoyants");
      $character1->setUser($user);
      $character1->setClass("Priest");

      $character2 = new Characters();
      $character2->setName("Biagrin");
      $character2->setServer("Les Clairvoyants");
      $character2->setUser($user);
      $character2->setClass("Warrior");

      $character3 = new Characters();
      $character3->setName("Véronika");
      $character3->setServer("Les Clairvoyants");
      $character3->setUser($user);
      $character3->setClass("Mage");

      $character4 = new Characters();
      $character4->setName("Bainbridge");
      $character4->setServer("Les Clairvoyants");
      $character4->setUser($user2);
      $character4->setClass("Deathknight");

      $character5 = new Characters();
      $character5->setName("Exudra");
      $character5->setServer("Les Clairvoyants");
      $character5->setUser($user2);
      $character5->setClass("Shaman");

      $manager->persist($user);
      $manager->persist($user2);
      $manager->persist($character1);
      $manager->persist($character2);
      $manager->persist($character3);
      $manager->persist($character4);
      $manager->persist($character5);

        // On déclenche l'enregistrement de toutes les catégories
        $manager->flush();

  }

}