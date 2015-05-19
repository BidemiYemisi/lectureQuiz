<?php
namespace Quiz\LectureQuizBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Quiz\LectureQuizBundle\Controller\UserController;
use Quiz\LectureQuizBundle\Entity\User;
use Quiz\LectureQuizBundle\Entity\Quiz;


class SecurityController extends Controller
{
    /**
     * Login Controller
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {


        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $authenticationUtils = $this->get('security.authentication_utils');


            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();


            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();


            return $this->render('QuizLectureQuizBundle:Security:login.html.twig',
                array(
                    // last username entered by the user
                    'last_username' => $lastUsername,
                    'error' => $error,

                )
            );

        }

    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
    }

    /**
     * @Route("/logout", pattern="logout", name="logout")
     */

    public function logoutAction()
    {

    }


}