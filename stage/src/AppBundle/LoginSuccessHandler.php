<?php


namespace AppBundle;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use UserBundle\Entity\User;

class LoginSuccessHandler  implements AuthenticationSuccessHandlerInterface
{
    protected $router;
    protected $authorizationChecker;

    public function __construct(Router $router, AuthorizationChecker $authorizationChecker) {
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        $user = $token->getUser();
        $response = null;

        if ($this->authorizationChecker->isGranted('ROLE_AGENT')) {
                $boutiquename = $user->getUsername();
            $response = new RedirectResponse($this->router->generate('backend_index',[
                'username' => $boutiquename,
            ]));
        }
        if ($this->authorizationChecker->isGranted('ROLE_CLIENT')) {
           $nomB= $request->request->get('nomBoutique');
            $response = new RedirectResponse($this->router->generate('cltloggedin',[
                'nomB' => $nomB,
                'cltname' => $user->getUsername(),
            ]));
        }



        return $response;
    }
}