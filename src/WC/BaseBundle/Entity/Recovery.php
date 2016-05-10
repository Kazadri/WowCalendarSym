<?php

namespace WC\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recovery
 *
 * @ORM\Table(name="recovery")
 * @ORM\Entity(repositoryClass="WC\BaseBundle\Repository\RecoveryRepository")
 */
class Recovery
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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=64)
     */
    private $ip;


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
     * Set username
     *
     * @param string $username
     *
     * @return Recovery
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Recovery
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return Recovery
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    public function __construct()
    {
        $token = "";
        $chaine = "abcdefghijklmnpqrstuvwxyz";
        srand((double)microtime()*1000000);
        for($i=0; $i<20; $i++) {
            $token .= $chaine[rand()%strlen($chaine)];
        }
        $this->token = $token;
        $this->ip = $this->constructAddrIp();

    }

    function constructAddrIp() {
        // IP si internet partagé
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
        }
        // IP derrière un proxy non anonyme
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        // IP normale
        else {
        return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
        }
    }
}

