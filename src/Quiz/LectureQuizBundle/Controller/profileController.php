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


    private function queryGradedAction($userId)
    {
        $quizGd = null;
        //get all graded questions based on quizzes created by user

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


    private function queryOutcomeAction($userId)
    {
        $quizOc = null;
        //get all outcome questions based on quizzes created by user

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

    private function queryTfAction($userId)
    {
        $quizTf = null;
        //get all t/f questions  based on quizzes created by user

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

        //get user details by calling the getUserAction() method
        $userData = $this->getUserAction($userId);

        $graded = $this->queryGradedAction($userId);
        $outcome = $this->queryOutcomeAction($userId);
        $tf = $this->queryTfAction($userId);

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
                'dec' => $dec
            ));


    }


    /**
     * Displays profile user details and quizzes created by user
     * @Route("/graded/{id}", name="profile_graded")
     */
    public function displayGradedAction($id)
    {
        $questn_id = null;
        $question = null;
        $answer = null;
        $page_number = null;


        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('q.answer', 'p.question', 'q.correct', 'p.id', 'p.pagenumber')
            ->from('QuizLectureQuizBundle:GradedAnswer', 'q')
            ->innerJoin('q.gradedQuestion', 'p')
            ->where('p.quiz = :quizId')
            ->setParameter('quizId', $id);

        $query = $qb->getQuery();
        $gradedquestion = $query->getResult();

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

                $pdf->getGradedOption($lenght, $alphabetd, $answer);

                $photo_id = $this->getGradedPhotoAction($questn_id);

                $pdf->getImage($photo_id);
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


                            $pdf->getGradedOption($lenght, $alphabetd, $answer);

                            $photo_id = $this->getGradedPhotoAction($questn_id);

                            $pdf->getImage($photo_id);
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


                            //loop through based on answer option lenght and append the correcponding alphabets
                            for ($i = 0; $i < $lenght; $i++) {

                                $answer1 = $alphabetd[$i] . $answer[$i];

                                //add each answer choice to the pdf generator
                                $pdf->MultiCell(0, 10, $answer1);
                                $pdf->Ln(4);
                            }

                            $photo_id = $this->getGradedPhotoAction($questn_id);

                            $pdf->getImage($photo_id);
                            $pdf->GdUrl($questn_id, -38);
                            $pdf->GdUrl($questn_id, 30);

                        }
                    }
                } else {
                    $pdf->AddPage();
                    $pdf->getQuizPage($question);


                    //loop through based on answer option lenght and append the correcponding alphabets
                    $pdf->getGradedOption($lenght, $alphabetd, $answer);

                    $photo_id = $this->getGradedPhotoAction($questn_id);

                    $pdf->getImage($photo_id);
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
     * Displays profile user details and quizzes created by user
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
                $pdf->getImage($photo_id);
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
                            $pdf->getImage($photo_id);
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
                            $pdf->getImage($photo_id);
                            $pdf->OcUrl($question_id, -38);
                            $pdf->OcUrl($question_id, 30);

                        }
                    }
                } else {
                    $pdf->AddPage();
                    $pdf->getQuizPage($question);
                    $photo_id = $this->getOutcomePhotoAction($question_id);
                    $pdf->getImage($photo_id);
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
     * Displays profile user details and quizzes created by user
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

            $true = '(A) ' . 'True';
            $false = '(B) ' . 'False';

            //create pdf object
            $pdf = new PDF();


            //get the note id if it exists
            $file_id = $this->getTfNoteAction($q_id);

            if ($file_id == null) {
                $pdf->AddPage();
                $pdf->getQuizPage($question);
                $pdf->getTfOption($true, $false);
                $photo_id = $this->getTfPhotoAction($q_id);
                $pdf->getImage($photo_id);
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
                            $pdf->getTfOption($true, $false);
                            $photo_id = $this->getTfPhotoAction($q_id);
                            $pdf->getImage($photo_id);
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
                            $pdf->getTfOption($true, $false);
                            $photo_id = $this->getTfPhotoAction($q_id);
                            $pdf->getImage($photo_id);
                            $pdf->TfUrl($q_id, -38);
                            $pdf->TfUrl($q_id, 30);

                        }
                    }
                } else {
                    $pdf->AddPage();
                    $pdf->getQuizPage($question);
                    $pdf->getTfOption($true, $false);
                    $photo_id = $this->getTfPhotoAction($q_id);
                    $pdf->getImage($photo_id);
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
     * User data for User details page
     *
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
     * @Route("/{id}" , name="graded_edit")
     */

    public function editTfAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p.question', 'q.correct')
            ->from('QuizLectureQuizBundle:TfAnswer', 'q')
            ->innerJoin('q.tfQuestion', 'p')
            ->where('p.quiz = :quizId')
            ->setParameter('quizId', $id);

        $query = $qb->getQuery();
        $tfquestion = $query->getResult();

        if ($request->getMethod() == 'POST') {

            $question = $request->get('question');
            // $correct = $request->get('iscorrect');
            //$photo = $request->get('photo');

            $update = $em->createQueryBuilder();
            $update->update('QuizLectureQuizBundle:TfQuestion', 'q')
                ->set('q.question')
                ->where('q.quizId = :quizId')
                ->setParameter('quizId', $id);
        }

        return array('tfQ' => $tfquestion);


    }
}


