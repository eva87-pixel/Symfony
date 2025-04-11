<?php 
namespace App\Data; 
 
class ListeProduits 
{ 
    static $mesProduits= [ 
        [ "nom"=>"imprimantes", "prix"=>700, "quantite"=>10, "rupture"=>false], 
        [ "nom"=>"cartouches encre", "prix"=>80, "quantite"=>50, "rupture"=>false], 
        [ "nom"=>"ordinateurs", "prix"=>1700, "quantite"=>3, "rupture"=>false], 
        [ "nom"=>"écrans", "prix"=>500, "quantite"=>100, "rupture"=>false], 
        [ "nom"=>"claviers", "prix"=>100, "quantite"=>10, "rupture"=>true], 
        [ "nom"=>"souris", "prix"=>5, "quantite"=>200, "rupture"=>false], 
    ]; 
} 