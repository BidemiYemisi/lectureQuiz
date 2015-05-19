<?php
namespace Quiz\LectureQuizBundle\Controller;


use fpdi\FPDI;
use Quiz\LectureQuizBundle\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Quiz\LectureQuizBundle\Entity\User;
use fpdf\FPDF;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Quiz\LectureQuizBundle\Image\PDF;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Validator\Constraints\Null;


/**
 * OutcomeQuestion controller.
 *
 * @Route("/profile")
 */
class profileController extends Controller
{

    //get some values from both graded question and answer tables
    private function gradedAnswerQueryAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.answer', 'p.question', 'q.correct', 'p.id', 'p.pagenumber')
            ->from('QuizLectureQuizBundle:GradedAnswer', 'q')
            ->innerJoin('q.gradedQuestion', 'p')
            ->where('p.quiz = :quizId')
            ->setParameter('quizId', $id);

        $query = $qb->getQuery();
        $gradedquestion = $query->getResult();

        return $gradedquestion;

    }

    //get some values from both true/false question and answer tables
    private function tfAnswerQueryAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.answer', 'p.question', 'q.correct', 'p.id', 'p.pagenumber')
            ->from('QuizLectureQuizBundle:TfAnswer', 'q')
            ->innerJoin('q.tfQuestion', 'p')
            ->where('p.quiz = :quizId')
            ->setParameter('quizId', $id);

        $query = $qb->getQuery();
        $tfquestion = $query->getResult();

        return $tfquestion;

    }

    //get some values from both outcome question and answer tables
    private function outcomeAnswerQueryAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.answer', 'p.question', 'p.id', 'p.pagenumber')
            ->from('QuizLectureQuizBundle:OutcomeAnswer', 'q')
            ->innerJoin('q.outcomeQuestion', 'p')
            ->where('p.quiz = :quizId')
            ->setParameter('quizId', $id);

        $query = $qb->getQuery();
        $outcomequestion = $query->getResult();

        return $outcomequestion;

    }

    //get id of answer choices for graded answers
    private function getGradedAnswerOptionAction($questionId)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.id')
            ->from('QuizLectureQuizBundle:GradedAnswer', 'q')
            ->where('q.gradedQuestion = :questionId')
            ->setParameter('questionId', $questionId);

        $query = $qb->getQuery();
        $gradedanswer = $query->getResult();

        return $gradedanswer;

    }

    //get id of answer choices for true/false answers
    private function getTfAnswerOptionAction($questionId)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.id')
            ->from('QuizLectureQuizBundle:TfAnswer', 'q')
            ->where('q.tfQuestion = :questionId')
            ->setParameter('questionId', $questionId);

        $query = $qb->getQuery();
        $tfanswer = $query->getResult();

        return $tfanswer;

    }


//get note id of outcome quiz
    private function getOutcomeNoteAction($id)
    {
        $noteOc_id = null;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p.id')
            ->from('QuizLectureQuizBundle:OutcomeQuestion', 'q')
            ->innerJoin('q.lectureNote', 'p')
            ->where('q.id = :questionId')
            ->setParameter('questionId', $id);

        $query = $qb->getQuery();
        $ocNote = $query->getResult();

        foreach ($ocNote as $oc) {

            $noteOc_id = $oc['id'];
        }

        return $noteOc_id;
    }

    //get note id of graded quiz
    private function getGradedNoteAction($id)
    {
        $noteGd_id = null;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p.id')
            ->from('QuizLectureQuizBundle:GradedQuestion', 'q')
            ->innerJoin('q.lectureNote', 'p')
            ->where('q.id = :questionId')
            ->setParameter('questionId', $id);

        $query = $qb->getQuery();
        $gdNote = $query->getResult();

        foreach ($gdNote as $gd) {

            $noteGd_id = $gd['id'];
        }

        return $noteGd_id;
    }

    //get note id of true/false quiz
    private function getTfNoteAction($id)
    {
        $noteTf_id = null;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p.id')
            ->from('QuizLectureQuizBundle:TfQuestion', 'q')
            ->innerJoin('q.lectureNote', 'p')
            ->where('q.id = :questionId')
            ->setParameter('questionId', $id);

        $query = $qb->getQuery();
        $tfNote = $query->getResult();

        foreach ($tfNote as $tf) {

            $noteTf_id = $tf['id'];
        }

        return $noteTf_id;
    }

    //get picture id of graded quiz
    private function getGradedPhotoAction($id)
    {
        $photoGd_id = null;

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p.id')
            ->from('QuizLectureQuizBundle:GradedQuestion', 'q')
            ->innerJoin('q.photo', 'p')
            // ->innerJoin('q.lectureNote', 'a')
            ->where('q.id = :questionId')
            ->setParameter('questionId', $id);

        $query = $qb->getQuery();
        $gradedquestion = $query->getResult();

        foreach ($gradedquestion as $g) {

            $photoGd_id = $g['id'];
        }


        return $photoGd_id;
    }

