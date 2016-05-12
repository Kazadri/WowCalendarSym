<?php

namespace WC\GuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CharactersBlackGuild
 *
 * @ORM\Table(name="characters_black_guild")
 * @ORM\Entity(repositoryClass="WC\GuildBundle\Repository\CharactersBlackGuildRepository")
 */
class CharactersBlackGuild
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
     * @ORM\Column(name="why", type="text", nullable=true)
     */
    private $why;

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
     * Set why
     *
     * @param string $why
     *
     * @return CharactersBlackGuild
     */
    public function setWhy($why)
    {
        $this->why = $why;

        return $this;
    }

    /**
     * Get why
     *
     * @return string
     */
    public function getWhy()
    {
        return $this->why;
    }

    /**
     * Set characters
     *
     * @param \WC\UserBundle\Entity\Characters $characters
     *
     * @return CharactersBlackGuild
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
     * @return CharactersBlackGuild
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
}
