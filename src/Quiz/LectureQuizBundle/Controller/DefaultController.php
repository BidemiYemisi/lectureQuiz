<?php

namespace Quiz\LectureQuizBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="my_index")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('QuizLectureQuizBundle:Default:index.html.twig');

    }


}