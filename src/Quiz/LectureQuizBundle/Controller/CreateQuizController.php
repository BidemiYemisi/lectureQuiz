<?php

namespace Quiz\LectureQuizBundle\Controller;

use Quiz\LectureQuizBundle\Entity\LectureNote;
use Quiz\LectureQuizBundle\Entity\OutcomeAnswer;
use Quiz\LectureQuizBundle\Entity\OutcomeQuestion;
use Quiz\LectureQuizBundle\Entity\Photo;
use Quiz\LectureQuizBundle\Entity\Quiz;
use Quiz\LectureQuizBundle\Entity\TfAnswer;
use Quiz\LectureQuizBundle\Entity\TfQuestion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Quiz\LectureQuizBundle\Entity\GradedQuestion;
use Quiz\LectureQuizBundle\Entity\GradedAnswer;
use Quiz\LectureQuizBundle\Controller\SecurityController;
use Symfony\Component\HttpFoundation\Session;

/**
 * CreateQuizController controller.
 *
 * @Route("/create")
 */
class CreateQuizController extends Controller
{
    /**
     *
     * @Route("/", name="create")
     * @Template("QuizLectureQuizBundle:Quiz:create.html.twig")
     */

    public function createAction(){

        if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')){

            return $this->redirect($this->generateUrl('login'));
        }

        $current_user = $this->get('security.context')->getToken()->getUser();

