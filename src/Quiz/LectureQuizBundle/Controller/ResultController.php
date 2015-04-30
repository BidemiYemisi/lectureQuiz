<?php

namespace Quiz\LectureQuizBundle\Controller;

use Quiz\LectureQuizBundle\Entity\Quiz;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Quiz\LectureQuizBundle\Entity\GradedQuestion;
use Quiz\LectureQuizBundle\Entity\GradedAnswer;
use Quiz\LectureQuizBundle\Form\Type\GradedQuestionType;
use Quiz\LectureQuizBundle\Controller\SecurityController;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * OutcomeQuestion controller.
 *
 * @Route("/result")
 */
class ResultController extends Controller
{

    /**
     * @param $id
     *
     */
    public function getCurrentGradedResultAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.answer', 'q.vote')
            ->from('QuizLectureQuizBundle:GradedAnswer', 'q')
            ->innerJoin('q.gradedQuestion', 'p')
            ->where('p.quiz = :quizId')
            ->setParameter('quizId', $id);

        $query = $qb->getQuery();
        $gradedResult = $query->getResult();

        return $gradedResult;

    }


    public function getCurrentTfResultAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.answer', 'q.vote')
            ->from('QuizLectureQuizBundle:TfAnswer', 'q')
            ->innerJoin('q.tfQuestion', 'p')
            ->where('p.quiz = :quizId')
            ->setParameter('quizId', $id);

        $query = $qb->getQuery();
        $tfResult = $query->getResult();

        return $tfResult;

    }


    //use quiz id to get answers to outcome question
    //limit result to 3
    public function getCurrentOutcomeResultAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.answercode')
            ->from('QuizLectureQuizBundle:TakersAnswer', 'q')
            ->where('q.quizId = :quizId')
            //->setMaxResults('1')
            ->setParameter('quizId', $id);

        $query = $qb->getQuery();
        $ocResult = $query->getResult();

        return $ocResult;

    }


    protected function getChangedGradedResultAction($newResult, $oldResuilt)
    {

        // $cur= array();
        $ret = array();
        foreach ($newResult as $vote => $new) {


            if (!isset($oldResuilt[$vote])) {

                $ret[$vote]['answer'] = $new['answer'];

                $ret[$vote]['vote'] = $new['vote'];


            } elseif ($oldResuilt[$vote]['vote'] != $new['vote']) {

                $ret[$vote]['answer'] = $new['answer'];
                $ret[$vote]['vote'] = $new['vote'];
            }
        }

        return $ret;
    }


    protected function getChangedOutcomeResultAction($newResult, $oldResult)
    {

        $newcount = count($newResult);
        $oldcount = count($oldResult);


        if ($oldcount != $newcount) {

            $oldResult = $newResult;

        }


        return $oldResult;

    }


    /**
     *
     * @Route("/graded/{quizid}", name="current_result_graded")
     * @Template()
     */
    public function getGradedValuesAction($quizid)
    {


        $response = new StreamedResponse();

        $response->setCallback(function () use ($quizid) {

            $old_result = array();

            while (true) {

                $em = $this->getDoctrine()->getManager();
                $qb = $em->createQueryBuilder();
                $qb->select('q.answer', 'q.vote')
                    ->from('QuizLectureQuizBundle:GradedAnswer', 'q')
                    ->innerJoin('q.gradedQuestion', 'p')
                    ->where('p.quiz = :quizId')
                    ->setParameter('quizId', $quizid);

                $query = $qb->getQuery();
                $gradedResult = $query->getResult();

                // $gradedResult = $this->getCurrentGradedResultAction($quizid);

                $changed_data = $this->getChangedGradedResultAction($gradedResult, $old_result);


                if (count($changed_data)) {

                    echo 'data: ' . json_encode($changed_data) . "\n\n";

                    ob_end_flush();//strange behaviour won't work
                    // ob_flush();
                    flush();
                }

                //delay execution of script for 3sec
                sleep(3);
                $old_result = $gradedResult;


            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');


        return $response;


    }


    /**
     *
     * @Route("/tf/{qid}", name="current_result_tf")
     * @Template()
     */
    public function getTfValuesAction($qid)
    {
        $response = new StreamedResponse();

        $response->setCallback(function () use ($qid) {

            $oldResult = array();

            while (true) {


                $tfResult = $this->getCurrentTfResultAction($qid);

                $changedData = $this->getChangedGradedResultAction($tfResult, $oldResult);


                if (count($changedData)) {

                    echo 'data: ' . json_encode($changedData) . "\n\n";

                    ob_end_flush();//strange behaviour won't work
                    // ob_flush();
                    flush();
                }

                //delay execution of script for 3sec
               sleep(3);
                $oldResult = $tfResult;


            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');


        return $response;


    }

    /**
     *
     * @Route("/oc/{quiz_id}", name="current_result_oc")
     * @Template()
     */
    public function getOutcomeValuesAction($quiz_id)
    {
        $response = new StreamedResponse();

        $response->setCallback(function () use ($quiz_id) {

            $oldResult = array();
            while (true) {


                $ocResult = $this->getCurrentOutcomeResultAction($quiz_id);
                $changedResult = $this->getChangedOutcomeResultAction($ocResult, $oldResult);

                // var_dump($changedResult);
                // die();

                echo 'data: ' . json_encode($changedResult) . "\n\n";

                ob_end_flush();//strange behaviour won't work
                //ob_flush();
                flush();


                //delay execution of script for 3sec
                sleep(3);
                $oldResult = $ocResult;


            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');


        return $response;


    }

    /**
     *
     * @Route("/gdshow/{id}", name="result_graded")
     * @Template("QuizLectureQuizBundle:Results:result.html.twig")
     */
    public function displayGradedResultAction($id)
    {
        $result = $this->getCurrentGradedResultAction($id);

        return $this->render("QuizLectureQuizBundle:Results:result.html.twig",
            array('result' => $result,
                'id' => $id
            ));
    }


    /**
     *
     * @Route("/tfshow/{id}", name="result_tf")
     * @Template("QuizLectureQuizBundle:Results:tfresult.html.twig")
     */
    public function displayTfResultAction($id)
    {
        $result_tf = $this->getCurrentTfResultAction($id);

        return $this->render("QuizLectureQuizBundle:Results:tfresult.html.twig",
            array('result' => $result_tf,
                'id' => $id
            ));
    }


    /**
     *
     * @Route("/ocshow/{id}", name="result_oc")
     * @Template("QuizLectureQuizBundle:Results:ocresult.html.twig")
     */
    public function displayOutcomeResultAction($id)
    {
        //$result_oc =$this->getOutcomeValuesAction($id);

        $result_oc = $this->getCurrentOutcomeResultAction($id);
        //var_dump($result_oc);
        //die();


        return $this->render("QuizLectureQuizBundle:Results:ocresult.html.twig",
            array('result' => $result_oc,
                'id' => $id
            ));
    }


}