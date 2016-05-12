<?php

namespace WC\GuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use WC\UserBundle\Entity\Characters;

/**
 * CharactersPuGuild
 *
 * @ORM\Table(name="characters_pu_guild")
 * @ORM\Entity(repositoryClass="WC\GuildBundle\Repository\CharactersPuGuildRepository")
 */
class CharactersPuGuild
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
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity="WC\UserBundle\Entity\Characters", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $characters;

    /**
     * @ORM\ManyToOne(targetEntity="WC\GuildBundle\Entity\Guild", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $guilds;

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
     * Set state
     *
     * @param string $state
     *
     * @return CharactersPuGuild
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set characters
     *
     * @param \WC\UserBundle\Entity\Characters $characters
     *
     * @return CharactersPuGuild
     */
    public function setCharacters(\WC\UserBundle\Entity\Characters $characters = null)
    {
        $this->characters = $characters;

        return $this;
    }

    /**
     * Get characters
     *
     * @return \WC\UserBundle\Entity\Characters
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * Set guilds
     *
     * @param \WC\GuildBundle\Entity\Guild $guilds
     *
     * @return CharactersPuGuild
     */
    public function setGuilds(\WC\GuildBundle\Entity\Guild $guilds = null)
    {
        $this->guilds = $guilds;

        return $this;
    }

    /**
     * Get guilds
     *
     * @return \WC\GuildBundle\Entity\Guild
     */
    public function getGuilds()
    {
        return $this->guilds;
    }

    public function __construct(){
        $this->setCharacters(new Characters());
    }
}
