<?php
namespace App\Service;

class MessageGenerator
{
    public function getHappyMessage()
    {
        $messages = [
            'Bravo vous êtes le meilleur !',
            'Ceci est le meilleur service que j\'ai vu ',
            'Beau travail ! Continuez ! ',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }
}