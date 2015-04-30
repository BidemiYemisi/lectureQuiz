<?php

namespace Quiz\LectureQuizBundle\Controller;

use Quiz\LectureQuizBundle\Entity\GradedAnswer;
use Quiz\LectureQuizBundle\Entity\OutcomeQuestion;
use Quiz\LectureQuizBundle\Entity\TakersAnswer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;


/**
 * Student Answers controller.
 *
 * @Route("/take")
 *
 */
class TakersController extends Controller
{

    public function getOcQuizId($questionId){
        $ocQuiz = null;
        $quizId= null;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p.id')
            ->from('QuizLectureQuizBundle:OutcomeQuestion', 'q')
            ->innerJoin('q.quiz', 'p')
            ->where('q.id = :questionId')
            ->setParameter('questionId', $questionId);

        $query = $qb->getQuery();
        $ocQuiz = $query->getResult();

        foreach($ocQuiz as $quiz_id ){

            $quizId = $quiz_id['id'];
        }

        return $quizId ;

    }


    /**
     * Displays quiz answer choices
     * @Route("/gd/{id}/", name="take_gd")
     * @Template("QuizLectureQuizBundle:TakersInterface:gdanswer.html.twig")
     */

    public function TakeGradedAction($id)
    {
        //check whether cookie is set before i.e if user as taken quiz before
        //if yes, don't allow user access quiz for one day
        if (isset($_COOKIE['question_id']) && isset($_COOKIE['ip_address'])) {

            if ($_COOKIE['question_id'] == $id) {

                return $this->redirect($this->generateUrl('vote_thanks'));
            }


        }

        //get the answers based on the supplied question_id through the url
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.answer, q.id')
            ->from('QuizLectureQuizBundle:GradedAnswer', 'q')
            ->innerJoin('q.gradedQuestion', 'p')
            ->where('p.id = :questionId')
            ->setParameter('questionId', $id);

        $query = $qb->getQuery();
        $gradedanswer = $query->getResult();

        //create colorFormat and letter arrays and loop through
        //to append them to the result of the query above (gradedanswer)
        $colorFormat = ['#0066FF', '#FF6600', '#33CC33', '#FF5050'];
        $letter = ['A', 'B', 'C', 'D'];

        for ($i = 0; $i < count($gradedanswer); $i++) {
            $gradedanswer[$i]['color'] = $colorFormat[$i];
            $gradedanswer[$i]['letter'] = $letter[$i];
        }


        //grab user ip address
        $ip_address = $_SERVER['REMOTE_ADDR'];

        //calculate duration of cookies
        $number_of_days = 1;
        $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days;

        //create new cookies
        $cookie1 = new Cookie("ip_address", $ip_address, $date_of_expiry, '/', null, false, false);
        $cookie2 = new Cookie("question_id", $id, $date_of_expiry, '/', null, false, false);

        //creates response object
        $response = $this->render("QuizLectureQuizBundle:TakersInterface:gdanswer.html.twig",
            array(
                'answer' => $gradedanswer
            ));
        //set cookies
        $response->headers->setCookie($cookie1);
        $response->headers->setCookie($cookie2);

        return $response;
    }


    /**
     * Displays thank you page
     * @Route("/quiz/thanks/", name="vote_thanks")
     * @Template("QuizLectureQuizBundle:TakersInterface:thanks.html.twig")
     */
    public function thanksAction()
    {
        //renders thank you page
        return $this->render("QuizLectureQuizBundle:TakersInterface:thanks.html.twig");
    }


    /**
     * Processes user's answer choice
     * @Route("/gd/vote/answer", name="process_form")
     * @Template()
     */

    public function submitGradedAction(Request $request)
    {
        //check to see if method id POST
        if ($request->getMethod() == 'POST') {

            //grab the user answer choice
            $vote = $request->get('vote');

            //update database with new value of 1
            $em = $this->getDoctrine()->getManager();

            $qb = $em->createQueryBuilder()
                ->update('QuizLectureQuizBundle:GradedAnswer', 'u')
                ->set('u.vote', 'u.vote + 1')
                ->where('u.id = :uId')
                ->setParameter('uId', $vote);
            $query = $qb->getQuery();
            $answer = $query->getResult();

            //check to see if entity exists in the database
            if (!$answer) {

                throw $this->createNotFoundException('Unable to save answer in database.');
            }


            //redirects to thank you page
            return $this->redirect($this->generateUrl('vote_thanks'));


        }

        //if request is not POST, show answer options
        return $this->render('QuizLectureQuizBundle:TakersInterface:gdanswer.html.twig');

    }


    /**
     * take question Id from url entered manually by user (copied from the pdf )
     * @Route("/oc/{id}/", name= "take_oc")
     * @Template("QuizLectureQuizBundle:TakersInterface:ocanswer.html.twig")
     */
    public function takeOutcomeAction($id)
    {

        //check whether cookie is set before i.e if user as taken quiz before
        //if yes, don't allow user access quiz for one day
        if (isset($_COOKIE['question_id']) && isset($_COOKIE['ip_address'])) {

            if ($_COOKIE['question_id'] == $id) {

                return $this->redirect($this->generateUrl('vote_thanks'));
            }


        }

        //grab user ip address
        $ip_address = $_SERVER['REMOTE_ADDR'];

        //calculate duration of cookies
        $number_of_days = 1;
        $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days;

        $cookie1 = new Cookie("ip_address", $ip_address, $date_of_expiry, '/', null, false, false);
        $cookie2 = new Cookie("question_id", $id, $date_of_expiry, '/', null, false, false);

        $quizId = $this->getOcQuizId($id);


        //creates response object
        $response = $this->render("QuizLectureQuizBundle:TakersInterface:ocanswer.html.twig",
            array('questionId' => $id,
                   'quizId'=>$quizId));


        //set cookies
        $response->headers->setCookie($cookie1);
        $response->headers->setCookie($cookie2);

        return $response;


    }


