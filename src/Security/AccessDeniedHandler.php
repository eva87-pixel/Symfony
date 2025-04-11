<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class AccessDeniedHandler extends AbstractController implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException
$accessDeniedException) : Response
    {

        $session=$request->getSession();
        $session->getFlashBag()->add('message','Vous n\'avez pas les
droits suffisants pour accéder à cette page');
        $session->set('statut','danger');

        return $this->redirectToRoute('app_login');
    }
}