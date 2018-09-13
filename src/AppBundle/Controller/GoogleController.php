<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class GoogleController extends Controller
{
    private $em;
    /**
     * GoogleController constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/google", name="connect_google")
     */
    public function connectAction()
    {
        // will redirect to Facebook!
        return $this->get('oauth2.registry')
            ->getClient('google') // key used in config.yml
            ->redirect();
    }

    /**
     * After going to Google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config.yml
     *
     * @Route("/connect/google/check", name="connect_google_check")
     */
    public function connectCheckAction(Request $request)
    {


        //google_user = new GoogleAuth::getUser();

        $client = $this->get('oauth2.registry')
            ->getClient('google');

        try {
            $google_user = $client->fetchUser();

        } catch (IdentityProviderException $e) {

            $e->getMessage();die;
        }

        

        $lib_user = $this->em->getRepository('AppBundle:User')
                                 ->findOneBy([
                                     'googleId' => $google_user->getId(),
                                     'email' => $google_user->getEmail()
                                 ]);

        if (!$lib_user) {

            $lib_user = new User();
            $lib_user->setEmail($google_user->getEmail());
            $lib_user->setName($google_user->getFirstName());
            $lib_user->setSecondName($google_user->getLastName());
            $lib_user->setGoogleId($google_user->getId());

            $this->em->persist($lib_user);
            $this->em->flush();
        }


        if(isset($lib_user)) {

            $token = new UsernamePasswordToken($lib_user, null, 'user', $lib_user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            $this->get('session')->set('_security_user', serialize($token));

            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/login/", name="user_login")
     */
    public function loginAction()
    {
        return $this->render('security/user_login.html.twig');
    }

    /**
     * @Route("/logout/", name="user_logout")
     */
    public function logoutAction()
    {
        $this->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute('homepage');
    }
}