<?php
namespace WC\UserBundle\Bnet;

use Pwnraid\Bnet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WCBnet
{
    public function getAllRealms($bnetKey){
        $bNet = new Bnet\ClientFactory($bnetKey);
        $armory = $bNet->warcraft(new Bnet\Region("eu"));
        $realms = $armory->realms()->all();
        $realmsName = array();
        foreach ($realms as $realm) {
            $realmsName[$realm->__get('name')] = $realm->__get('name'); //Texte affiché => Valeur
        }
        $realmsName['Suramar'] = 'Suramar'; //Ajout du serveur manquant
        return $realmsName;
    }

    public function characterIsValid($bnetKey, $name, $realms){
        $bNet = new Bnet\ClientFactory($bnetKey);
        $armory = $bNet->warcraft(new Bnet\Region("eu"));
        $character = $armory->characters()->on($realms)->find($name);
        if($character === null){
            return false;
        }
        else{
            return true;
        }
    }

    public function guildIsValid($bnetKey, $name, $realms){
        $bNet = new Bnet\ClientFactory($bnetKey);
        $armory = $bNet->warcraft(new Bnet\Region("eu"));
        $guild = $armory->guilds()->on($realms)->find($name);
        if($guild === null){
            return false;
        }
        else{
            return true;
        }
    }

    public function getClass($bnetKey, $name, $realms){
        $bNet = new Bnet\ClientFactory($bnetKey);
        $armory = $bNet->warcraft(new Bnet\Region("eu"));
        $character = $armory->characters()->on($realms)->find($name);
        $class = $character['attributes']['class']['attributes']['name'];
        return $class;
    }
}