<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

/**
 * Class MessageGenerator
 *
 * Ce service génère un message de motivation aléatoire parmi un ensemble de messages pré-définis.
 * Il enregistre également le message sélectionné dans le logger.
 *
 * @package App\Service
 */
class MessageGenerator
{
    /**
     * Le service de logging permettant d'enregistrer les messages générés.
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructeur.
     *
     * Injecte l'instance de LoggerInterface.
     *
     * @param LoggerInterface $logger Le service de logging.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Génère et retourne un message aléatoire.
     *
     * Sélectionne un message aléatoirement parmi une liste prédéfinie,
     * l'enregistre dans le logger et le retourne.
     *
     * @return string Le message généré.
     */
    public function getHappyMessage(): string
    {
        $messages = [
            'Bravo vous êtes le meilleur !',
            'Ceci est le meilleur service que j\'ai vu ',
            'Beau travail ! Continuez ! ',
        ];

        $index = array_rand($messages);
        $this->logger->info($messages[$index]);

        return $messages[$index];
    }
}