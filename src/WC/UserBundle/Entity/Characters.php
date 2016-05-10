<?php

namespace WC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Characters
 *
 * @ORM\Table(name="characters")
 * @ORM\Entity(repositoryClass="WC\UserBundle\Repository\CharactersRepository")
 * @UniqueEntity(
 *     fields={"name", "server"},
 *     message="Ce personnage est déjà référencer"
 * )
 */
class Characters
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="WC\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="WC\GuildBundle\Entity\Guild", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $guild;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="server", type="string", length=255)
     */
    private $server;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=255)
     */
    private $class;

    /**
     * @var string
     *
     * @ORM\Column(name="rank", type="string", length=255, nullable=true)
     */
    private $rank;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Characters
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set server
     *
     * @param string $server
     *
     * @return Characters
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set user
     *
     * @param \WC\UserBundle\Entity\User $user
     *
     * @return Characters
     */
    public function setUser(\WC\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \WC\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }



    /**
     * Set guild
     *
     * @param \WC\GuildBundle\Entity\Guild $guild
     *
     * @return Characters
     */
    public function setGuild(\WC\GuildBundle\Entity\Guild $guild = null)
    {
        $this->guild = $guild;

        return $this;
    }

    /**
     * Get guild
     *
     * @return \WC\GuildBundle\Entity\Guild
     */
    public function getGuild()
    {
        return $this->guild;
    }

    /**
     * Set rank
     *
     * @param string $rank
     *
     * @return Characters
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return string
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set class
     *
     * @param string $class
     *
     * @return Characters
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}