    /**
     * @Route("/oc/vote/answer", name= "outcome_takers_answer")
     * @Template()
     */
    public function submitOutcomeAction(Request $request)
    {

        if ($request->getMethod() == 'POST') {

            //get user supplied answer
            $answercode = $request->get('answercode');
            $questinId = $request->get('questionId');
            $quizId = $request->get('quizId');


            //query database based on the supplied question_id through url to check whether
            //or not it exists
            $entity = $this->getDoctrine()->getManager();
            $qb = $entity->createQueryBuilder();
            $qb->select('q.id')
                ->from('QuizLectureQuizBundle:OutcomeQuestion', 'q')
                ->where('q.id = :questionId')
                ->setParameter('questionId', $questinId);

            $query = $qb->getQuery();
            $gradedanswer = $query->getResult();


            //check to see that question_id supplied through url has an existing outcome question in the
            //outcome question database
            if (!$gradedanswer) {
                throw $this->createNotFoundException('Unable to find Quiz entity, check that you typed the correct url.');

            } else {

                //create new takersAnswer Object
                $takersAnswer = new TakersAnswer();

                //set the values of takersAnswer Object
                $takersAnswer->setAnswercode($answercode);
                $takersAnswer->setOutcomeQuestion($questinId);
                $takersAnswer->setQuizId($quizId);

                //commit to database
                $em = $this->getDoctrine()->getManager();

                $em->persist($takersAnswer);
                $em->flush();

                //redirect to the thank you page
                return $this->redirect($this->generateUrl('vote_thanks'));
            }
        }

        //if request is not POST, the show the form
        return $this->render('QuizLectureQuizBundle:TakersInterface:ocanswer.html.twig');

    }


    /**
     * Displays quiz answer choices
     * @Route("/tf/{id}/", name="take_tf")
     * @Template("QuizLectureQuizBundle:TakersInterface:tfanswer.html.twig")
     */
    public function TakeTfAction($id)
    {
        //check whether cookie is set before i.e if user as taken quiz before
        //if yes, don't allow user access quiz for one day
        if (isset($_COOKIE['question_id']) && isset($_COOKIE['ip_address'])) {

            if ($_COOKIE['question_id'] == $id) {

                return $this->redirect($this->generateUrl('vote_thanks'));
            }


        }

        //get the answers based on the supplied question_id through the url
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.answer, q.id')
            ->from('QuizLectureQuizBundle:TfAnswer', 'q')
            ->innerJoin('q.tfQuestion', 'p')
            ->where('p.id = :questionId')
            ->setParameter('questionId', $id);

        $query = $qb->getQuery();
        $tfanswer = $query->getResult();

        //create colorFormat and letter arrays and loop through
        //to append them to the result of the query above (tfanswer)
        $colorFormat = ['#0066FF', '#FF6600', '#33CC33', '#FF5050'];
        $letter = ['True', 'False'];

        for ($i = 0; $i < count($tfanswer); $i++) {
            $tfanswer[$i]['color'] = $colorFormat[$i];
            $tfanswer[$i]['letter'] = $letter[$i];
        }


        //grab user ip address
        $ip_address = $_SERVER['REMOTE_ADDR'];

        //calculate duration of cookies
        $number_of_days = 1;
        $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days;

        //create new cookies
        $cookie1 = new Cookie("ip_address", $ip_address, $date_of_expiry, '/', null, false, false);
        $cookie2 = new Cookie("question_id", $id, $date_of_expiry, '/', null, false, false);

        //creates response object
        $response = $this->render("QuizLectureQuizBundle:TakersInterface:tfanswer.html.twig",
            array(
                'answer' => $tfanswer
            ));
        //set cookies
        $response->headers->setCookie($cookie1);
        $response->headers->setCookie($cookie2);

        return $response;
    }


    /**
     * Processes user's answer choice
     * @Route("/tf/vote/answer", name="tf_takers_answers")
     * @Template()
     */

    public function submitTfAction(Request $request)
    {
        //check to see if method id POST
        if ($request->getMethod() == 'POST') {

            //grab the user answer choice
            $vote = $request->get('vote');

            //var_dump($vote);
            //die();

            //update database with new value of 1
            $em = $this->getDoctrine()->getManager();

            $qb = $em->createQueryBuilder()
                ->update('QuizLectureQuizBundle:TfAnswer', 'u')
                ->set('u.vote', 'u.vote + 1')
                ->where('u.id = :uId')
                ->setParameter('uId', $vote);
            $query = $qb->getQuery();
            $answer = $query->getResult();

            //check to see if entity exists in the database
            if (!$answer) {

                throw $this->createNotFoundException('Unable to save answer in database.');
            }


            //redirects to thank you page
            return $this->redirect($this->generateUrl('vote_thanks'));


        }

        //if request is not POST, show answer options
        return $this->render('QuizLectureQuizBundle:TakersInterface:tfanswer.html.twig');

    }


}