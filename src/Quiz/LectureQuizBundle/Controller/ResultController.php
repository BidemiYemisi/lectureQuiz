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
     * Gets the current vote count for a graded question
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

    /**
     * Gets the current vote count for a t/f question
     * @param $id
     *
     */
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

    /**
     * use quiz id to get answers to outcome question
     * @param $id
     *
     */
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


    //get the difference in votes when new responses are gotten
    // Code idea gotten from Server-Sent Event Example with Laravel
    //By Zeid Rashwani -http://zrashwani.com
    protected function getChangedGradedResultAction($newResult, $oldResuilt)
    {
        $ret = array(); //create new array

        //loop through current result
        foreach ($newResult as $vote => $new) {

            //set current result to array if old result is not set
            if (!isset($oldResuilt[$vote])) {

                $ret[$vote]['answer'] = $new['answer'];

                $ret[$vote]['vote'] = $new['vote'];

                //if old vote is not equals to new current vote
            } elseif ($oldResuilt[$vote]['vote'] != $new['vote']) {

                //set votes and answer and return
                $ret[$vote]['answer'] = $new['answer'];
                $ret[$vote]['vote'] = $new['vote'];
            }
        }

        return $ret;
    }

    //get new outcome answers
    protected function getChangedOutcomeResultAction($newResult, $oldResult)
    {
        //get length of newResult and oldResult arrays
        $newcount = count($newResult);
        $oldcount = count($oldResult);

        //if the length are not equal
        if ($oldcount != $newcount) {

            //loop through newResult and check if old Result is set
            for ($i = 0; $i < $newcount; $i++) {
                foreach ($newResult as $i => $new) {

                    if (!isset($oldResult[$i]))
                        //set old Result with new Result value and return
                        $oldResult[$i]['answercode'] = $new['answercode'];
                }
                // $oldResult = $newResult;

            }
        }

        return $oldResult;

    }


    /**
     * Get streams of votes for graded quiz
     * Code idea gotten from Server-Sent Event Example with Laravel
     * By Zeid Rashwani -http://zrashwani.com
     * @Route("/graded/{quizid}", name="current_result_graded")
     * @Template()
     */
    public function getGradedValuesAction($quizid)
    {
        //create new streamResponse object
        $response = new StreamedResponse();

        //get new streams results
        $response->setCallback(function () use ($quizid) {

            $old_result = array();

            while (true) {

                //get current votes in database
                $gradedResult = $this->getCurrentGradedResultAction($quizid);

                //get new votes in database
                $changed_data = $this->getChangedGradedResultAction($gradedResult, $old_result);

                //change the data to Json format
                if (count($changed_data)) {

                    echo 'data: ' . json_encode($changed_data) . "\n\n";

                    ob_end_flush();//strange behaviour won't work
                    // ob_flush();
                    flush();
                }

                //delay execution of script for 3sec
                sleep(3);

                //assign current votes to old vote result
                $old_result = $gradedResult;


            }
        });

        //configure header
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        //return stream responses
        return $response;


    }


    /**
     * Get streams of votes for t/f quiz
     * Code idea gotten from Server-Sent Event Example with Laravel
     * By Zeid Rashwani -http://zrashwani.com
     * @Route("/tf/{qid}", name="current_result_tf")
     * @Template()
     */
    public function getTfValuesAction($qid)
    {
        //create new stream response
        $response = new StreamedResponse();


        $response->setCallback(function () use ($qid) {

            $oldResult = array();


            while (true) {

                //get current votes fot t/f quiz
                $tfResult = $this->getCurrentTfResultAction($qid);

                //get new votes
                $changedData = $this->getChangedGradedResultAction($tfResult, $oldResult);

                //format vote data as Json
                if (count($changedData)) {

                    echo 'data: ' . json_encode($changedData) . "\n\n";

                    ob_end_flush();//strange behaviour won't work
                    // ob_flush();
                    flush();
                }

                //delay execution of script for 3sec
                sleep(3);
                //assign current result to old result
                $oldResult = $tfResult;

            }
        });

        //configure the header and return stream response
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');


        return $response;


    }

    /**
     * Get streams of result for outcome quiz
     * Code idea gotten from Server-Sent Event Example with Laravel
     * By Zeid Rashwani -http://zrashwani.com
     * @Route("/oc/{quiz_id}", name="current_result_oc")
     * @Template()
     */
    public function getOutcomeValuesAction($quiz_id)
    {
        //create new stream response object
        $response = new StreamedResponse();

        $response->setCallback(function () use ($quiz_id) {

            $oldResult = array();
            while (true) {

                // get current result and new results
                $ocResult = $this->getCurrentOutcomeResultAction($quiz_id);
                $changedResult = $this->getChangedOutcomeResultAction($ocResult, $oldResult);

                //format data as Json
                echo 'data: ' . json_encode($changedResult) . "\n\n";

                //ob_end_flush();//strange behaviour won't work
                ob_flush();
                flush();


                //delay execution of script for 3sec
                // sleep(3);

                //assign current result to old result
                $oldResult = $ocResult;


            }
        });

        //configure header and return response
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');


        return $response;


    }

    /**
     * Displays the graded Result page
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
     * Displays the T/f Result page
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
     * Displays the outcome Result page
     * @Route("/ocshow/{id}", name="result_oc")
     * @Template("QuizLectureQuizBundle:Results:ocresult.html.twig")
     */
    public function displayOutcomeResultAction($id)
    {
        // $result_oc =$this->getOutcomeValuesAction($id);

        $result_oc = $this->getCurrentOutcomeResultAction($id);
        return $this->render("QuizLectureQuizBundle:Results:ocresult.html.twig",
            array('result' => $result_oc,
                'id' => $id
            ));
    }


}