//get picture id of outcome quiz
    private function getOutcomePhotoAction($id)
    {
        $photoOc_id = null;

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p.id')
            ->from('QuizLectureQuizBundle:OutcomeQuestion', 'q')
            ->innerJoin('q.photo', 'p')
            // ->innerJoin('q.lectureNote', 'a')
            ->where('q.id = :questionId')
            ->setParameter('questionId', $id);

        $query = $qb->getQuery();
        $ocquestion = $query->getResult();

        foreach ($ocquestion as $oc) {

            $photoOc_id = $oc['id'];
        }


        return $photoOc_id;
    }

    //get picture id of true/false quiz
    private function getTfPhotoAction($id)
    {
        $photoTf_id = null;

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p.id')
            ->from('QuizLectureQuizBundle:TfQuestion', 'q')
            ->innerJoin('q.photo', 'p')
            // ->innerJoin('q.lectureNote', 'a')
            ->where('q.id = :questionId')
            ->setParameter('questionId', $id);

        $query = $qb->getQuery();
        $tfquestion = $query->getResult();

        foreach ($tfquestion as $tf) {

            $photoTf_id = $tf['id'];
        }


        return $photoTf_id;
    }

//get all graded questions based on quizzes created by user
    private function queryGradedAction($userId)
    {
        $quizGd = null;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.quizName', 'p.question', 'q.id', 'q.dateCreated')
            ->from('QuizLectureQuizBundle:GradedQuestion', 'p')
            ->innerJoin('p.quiz', 'q')
            ->where('q.user = :user')
            ->andWhere('q.type = :type')
            ->orderBy('q.id', 'DESC')
            ->setParameter('user', $userId)
            ->setParameter('type', 'gd');
        $query = $qb->getQuery();
        $quizGd = $query->getResult();


        return $quizGd;

    }

