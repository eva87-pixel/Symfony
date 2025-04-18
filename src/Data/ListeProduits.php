<?php

namespace App\Data;

/**
 * Class ListeProduits
 *
 * Fournit une liste statique de produits sous forme de tableau.
 *
 * Cette classe peut être utilisée pour alimenter des fixtures ou pour des tests.
 *
 * @package App\Data
 */
class ListeProduits
{
    /**
     * Tableau statique contenant la liste des produits.
     *
     * Chaque produit est représenté par un tableau associatif avec les clés :
     * - nom: Le nom du produit
     * - prix: Le prix du produit
     * - quantite: La quantité disponible
     * - rupture: Indique si le produit est en rupture de stock (true/false)
     *
     * @var array
     */
    static $mesProduits = [
        ["nom" => "imprimantes", "prix" => 700, "quantite" => 10, "rupture" => false],
        ["nom" => "cartouches encre", "prix" => 80, "quantite" => 50, "rupture" => false],
        ["nom" => "ordinateurs", "prix" => 1700, "quantite" => 3, "rupture" => false],
        ["nom" => "écrans", "prix" => 500, "quantite" => 100, "rupture" => false],
        ["nom" => "claviers", "prix" => 100, "quantite" => 10, "rupture" => true],
        ["nom" => "souris", "prix" => 5, "quantite" => 200, "rupture" => false],
    ];
}