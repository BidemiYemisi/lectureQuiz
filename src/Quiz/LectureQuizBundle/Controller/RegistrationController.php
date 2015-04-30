<?php

namespace Quiz\LectureQuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Quiz\LectureQuizBundle\Form\Type\RegistrationType;
use Quiz\LectureQuizBundle\Form\Model\Registration;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 *  Registration controller.
 *
 * @Route("/register")
 */
class RegistrationController extends Controller
{

    /**
     * Registration controller.
     *
     * @Route("/", name="account_register")
     * @Template()
     */
    public function registerAction()
    {
        $registration = new Registration();
        $form = $this->createForm(new RegistrationType(), $registration, array(
            'action' => $this->generateUrl('account_create'),
        ));

        return $this->render(
            'QuizLectureQuizBundle:Account:register.html.twig',
            array('form' => $form->createView())
        );
    }


    /**
     * Registration controller.
     *
     * @Route("/create", name="account_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RegistrationType(), new Registration());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $registration = $form->getData();

            $em->persist($registration->getUser());
            $em->flush();

            return $this->redirect($this->generateUrl('my_index'));
        }

        return $this->render(
            'QuizLectureQuizBundle:Account:register.html.twig',
            array('form' => $form->createView())
        );
    }
}