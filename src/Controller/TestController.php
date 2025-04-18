<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MessageGenerator;

/**
 * Class TestController
 *
 * Ce contrôleur gère plusieurs actions de test :
 * - La méthode message() qui utilise le service MessageGenerator pour renvoyer un message.
 * - La méthode index() qui affiche une vue de test.
 * - La méthode hello() qui crée des graphiques Chart.js (de type line et bar) et les transmet à une vue.
 *
 * @package App\Controller
 */
class TestController extends AbstractController
{
    /**
     * Affiche un message généré par le service MessageGenerator.
     *
     * @param MessageGenerator $message Le service qui génère un message
     *
     * @return Response Retourne une réponse contenant le message généré
     */
    #[Route("/message")]
    public function message(MessageGenerator $message): Response
    {
        return new Response($message->getHappyMessage());
    }

    /**
     * Affiche la vue de test.
     *
     * @param Request $request La requête HTTP
     *
     * @return Response Rend la vue 'test/index.html.twig'
     */
    #[Route('/test', name: 'app_test', methods: ['GET', 'HEAD'])]
    public function index(Request $request): Response
    {
        return $this->render('test/index.html.twig');
    }

    /**
     * Affiche une vue avec deux graphiques Chart.js (line et bar) et d'autres données.
     *
     * Cette méthode crée et configure un graphique linéaire et un histogramme en utilisant le ChartBuilderInterface.
     * Elle transmet également plusieurs autres données (nom, prenom, âge, un message HTML et un tableau associatif) au template.
     *
     * @param ChartBuilderInterface $chartBuilder Le service de construction de graphiques Chart.js.
     * @param Request $request La requête HTTP.
     * @param int $age L'âge passé dans l'URL.
     * @param mixed $nom Le nom passé dans l'URL (doit satisfaire la regex définie).
     * @param string $prenom Le prénom passé dans l'URL (optionnel, valeur par défaut vide).
     *
     * @return Response Retourne la réponse qui affiche le template 'test/hello.html.twig' avec les graphiques et autres données.
     */
    #[Route('/hello/{age}/{nom}/{prenom}', name: 'hello', requirements: ["nom" => "[a-z]{2,50}"])]
    public function hello(
        ChartBuilderInterface $chartBuilder,
        Request $request,
        int $age,
        $nom,
        $prenom = ''
    ): Response {
        // Créer un graphique de type "line"
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        // Définir les données du graphique linéaire
        $chart->setData([
            'labels'   => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label'           => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor'     => 'rgb(255, 99, 132)',
                    'data'            => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        // Définir les options du graphique linéaire
        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        // Créer un graphique de type "bar" (histogramme)
        $histo = $chartBuilder->createChart(Chart::TYPE_BAR);
        $histo->setData([
            'labels'   => ['Rouge', 'Vert', 'Bleu', 'Jaune'],
            'datasets' => [
                [
                    'label'           => 'Mon histogramme',
                    'data'            => [10, 20, 50, 30],
                    'backgroundColor' => ['#FF0000', '#00FF00', '#0000FF', '#FFFF00'],
                ],
            ],
        ]);
        $histo->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        // Renvoyer la réponse avec les graphiques et d'autres données transmises au template 'test/hello.html.twig'
        return $this->render('test/hello.html.twig', [
            'chart'       => $chart,
            'histo'       => $histo,
            'nom'         => $nom,
            'prenom'      => $prenom,
            'age'         => $age,
            'messageHtml' => '<h3>je vais tester raw</h3>',
            'monTableau'  => [
                'profession' => 'formateur',
                'sexe'       => 'M',
                'specialité' => 'Symfony',
            ],
        ]);
    }
}