//get all outcome questions based on quizzes created by user
    private function queryOutcomeAction($userId)
    {
        $quizOc = null;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.quizName', 'q.id', 'p.question', 'q.dateCreated')
            ->from('QuizLectureQuizBundle:OutcomeQuestion', 'p')
            ->innerJoin('p.quiz', 'q')
            ->where('q.user = :user')
            ->andWhere('q.type = :type')
            ->orderBy('q.id', 'DESC')
            ->setParameter('user', $userId)
            ->setParameter('type', 'oc');
        $query = $qb->getQuery();
        $quizOc = $query->getResult();


        return $quizOc;
    }

    //get all t/f questions  based on quizzes created by user
    private function queryTfAction($userId)
    {
        $quizTf = null;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.quizName', 'q.id', 'p.question', 'q.dateCreated')
            ->from('QuizLectureQuizBundle:TfQuestion', 'p')
            ->innerJoin('p.quiz', 'q')
            ->where('q.user = :user')
            ->andWhere('q.type = :type')
            ->orderBy('q.id', 'DESC')
            ->setParameter('user', $userId)
            ->setParameter('type', 'tf');
        $query = $qb->getQuery();
        $quizTf = $query->getResult();


        return $quizTf;
    }


    //statistic function for Graded Quiz
    private function statGdAction($userId)
    {
        //get answer and votes based on logged in user
        $total = $correctVote = $result = null;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u.vote', 'u.correct', 'u.answer')
            ->from('QuizLectureQuizBundle:GradedAnswer', 'u')
            ->innerJoin('u.gradedQuestion', 'p')
            ->innerJoin('p.quiz', 'q')
            ->where('q.user = :user')
            ->andWhere('q.type = :type')
            ->setParameter('user', $userId)
            ->setParameter('type', 'gd');
        $q = $qb->getQuery();
        $query = $q->getResult();

        foreach ($query as $key => $qy) {
            $total += $qy['vote'];


            if ($qy['answer'] == $qy['correct']) {
                $correctVote += $qy['vote'];
            }
        }
        //get correct votes and incorrect votes
        $result = $total - $correctVote;

        //if no vote exist, then assign 1 to result for pie-chart
        if ($result == 0) {
            $result = 1;
            $correctVote = 0;
        }

        //return array of result
        return array($result, $correctVote);
    }


    //statistic function for t/f Quiz
    private function statTfAction($userId){
        //get answer and votes based on logged in user
        $total = $correctVote = $result = null;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u.vote', 'u.correct', 'u.answer')
            ->from('QuizLectureQuizBundle:TfAnswer', 'u')
            ->innerJoin('u.tfQuestion', 'p')
            ->innerJoin('p.quiz', 'q')
            ->where('q.user = :user')
            ->andWhere('q.type = :type')
            ->setParameter('user', $userId)
            ->setParameter('type', 'tf');
        $q = $qb->getQuery();
        $query = $q->getResult();

       //loop through query and get the total votes of all
       // answers and the total votes of correct answer
        foreach ($query as $key => $qy) {
            $total += $qy['vote'];

            if ($qy['answer'] == $qy['correct']) {
                $correctVote += $qy['vote'];
            }
        }
        //get correct votes and incorrect votes
        $result = $total - $correctVote;
        //if no vote exist, then assign 1 to result for pie-chart
        if ($result == 0) {
            $result = 1;
            $correctVote = 0;
        }
        //returns array of result
        return array($result, $correctVote);
    }


    //calculate month interval when user created quiz
    private function getMonth($query)
    {

        $count1 = $count2 = $count3 = null;
        foreach ($query as $q) {
            $month = $q['dateCreated'];

            $mon = $month->format('n');

            if ($mon <= 4) {
                $Apr[] = $mon;
                $count1 = count($Apr);
            } elseif ($mon > 4 || $mon <= 8) {

                $Aug[] = $mon;
                $count2 = count($Aug);
            } else {

                $Dec[] = $mon;
                $count3 = count($Dec);
            }
        }
        $totalCount = array($count1, $count2, $count3);
        return $totalCount;
    }


    /**
     * User data for User details page
     * Gets user that is logged in
     */
    private function getUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('QuizLectureQuizBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User does not exist');
        }
        return $user;
    }


    /**
     * Displays profile user details and quizzes created by user
     * @Route("/", name="profile")
     * @Template("QuizLectureQuizBundle:Profile:profile.html.twig")
     */

    public function profileAction()
    {

        //check to see if user is logged in and get the user
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            //  $this->get('session')->setFlash('msg', "You must be logged in to create quiz");
            return $this->redirect($this->generateUrl('login'));
        }

        //get object of user that is logged in
        $user = $this->get('security.context')->getToken()->getUser();

        $userId = $user->getId();

        //get quiz details for editing


        //get user details by calling the getUserAction() method
        $userData = $this->getUserAction($userId);

        //get quiz details for grid quiz display on profilepage
        $graded = $this->queryGradedAction($userId);
        $outcome = $this->queryOutcomeAction($userId);
        $tf = $this->queryTfAction($userId);


        //get stats of total against correct answers
        $correctStat1 = $this->statGdAction($userId);
        $correctStat2 = $this->statTfAction($userId);


        //get total quiz created by user
        $count = count($graded) + count($outcome) + count($tf);

        //get months of created quizzes for Barchart
        $quiz_tmp1 = $this->getMonth($graded);
        $quiz_tmp2 = $this->getMonth($outcome);
        $quiz_tmp3 = $this->getMonth($tf);

        $apr = $quiz_tmp1[0] + $quiz_tmp2[0] + $quiz_tmp3[0];
        $aug = $quiz_tmp1[1] + $quiz_tmp2[1] + $quiz_tmp3[1];
        $dec = $quiz_tmp1[2] + $quiz_tmp2[2] + $quiz_tmp3[2];


        return $this->render('QuizLectureQuizBundle:Profile:profile.html.twig',
            array(
                'entity' => $userData,
                'quizGd' => $graded,
                'quizOc' => $outcome,
                'quizTf' => $tf,
                'total_quiz' => $count,
                'apr' => $apr,
                'aug' => $aug,
                'dec' => $dec,
                'gdStat' => $correctStat1,
                'tfStat' => $correctStat2
            ));
    }


    /**
     * Renders graded quiz as pdf in lecture note
     * @Route("/graded/{id}", name="profile_graded")
     */
    public function displayGradedAction($id)
    {
        $questn_id = null;
        $question = null;
        $answer = null;
        $page_number = null;

        $gradedquestion = $this->gradedAnswerQueryAction($id);

        $pdf = new PDF();

        $pdf->AddPage();
        $pdf->SetFont('Times', '', 30);


        if ($gradedquestion != null) {

            //loop through query result to get result
            //since each answer is made up of question, answer and correct answer
            //assign all questions and answers to arrays respectively
            foreach ($gradedquestion as $graded) {

                $question = $graded['question'];
                $answer[] = $graded['answer'];
                $questn_id = $graded['id'];
                $page_number = $graded['pagenumber'];

            }

            //get array answer length
            $lenght = count($answer);

            //declare array of alphabets
            $alphabetd = array("(A) ", "(B) ", "(C) ", "(D) ");

            //create pdf object
            $pdf = new PDF();

            //get the note id if it exists
            $file_id = $this->getGradedNoteAction($questn_id);

            if ($file_id == null) {
                $pdf->AddPage();
                $pdf->getQuizPage($question);
                $photo_id = $this->getGradedPhotoAction($questn_id);
                $pdf->getTfImage($photo_id);
                $pdf->getGradedOption($lenght, $alphabetd, $answer);
                $pdf->GdUrl($questn_id, -38);
                $pdf->GdUrl($questn_id, 30);

            } else {

                // set the source file
                $pdfdoc = $pdf->getNote($file_id);

                if ($pdfdoc != null) {

                    // get the page count
                    $pageCount = $pdf->setSourceFile($pdfdoc);

                    if ($page_number == 0) {

                        $page_number = $pageCount;

                    }

                    // iterate through all pages
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        // import a page
                        $templateId = $pdf->importPage($pageNo);
                        // get the size of the imported page
                        $size = $pdf->getTemplateSize($templateId);

                        // create a page (landscape or portrait depending on the imported page size)
                        if ($size['w'] > $size['h']) {
                            $pdf->AddPage('L', 'A4');
                        } else {
                            $pdf->AddPage('P', 'A4');
                        }


                        //insert quiz anywhere apart from last page

                        if ($pageNo == $page_number && $page_number < $pageCount) {
                            $pdf->getQuizPage($question);
                            $photo_id = $this->getGradedPhotoAction($questn_id);
                            $pdf->getTfImage($photo_id);
                            $pdf->getGradedOption($lenght, $alphabetd, $answer);
                            $pdf->GdUrl($questn_id, -38);
                            $pdf->GdUrl($questn_id, 30);
                            $pdf->AddPage();
                        }
                        // use the imported page
                        $pdf->useTemplate($templateId);


                        //insert quiz on last page
                        if ($pageNo == $page_number && $page_number == $pageCount) {
                            $pdf->AddPage();
                            $pdf->getQuizPage($question);
                            $photo_id = $this->getGradedPhotoAction($questn_id);
                            $pdf->getTfImage($photo_id);
                            //loop through based on answer option lenght and append the correcponding alphabets
                            $pdf->getGradedOption($lenght, $alphabetd, $answer);
                            $pdf->GdUrl($questn_id, -38);
                            $pdf->GdUrl($questn_id, 30);

                        }
                    }
                } else {
                    $pdf->AddPage();
                    $pdf->getQuizPage($question);
                    $photo_id = $this->getGradedPhotoAction($questn_id);
                    $pdf->getTfImage($photo_id);
                    //loop through based on answer option lenght and append the correcponding alphabets
                    $pdf->getGradedOption($lenght, $alphabetd, $answer);
                    $pdf->GdUrl($questn_id, -38);
                    $pdf->GdUrl($questn_id, 30);
                }
            }
            //ob_end_clean();

            // $newpdf = new Response($pdf->Output('%kernel.root_dir%/../pdf2/u.pdf', 'F'), 200, array(
            // 'Content-Type' => 'application/pdf'));

            return new Response($pdf->Output(), 200, array(
                'Content-Type' => 'application/pdf'));
        } else {

            return $this->render('QuizLectureQuizBundle:Profile:noquiz.html.twig');
        }

    }


    /**
     * Renders outcome quiz as pdf in lecture note
     * @Route("/outcome/{id}", name="profile_outcome")
     */
    public function displayOutcomeAction($id)
    {
        $question = null;
        $question_id = null;
        $page_number = null;

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p.question', 'p.id', 'p.pagenumber')
            ->from('QuizLectureQuizBundle:OutcomeQuestion', 'p')
            ->where('p.quiz = :quizId')
            ->setParameter('quizId', $id);

        $query = $qb->getQuery();
        $outcomequestion = $query->getResult();


        if ($outcomequestion != null) {

            foreach ($outcomequestion as $outcome) {
                $question = $outcome ['question'];
                $question_id = $outcome['id'];
                $page_number = $outcome['pagenumber'];

            }

            //create pdf object
            $pdf = new PDF();

            //get the note id if it exists
            $file_id = $this->getOutcomeNoteAction($question_id);

            if ($file_id == null) {
                $pdf->AddPage();
                $pdf->getQuizPage($question);
                $photo_id = $this->getOutcomePhotoAction($question_id);
                $pdf->getGdImage($photo_id);
                $pdf->OcUrl($question_id, -38);
                $pdf->OcUrl($question_id, 30);

            } else {

                // set the source file
                $pdfdoc = $pdf->getNote($file_id);

                if ($pdfdoc != null) {

                    // get the page count
                    $pageCount = $pdf->setSourceFile($pdfdoc);

                    if ($page_number == 0) {

                        $page_number = $pageCount;

                    }

                    // iterate through all pages
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        // import a page
                        $templateId = $pdf->importPage($pageNo);
                        // get the size of the imported page
                        $size = $pdf->getTemplateSize($templateId);

                        // create a page (landscape or portrait depending on the imported page size)
                        if ($size['w'] > $size['h']) {
                            $pdf->AddPage('L', 'A4');
                        } else {
                            $pdf->AddPage('P', 'A4');
                        }


                        //insert quiz anywhere apart from last page

                        if ($pageNo == $page_number && $page_number < $pageCount) {
                            $pdf->getQuizPage($question);
                            $photo_id = $this->getOutcomePhotoAction($question_id);
                            $pdf->getGdImage($photo_id);
                            $pdf->OcUrl($question_id, -38);
                            $pdf->OcUrl($question_id, 30);
                            $pdf->AddPage();
                        }
                        // use the imported page
                        $pdf->useTemplate($templateId);


                        //insert quiz on last page
                        if ($pageNo == $page_number && $page_number == $pageCount) {
                            $pdf->AddPage();
                            $pdf->getQuizPage($question);
                            $photo_id = $this->getOutcomePhotoAction($question_id);
                            $pdf->getGdImage($photo_id);
                            $pdf->OcUrl($question_id, -38);
                            $pdf->OcUrl($question_id, 30);

                        }
                    }
                } else {
                    $pdf->AddPage();
                    $pdf->getQuizPage($question);
                    $photo_id = $this->getOutcomePhotoAction($question_id);
                    $pdf->getGdImage($photo_id);
                    $pdf->OcUrl($question_id, -38);
                    $pdf->OcUrl($question_id, 30);

                }

            }

            //$pdf->AliasNbPages();
            return new Response($pdf->Output(), 200, array(
                'Content-Type' => 'application/pdf'));

        } else {

            return $this->render('QuizLectureQuizBundle:Profile:noquiz.html.twig');
        }
    }

    /**
     * Renders true/false quiz as pdf in lecture note
     * @Route("/tf/{id}", name="profile_tf")
     */
    public function displayTfAction($id)
    {
        $question = null;
        $q_id = null;
        $page_number = null;

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p.question', 'p.id', 'p.pagenumber')
            ->from('QuizLectureQuizBundle:TfQuestion', 'p')
            ->where('p.quiz = :quizId')
            ->setParameter('quizId', $id);

        $query = $qb->getQuery();
        $tfquestion = $query->getResult();

        if ($tfquestion != null) {

            foreach ($tfquestion as $tf) {
                $question = $tf ['question'];
                $q_id = $tf['id'];
                $page_number = $tf['pagenumber'];
            }

            // $true = '(A) ' . 'True';
            //$false = '(B) ' . 'False';

            //create pdf object
            $pdf = new PDF();


            //get the note id if it exists
            $file_id = $this->getTfNoteAction($q_id);

            if ($file_id == null) {
                $pdf->AddPage();
                $pdf->getQuizPage($question);
                $photo_id = $this->getTfPhotoAction($q_id);
                $pdf->getTfImage($photo_id);
                $pdf->getTfOption();
                $pdf->TfUrl($q_id, -38);
                $pdf->TfUrl($q_id, 30);
            } else {

                // set the source file
                $pdfdoc = $pdf->getNote($file_id);

                if ($pdfdoc != null) {

                    // get the page count
                    $pageCount = $pdf->setSourceFile($pdfdoc);

                    if ($page_number == 0) {

                        $page_number = $pageCount;

                    }

                    // iterate through all pages
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        // import a page
                        $templateId = $pdf->importPage($pageNo);
                        // get the size of the imported page
                        $size = $pdf->getTemplateSize($templateId);

                        // create a page (landscape or portrait depending on the imported page size)
                        if ($size['w'] > $size['h']) {
                            $pdf->AddPage('L', 'A4');
                        } else {
                            $pdf->AddPage('P', 'A4');
                        }

                        //insert quiz anywhere apart from last page
                        if ($pageNo == $page_number && $page_number < $pageCount) {
                            $pdf->getQuizPage($question);
                            $photo_id = $this->getTfPhotoAction($q_id);
                            $pdf->getTfImage($photo_id);
                            $pdf->getTfOption();
                            $pdf->TfUrl($q_id, -38);
                            $pdf->TfUrl($q_id, 30);
                            $pdf->AddPage();
                        }
                        // use the imported page
                        $pdf->useTemplate($templateId);

                        //insert quiz on last page
                        if ($pageNo == $page_number && $page_number == $pageCount) {
                            $pdf->AddPage();
                            $pdf->getQuizPage($question);
                            $photo_id = $this->getTfPhotoAction($q_id);
                            $pdf->getTfImage($photo_id);
                            $pdf->getTfOption();
                            $pdf->TfUrl($q_id, -38);
                            $pdf->TfUrl($q_id, 30);
                        }
                    }
                } else {
                    $pdf->AddPage();
                    $pdf->getQuizPage($question);
                    $photo_id = $this->getTfPhotoAction($q_id);
                    $pdf->getTfImage($photo_id);
                    $pdf->getTfOption();
                    $pdf->TfUrl($q_id, -38);
                    $pdf->TfUrl($q_id, 30);

                }
            }

            return new Response($pdf->Output(), 200, array(
                'Content-Type' => 'application/pdf'));
        } else {

            return $this->render('QuizLectureQuizBundle:Profile:noquiz.html.twig');
        }
    }


    /**
     * Deletes a Quiz Entity and all its related to.
     *
     * @Route("/{id}", name="quiz_delete")
     *
     */
    public function deleteQuiz($id)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->delete('QuizLectureQuizBundle:Quiz', 'q')
            ->where('q.id = :quizId')
            ->setParameter('quizId', $id);
        $query = $qb->getQuery();
        $quizEntity = $query->getResult();

        if (!$quizEntity) {
            throw $this->createNotFoundException('Quiz entity does not exist');
        }

        return $this->redirect($this->generateUrl('profile'));
    }


    /**
     * Edits graded question
     * @Route("/gdEdit/{id}" , name="graded_edit")
     * Template("QuizLectureQuizBundle:EditQuiz:gdEdit.html.twig")
     */

    public function showGdAction($id)
    {

        $gdQuery = $this->gradedAnswerQueryAction($id);


        return $this->render("QuizLectureQuizBundle:EditQuiz:gdEdit.html.twig",
            array('gdQuery' => $gdQuery));
    }


    /**
     * Edits graded question
     * @Route("/gdEdit/update/new" , name="graded_update")
     *
     */
    public function editGdAction(Request $request)
    {

        //check if user has clicked the submit button
        //if yes get user's input
        if ($request->getMethod() == 'POST') {
            $question = $request->get('question');
            $answer = $request->get('answer');
            $pageNumber = $request->get('pagenumber');
            $question_id = $request->get('question_id');
            $isCorrect = $request->get('iscorrect');

            //get pagenumber
            if($pageNumber =='show'){
                $pageNumber = $request->get('custom');
            }

            //check what answer choice was choosen by user
            if ($isCorrect == 'answer1') {
                $isCorrect = $answer[0];
            } elseif ($isCorrect == 'answer2') {
                $isCorrect = $answer[1];
            } elseif ($isCorrect == 'answer3') {
                $isCorrect = $answer[2];
            } else {
                $isCorrect = $answer[3];
            }

            //get the answer_id of answer choices to a question
            $answers_id = $this->getGradedAnswerOptionAction($question_id);

            //update question table based on user's input
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder('QuizLectureQuizBundle:GradedQuestion');
            $qb->update('QuizLectureQuizBundle:GradedQuestion', 'p')
                ->set('p.question', $qb->expr()->literal($question))
                ->set('p.pagenumber', $qb->expr()->literal($pageNumber))
                ->where('p.id = ?1')
                ->setParameter(1, $question_id)
                ->getQuery()
                ->execute();

            //update answer choices based on user's input
            $em1 = $this->getDoctrine()->getManager();

            for ($i = 0; $i < count($answer); $i++) {

                $qb = $em1->createQueryBuilder();
                $qb->update('QuizLectureQuizBundle:GradedAnswer', 'p')
                    ->set('p.answer', $qb->expr()->literal($answer[$i]))
                    ->set('p.correct', $qb->expr()->literal($isCorrect))
                    ->where('p.gradedQuestion = ?1')
                    ->andWhere('p.id= ?2')
                    ->setParameter(1, $question_id)
                    ->setParameter(2, $answers_id[$i])
                    ->getQuery()
                    ->execute();
            }

        }
        //redirects to profile page after update
        return $this->redirect($this->generateUrl('profile'));
    }


    /**
     * Edits true/false question
     * @Route("/tfEdit/{id}" , name="tf_edit")
     * Template("QuizLectureQuizBundle:EditQuiz:tfEdit.html.twig")
     */

    public function showTfAction($id)
    {

        $tfQuery = $this->tfAnswerQueryAction($id);


        return $this->render("QuizLectureQuizBundle:EditQuiz:tfEdit.html.twig",
            array('tfQuery' => $tfQuery));
    }


    /**
     * Edits true/false question
     * @Route("/tfEdit/update/new" , name="tf_update")
     *
     */
    public function editTfAction(Request $request)
    {

        //check if user has clicked the submit button
        //if yes get user's input
        if ($request->getMethod() == 'POST') {
            $question = $request->get('question');
            $answer = $request->get('answer');
            $pageNumber = $request->get('pagenumber');
            $question_id = $request->get('question_id');
            $isCorrect = $request->get('iscorrect');

            //check what answer choice was choosen by user
            if ($isCorrect == 'true') {
                $isCorrect = 'true';
            } else {
                $isCorrect = 'false';
            }

            //get pagenumber
            if($pageNumber =='show'){
                $pageNumber = $request->get('custom');
            }

            //get the answer_id of answer choices to a question
            $answers_id = $this->getTfAnswerOptionAction($question_id);

            //update question table based on user's input
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder('QuizLectureQuizBundle:TfQuestion');
            $qb->update('QuizLectureQuizBundle:TfQuestion', 'p')
                ->set('p.question', $qb->expr()->literal($question))
                ->set('p.pagenumber', $qb->expr()->literal($pageNumber))
                ->where('p.id = ?1')
                ->setParameter(1, $question_id)
                ->getQuery()
                ->execute();

            //update answer choices based on user's input
            $em1 = $this->getDoctrine()->getManager();

            for ($i = 0; $i < count($answer); $i++) {

                $qb = $em1->createQueryBuilder();
                $qb->update('QuizLectureQuizBundle:TfAnswer', 'p')
                    ->set('p.answer', $qb->expr()->literal($answer[$i]))
                    ->set('p.correct', $qb->expr()->literal($isCorrect))
                    ->where('p.tfQuestion = ?1')
                    ->andWhere('p.id= ?2')
                    ->setParameter(1, $question_id)
                    ->setParameter(2, $answers_id[$i])
                    ->getQuery()
                    ->execute();
            }

        }
        //redirects to profile page after update
        return $this->redirect($this->generateUrl('profile'));
    }


    /**
     * Edits outcome question
     * @Route("/ocEdit/{id}" , name="oc_edit")
     * Template("QuizLectureQuizBundle:EditQuiz:ocEdit.html.twig")
     */

    public function showOutcomeAction($id)
    {

        $ocQuery = $this->outcomeAnswerQueryAction($id);


        return $this->render("QuizLectureQuizBundle:EditQuiz:ocEdit.html.twig",
            array('ocQuery' => $ocQuery));
    }


    /**
     * Edits true/false question
     * @Route("/ocEdit/update/new" , name="oc_update")
     *
     */
    public function editOutcomeAction(Request $request)
    {
        //check if user has clicked the submit button
        //if yes get user's input
        if ($request->getMethod() == 'POST') {
            $question = $request->get('question');
            $answer = $request->get('answer');
            $pageNumber = $request->get('pagenumber');
            $question_id = $request->get('question_id');

            //get pagenumber
            if($pageNumber =='show'){
                $pageNumber = $request->get('custom');
            }

            //update question table based on user's input
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder('QuizLectureQuizBundle:OutcomeQuestion');
            $qb->update('QuizLectureQuizBundle:OutcomeQuestion', 'p')
                ->set('p.question', $qb->expr()->literal($question))
                ->set('p.pagenumber', $qb->expr()->literal($pageNumber))
                ->where('p.id = ?1')
                ->setParameter(1, $question_id)
                ->getQuery()
                ->execute();

            //update answer choices based on user's input
            $em1 = $this->getDoctrine()->getManager();

            $qb = $em1->createQueryBuilder();
            $qb->update('QuizLectureQuizBundle:OutcomeAnswer', 'p')
                ->set('p.answer', $qb->expr()->literal($answer))
                ->where('p.outcomeQuestion = ?1')
                ->setParameter(1, $question_id)
                ->getQuery()
                ->execute();
        }
        //redirects to profile page after update
        return $this->redirect($this->generateUrl('profile'));
    }
}


