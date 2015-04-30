<?php

namespace Quiz\LectureQuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OutcomeQuestion
 *
 * @ORM\Table(name="outcome_question", indexes={@ORM\Index(name="fk_outcome_question_quiz1_idx", columns={"quiz_id"})})
 * @ORM\Entity
 */
class OutcomeQuestion
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     * min=3,
     * max= 5000,
     * message = "You must provide a question"
     * )
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
     * @ORM\ManyToOne(targetEntity="Quiz\LectureQuizBundle\Entity\Quiz", inversedBy="outcomequestion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     * })
     */
    private $quiz;

    /**
     * @ORM\OneToOne(targetEntity="Photo" , mappedBy ="OutcomeQuestion")
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     */
    private $photo;


    /**
     * @ORM\OneToOne(targetEntity="LectureNote" , mappedBy ="OutcomeQuestion")
     * @ORM\JoinColumn(name="lecture_note_id", referencedColumnName="id")
     */
    private $lectureNote;


    /**
     * @ORM\OneToMany(targetEntity="OutcomeAnswer" , mappedBy ="outcomeQuestion")
     * @ORM\JoinColumn(name="outcome_answer_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $outcome_answer;



    /**
     * @ORM\OneToMany(targetEntity="TakersAnswer" , mappedBy ="outcomeQuestion")
     * @ORM\JoinColumn(name="takers_answer_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $takers_answer;



    public function __construct(){
        $this->outcome_answer= new ArrayCollection();
        $this->takers_answer = new ArrayCollection();
    }

    public function __toString(){
        return $this->question;
    }



    /**
     * Set question
     *
     * @param string $question
     * @return OutcomeQuestion
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
     * @return OutcomeQuestion
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
     * Add outcome_answer
     *
     * @param \Quiz\LectureQuizBundle\Entity\OutcomeAnswer $outcome_answer
     * @return OutcomeQuestion
     */
    public function addOutcomeAnswer(\Quiz\LectureQuizBundle\Entity\OutcomeAnswer $outcome_answer)
    {
        $this->outcome_answer[] = $outcome_answer;

        return $this;
    }

    /**
     * Remove outcome_answer
     *
     * @param \Quiz\LectureQuizBundle\Entity\OutcomeAnswer $outcome_answer
     */
    public function removeOutcomeAnswer(\Quiz\LectureQuizBundle\Entity\OutcomeAnswer $outcome_answer)
    {
        $this->outcome_answer->removeElement($outcome_answer);
    }

    /**
     * Get outcome_answer
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOutcomeAnswers()
    {
        return $this->outcome_answer;
    }


    /**
     * Set photo
     *
     * @param \Quiz\LectureQuizBundle\Entity\Photo $photo
     * @return OutcomeQuestion
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
     * @return OutcomeQuestion
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
     * Add takers_answer
     *
     * @param \Quiz\LectureQuizBundle\Entity\TakersAnswer $takers_answer
     * @return OutcomeQuestion
     */
    public function addTakersAnswer(TakersAnswer $takers_answer)
    {
        $this->takers_answer[] = $takers_answer;

        return $this;
    }

    /**
     * Remove takers_answer
     *
     * @param \Quiz\LectureQuizBundle\Entity\TakersAnswer $takers_answer
     */
    public function removeTakersAnswer(TakersAnswer $takers_answer)
    {
        $this->takers_answer->removeElement($takers_answer);
    }

    /**
     * Get takers_answer
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTakersAnswers()
    {
        return $this->takers_answer;
    }

    /**
     * @var integer
     */
    private $pagenumber;


    /**
     * Set pagenumber
     *
     * @param integer $pagenumber
     * @return OutcomeQuestion
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