        return $this->render('QuizLectureQuizBundle:Quiz:create.html.twig',

            array(
                'user'=>$current_user,
            ));

    }



    /**
     * Creates a new entity.
     *
     * @Route("/graded", name="createquiz_graded")
     *
     * @Template("QuizLectureQuizBundle:Quiz:graded.html.twig")
     */
    public function createGradedQuizAction(Request $request)
    {
        //if user is not logged in
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            //  $this->get('session')->setFlash('msg', "You must be logged in to create quiz");
            return $this->redirect($this->generateUrl('login'));
        }

        //get object of user that is logged in
        $user = $this->get('security.context')->getToken()->getUser();



        if ($request->getMethod() == 'POST') {
            $quizName = $request->get('quizName');
            $question = $request->get('question');
            $photo = $request->files->get('name');
            $note = $request->files->get('note');
            $answer = $request->get('answer');
            $isCorrect = $request->get('iscorrect');
            $type = $request->get('type');
            $vote = $request->get('vote');
            $page = $request->get('pagenumber');
            $custom_pageNum = $request->get('custom_pageNum');


            //create new graded question object
            $entity = new GradedQuestion();

            //create new photo object
            $photoEntity = new Photo();
            //set photo
            $photoEntity->setFile($photo);

            //create new LectureNote Object
            $lectureNote = new LectureNote();
            //set file
            $lectureNote->setFile($note);

            //relate graded question to it fields and other entities
            $entity->setQuestion($question);
            if($page == 'show'){
                $page = $custom_pageNum;
            }
            $entity->setPagenumber($page);
            $entity->setPhoto($photoEntity);
            $entity->setLectureNote($lectureNote);



            //create new quiz object
            $quiz = new Quiz();
            $quiz->setQuizName($quizName);
            $quiz->setType($type);

            // $session = $this->get("session");
            //$sessionQuiz = $session->get("quiz");

            //relate quiz to logged in user
            if (is_object($user)) {
                $quiz->setUser($user);

            }
            $gradedanswer = new gradedAnswer();
            $gradedanswer->setGradedQuestion($entity);

            $gradedanswer->setAnswer($answer);

            //relates the quiz object to the graded question
            $entity->setQuiz($quiz);


            $em = $this->getDoctrine()->getManager();

            if ($isCorrect == "answer1"){

                foreach($answer as $ans){

                    //create new gradedAnswer object
                    $gradedanswer = new gradedAnswer();
                    $gradedanswer->setGradedQuestion($entity);

                    $gradedanswer->setAnswer($ans);
                    $gradedanswer->setVote(intval($vote));

                    // var_dump($gradedanswer->setVote(intval($vote)));
                    //die();


                    $gradedanswer->setCorrect($answer[0]);

                    $em->persist($gradedanswer);

                }
                $em->persist($photoEntity);
                $em->persist($quiz);
                $em->persist($lectureNote);
                $em->persist($entity);
                $em->flush();


            }elseif($isCorrect == "answer2"){

                foreach($answer as $ans){

                    //create new gradedAnswer object
                    $gradedanswer = new gradedAnswer();
                    $gradedanswer->setGradedQuestion($entity);

                    $gradedanswer->setAnswer($ans);
                    $gradedanswer->setVote(intval($vote));
                    $gradedanswer->setCorrect($answer[1]);

                    $em->persist($gradedanswer);

                }
                $em->persist($photoEntity);
                $em->persist($quiz);
                $em->persist($lectureNote);
                $em->persist($entity);
                $em->flush();

            }elseif($isCorrect == "answer3"){

                foreach($answer as $ans){

                    //create new gradedAnswer object
                    $gradedanswer = new gradedAnswer();
                    $gradedanswer->setGradedQuestion($entity);

                    $gradedanswer->setAnswer($ans);
                    $gradedanswer->setVote(intval($vote));
                    $gradedanswer->setCorrect($answer[2]);

                    $em->persist($gradedanswer);

                }
                $em->persist($photoEntity);
                $em->persist($quiz);
                $em->persist($lectureNote);
                $em->persist($entity);
                $em->flush();

            }
            else{

                foreach($answer as $ans){

                    //create new gradedAnswer object
                    $gradedanswer = new gradedAnswer();
                    $gradedanswer->setGradedQuestion($entity);

                    $gradedanswer->setAnswer($ans);
                    $gradedanswer->setVote(intval($vote));
                    $gradedanswer->setCorrect($answer[3]);

                    $em->persist($gradedanswer);

                }
                $em->persist($photoEntity);
                $em->persist($lectureNote);
                $em->persist($quiz);
                $em->persist($entity);
                $em->flush();
            }


            //after submitting form redirect to profile page
            return $this->redirect($this->generateUrl('profile'));

        }

        //if request is not POST, show form
        return $this->render('QuizLectureQuizBundle:Quiz:graded.html.twig');

    }


    /**
     * Creates a new entity.
     * @Route("/outcome", name="createquiz_outcome")
     *
     * @Template("QuizLectureQuizBundle:Quiz:outcome.html.twig")
     */
    public function createOutcomeQuizAction(Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            //  $this->get('session')->setFlash('msg', 'You must be logged in to create quiz');

            return $this->redirect($this->generateUrl('login'));

        }

        //create user object

        $user = $this->get('security.context')->getToken()->getUser();

        if ($request->getMethod() == 'POST') {

            $quizName = $request->get('quizName');
            $question = $request->get('question');
            $photo = $request->files->get('name');
            $note = $request->files->get('note');
            $answer = $request->get('answer');
            $page = $request->get('pagenumber');
            $type = $request->get('type');
            $custom_pageNum = $request->get('custom_pageNum');

            //create new objects of outcomeQuestion, outcomeAnswer and Quiz
            $outcome = new OutcomeQuestion();
            $quiz = new Quiz();
            $outcomeAnswer = new OutcomeAnswer();
            $photoObj = new Photo();
            $lectureNote= new LectureNote();


            //relate user to quiz
            if (is_object($user)) {
                $quiz->setUser($user);
            }

            //relate outcome question to photo class
            $outcome->setPhoto($photoObj);
            //set photo field
            $photoObj->setFile($photo);

            //relate outcome question to LectureNote class
            $outcome->setLectureNote($lectureNote);
            //set file
            $lectureNote->setFile($note);



            //relate quiz to outcome question
            $outcome->setQuiz($quiz);

            //relates outcomeAnswer to outcomeQuestion
            $outcomeAnswer->setOutcomeQuestion($outcome);

            //set outcome question field based on user input
            $outcome->setQuestion($question);
            $outcomeAnswer->setAnswer($answer);

            if($page == 'show'){
                $page = $custom_pageNum;
            }

            $outcome->setPageNumber($page);




            //set quiz fields based on user input

            $quiz->setQuizName($quizName);
            $quiz->setType($type);



            //save user values to database

            $em = $this->getDoctrine()->getManager();
            $em->persist($photoObj);
            $em->persist($outcome);
            $em->persist($lectureNote);
            $em->persist($quiz);
            $em->persist($outcomeAnswer);
            $em->flush();


            //after submitting form redirect to profile page
            return $this->redirect($this->generateUrl('profile'));

        }

        //if request is not POST, show form
        return $this->render('QuizLectureQuizBundle:Quiz:outcome.html.twig');

    }


    /**
     * Creates a new entity.
     * @Route("/tf", name="createquiz_tf")
     *
     * @Template("QuizLectureQuizBundle:Quiz:tfquestion.html.twig")
     */
    public function createTfQuizAction(Request $request)
    {
        //check if user is logged in
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            // $this->get('session')->setFlash('msg', 'You must be logged in to create quiz');

            //redirects to log in page if not logged in
            return $this->redirect($this->generateUrl('login'));
        }

        //check if form has been submitted
        if ($request->getMethod() == 'POST') {
            $quizName = $request->get('quizName');
            $question = $request->get('question');
            $photo = $request->files->get('name');
            $note = $request->files->get('note');
            $isCorrect = $request->get('iscorrect');
            $type = $request->get('type');
            $vote=$request->get('vote');
            $page = $request->get('pagenumber');
            $custom_pageNum = $request->get('custom_pageNum');


            //create objects
            $tfQuestion = new TfQuestion();
            $photoObj = new Photo();
            $lectureNote = new LectureNote();

            //get the current user
            $user = $this->get('security.context')->getToken()->getUser();

            //create quiz object
            $quiz = new Quiz();

            //check if user is object and relate it to the quiz object
            if (is_object($user)) {

                $quiz->setUser($user);
            }


            //set variables based on user input
            $tfQuestion->setQuestion($question);

            if($page == 'show'){
                $page = $custom_pageNum;
            }

            $tfQuestion->setPagenumber($page);


            //relates TfQuestion to objects
            $tfQuestion->setPhoto($photoObj);
            $tfQuestion->setQuiz($quiz);
            $tfQuestion->setLectureNote($lectureNote);

            //set variables submitted to objects' variables
            $quiz->setQuizName($quizName);
            $quiz->setType($type);

            //set photo
            $photoObj->setFile($photo);

            //set file
            $lectureNote->setFile($note);


            //create entity manager
            $em = $this->getDoctrine()->getEntityManager();

            if ($isCorrect == "true") {

                $answers = ['true','false'];

                foreach($answers  as $ans){

                    //create new tfAnswer object
                    $tfAnswer = new TfAnswer();
                    $tfAnswer->setVote($vote);
                    $tfAnswer->setCorrect('true');
                    //set variable correct to true
                    $tfAnswer->setAnswer($ans);

                    //relates tfAnswer to TfQuestion
                    $tfAnswer->setTfQuestion($tfQuestion);

                    $em->persist($tfAnswer);

                }


                //save entities into database
                $em->persist($photoObj);
                $em->persist($quiz);
                $em->persist($tfQuestion);
                $em->persist($lectureNote);
                $em->flush();

            } else {

                $answers = ['true','false'];

                foreach($answers  as $ans){

                    //create new tfAnswer object
                    $tfAnswer = new TfAnswer();
                    $tfAnswer->setVote($vote);
                    $tfAnswer->setCorrect($isCorrect);
                    //set variable correct to true
                    $tfAnswer->setAnswer($ans);

                    //relates tfAnswer to TfQuestion
                    $tfAnswer->setTfQuestion($tfQuestion);

                    $em->persist($tfAnswer);


                }

                //save entities into database
                $em->persist($quiz);
                $em->persist($photoObj);
                $em->persist($tfQuestion);
                $em->persist($lectureNote);
                $em->flush();

            }

            //after submitting form redirect to profile page
            return $this->redirect($this->generateUrl('profile'));

        }

        //if request is not POST, show form
        return $this->render('QuizLectureQuizBundle:Quiz:tfquestion.html.twig');
    }


}
