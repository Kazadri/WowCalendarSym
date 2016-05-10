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

      $manager->persist($user);
      $manager->persist($character1);
      $manager->persist($character2);
      $manager->persist($character3);

        // On déclenche l'enregistrement de toutes les catégories
        $manager->flush();

  }

}