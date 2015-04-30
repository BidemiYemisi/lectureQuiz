<?php

namespace Quiz\LectureQuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TfQuestion
 */
class TfQuestion
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     * min=3,
     * max= 5000,
     * message = "You must provide a question"
     * )
     * @ORM\Column(name="question", type="text", nullable=false)
     */
    private $question;


    /**
     * @ORM\OneToOne(targetEntity="Photo" , mappedBy ="TfQuestion")
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     */
    private $photo;


    /**
     * @ORM\OneToOne(targetEntity="LectureNote" , mappedBy ="TfQuestion")
     * @ORM\JoinColumn(name="lecture_note_id", referencedColumnName="id")
     */
    private $lectureNote;



    /**
     * @var integer
     */
    private $id;


    /**
     * @var \Quiz\LectureQuizBundle\Entity\Quiz
     *
     * @ORM\ManyToOne(targetEntity="Quiz\LectureQuizBundle\Entity\Quiz", inversedBy ="tf_question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     * })
     */
    private $quiz;


    /**
     * @ORM\OneToMany(targetEntity="TfAnswer" , mappedBy ="tfQuestion")
     * @ORM\JoinColumn(name="tf_answer_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $tf_answer;



    public function __construct(){
        $this->tf_answer= new ArrayCollection();
    }

    public function __toString(){  //get question
        return $this->question;
    }


    /**
     * Set question
     *
     * @param string $question
     * @return TfQuestion
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
     * @return TfQuestion
     */
    public function setQuiz(Quiz $quiz = null)
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
     * Add tf_answer
     *
     * @param \Quiz\LectureQuizBundle\Entity\TfAnswer $tf_answer
     * @return TfQuestion
     */
    public function addTfAnswer(TfAnswer $tf_answer)
    {
        $this->tf_answer[] = $tf_answer;

        return $this;
    }

    /**
     * Remove tf_answer
     *
     * @param \Quiz\LectureQuizBundle\Entity\TfAnswer $tf_answer
     */
    public function removeTfAnswer(TfAnswer $tf_answer)
    {
        $this->tf_answer->removeElement($tf_answer);
    }

    /**
     * Get tf_answer
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTfAnswers()
    {
        return $this->tf_answer;
    }



    /**
     * Set photo
     *
     * @param \Quiz\LectureQuizBundle\Entity\Photo $photo
     * @return TfQuestion
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
     * @return TfQuestion
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
     * @return TfQuestion
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
