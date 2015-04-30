<?php

namespace Quiz\LectureQuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * GradedQuestion
 *
 * @ORM\Table(name="graded_question", indexes={@ORM\Index(name="fk_graded_question_quiz1_idx", columns={"quiz_id"})})
 * @ORM\Entity
 */
class GradedQuestion
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=3,max= 5000)
     *
     * @ORM\Column(name="question", type="text", nullable=false)
     */
    private $question;


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Quiz\LectureQuizBundle\Entity\Quiz
     *
     * @ORM\ManyToOne(targetEntity="Quiz\LectureQuizBundle\Entity\Quiz", inversedBy ="graded_question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     * })
     */
    private $quiz;


    /**
     * @ORM\OneToOne(targetEntity="Photo" , mappedBy ="gradedQuestion")
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     */
    private $photo;


    /**
     * @ORM\OneToOne(targetEntity="LectureNote" , mappedBy ="gradedQuestion")
     * @ORM\JoinColumn(name="lecture_note_id", referencedColumnName="id")
     */
    private $lectureNote;


    /**
     * @ORM\OneToMany(targetEntity="GradedAnswer" , mappedBy ="gradedQuestion")
     * @ORM\JoinColumn(name="graded_answer_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $graded_answer;




    public function __construct()
    {
        $this->graded_answer = new ArrayCollection();
    }

    public function __toString()
    {  //get question
        return $this->question;
    }


    /**
     * Set question
     *
     * @param string $question
     * @return GradedQuestion
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set quiz
     *
     * @param \Quiz\LectureQuizBundle\Entity\Quiz $quiz
     * @return GradedQuestion
     */
    public function setQuiz(\Quiz\LectureQuizBundle\Entity\Quiz $quiz = null)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \Quiz\LectureQuizBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Add graded_answer
     *
     * @param \Quiz\LectureQuizBundle\Entity\GradedAnswer $graded_answer
     * @return GradedQuestion
     */
    public function addGradedAnswer(GradedAnswer $graded_answer)
    {
        $this->graded_answer[] = $graded_answer;

        return $this;
    }

    /**
     * Remove graded_answer
     *
     * @param \Quiz\LectureQuizBundle\Entity\GradedAnswer $graded_answer
     */
    public function removeGradedAnswer(GradedAnswer $graded_answer)
    {
        $this->graded_answer->removeElement($graded_answer);
    }

    /**
     * Get graded_answer
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGradedAnswers()
    {
        return $this->graded_answer;
    }


    /**
     * Set photo
     *
     * @param \Quiz\LectureQuizBundle\Entity\Photo $photo
     * @return GradedQuestion
     */
    public function setPhoto(\Quiz\LectureQuizBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \Quiz\LectureQuizBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set lectureNote
     *
     * @param \Quiz\LectureQuizBundle\Entity\LectureNote $lectureNote
     * @return GradedQuestion
     */
    public function setLectureNote(\Quiz\LectureQuizBundle\Entity\LectureNote $lectureNote = null)
    {
        $this->lectureNote = $lectureNote;

        return $this;
    }

    /**
     * Get lectureNote
     *
     * @return \Quiz\LectureQuizBundle\Entity\LectureNote
     */
    public function getLectureNote()
    {
        return $this->lectureNote;
    }

    /**
     * @var integer
     */
    private $pagenumber;


    /**
     * Set pagenumber
     *
     * @param integer $pagenumber
     * @return GradedQuestion
     */
    public function setPagenumber($pagenumber)
    {
        $this->pagenumber = $pagenumber;

        return $this;
    }

    /**
     * Get pagenumber
     *
     * @return integer
     */
    public function getPagenumber()
    {
        return $this->pagenumber;
    }
}
