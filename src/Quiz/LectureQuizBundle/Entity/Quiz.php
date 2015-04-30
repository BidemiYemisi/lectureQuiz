<?php

namespace Quiz\LectureQuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz", indexes={@ORM\Index(name="fk_quiz_user_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class Quiz
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     * min=3,
     * max= 5000,
     *
     * )
     *
     *
     * @ORM\Column(name="quiz_name", type="string", length=45, nullable=false)
     */
    private $quizName;

    /**
     * @var string
     *
     * @ORM\Column(name="quiz_url", type="text", nullable=false)
     */
    private $quizUrl;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_edited", type="datetime", nullable=false)
     */
    private $lastEdited;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    private $type;

    /**
     * @var \Quiz\LectureQuizBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Quiz\LectureQuizBundle\Entity\User" , inversedBy= "quizzes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     */
    private $user;


    /**
     * @var date
     * @ORM\Column(name="expired_at", type="date", nullable=false)
     */
    private $expiredAt;




    /**
     * @ORM\OneToMany(targetEntity="GradedQuestion" , mappedBy ="quiz")
     * @ORM\JoinColumn(name="graded_question_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $graded_question;


    /**
     * @ORM\OneToMany(targetEntity="OutcomeQuestion" , mappedBy ="quiz")
     * @ORM\JoinColumn(name="outcome_question_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $outcomequestion;

    /**
     * @ORM\OneToMany(targetEntity="TfQuestion" , mappedBy ="quiz")
     * @ORM\JoinColumn(name="tf_question_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $tf_question;


    //constructor to initialize both graded_question and outcomequestion to a new array collection on
    public function __construct(){
        $this->graded_question = new ArrayCollection();
        $this->outcomequestion = new ArrayCollection();
        $this->tf_question = new ArrayCollection();
        $this->setExpiredAt(new \DateTime);
        $this->setDateCreated(new \DateTime);
        $this->setLastEdited(new \DateTime);
        $this->setQuizUrl('url');

    }

    public function __toString(){  //set pages property
        return $this->quizName;
    }




    /**
     * Set quizName
     *
     * @param string $quizName
     * @return Quiz
     */
    public function setQuizName($quizName)
    {
        $this->quizName = $quizName;

        return $this;
    }

    /**
     * Get quizName
     *
     * @return string 
     */
    public function getQuizName()
    {
        return $this->quizName;
    }

    /**
     * Set quizUrl
     *
     * @param string $quizUrl
     * @return Quiz
     */
    public function setQuizUrl($quizUrl)
    {
        $this->quizUrl = $quizUrl;

        return $this;
    }

    /**
     * Get quizUrl
     *
     * @return string 
     */
    public function getQuizUrl()
    {
        return $this->quizUrl;
    }



    public function getType(){

        return $this->type;
    }

    public function setType($type){

        $this->type = $type;

        return $this;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Quiz
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set lastEdited
     *
     * @param \DateTime $lastEdited
     * @return Quiz
     */
    public function setLastEdited($lastEdited)
    {
        $this->lastEdited = $lastEdited;

        return $this;
    }

    /**
     * Get lastEdited
     *
     * @return \DateTime 
     */
    public function getLastEdited()
    {
        return $this->lastEdited;
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
     * Set user
     *
     * @param \Quiz\LectureQuizBundle\Entity\User $user
     * @return Quiz
     */
    public function setUser(\Quiz\LectureQuizBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Quiz\LectureQuizBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add graded_question
     *
     * @param \Quiz\LectureQuizBundle\Entity\GradedQuestion $graded_question
     * @return Quiz
     */
    public function addGraded_Question(\Quiz\LectureQuizBundle\Entity\GradedQuestion $graded_question)
    {
        $this->graded_question[] = $graded_question;

        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Quiz\LectureQuizBundle\Entity\GradedQuestion $graded_question
     */
    public function removeGraded_Question(\Quiz\LectureQuizBundle\Entity\GradedQuestion $graded_question)
    {
        $this->graded_question->removeElement($graded_question);
    }

    /**
     * Get graded_questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGraded_Questions()
    {
        return $this->graded_question;
    }


    /**
     * Add outcomequestion
     *
     * @param \Quiz\LectureQuizBundle\Entity\OutcomeQuestion $outcomequestion
     * @return Quiz
     */
    public function addOutcomequestion(\Quiz\LectureQuizBundle\Entity\OutcomeQuestion $outcomequestion)
    {
        $this->outcomequestion[] = $outcomequestion;

        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Quiz\LectureQuizBundle\Entity\OutcomeQuestion $outcomequestion
     */
    public function removeOutcomequestion(\Quiz\LectureQuizBundle\Entity\OutcomeQuestion $outcomequestion)
    {
        $this->outcomequestion->removeElement($outcomequestion);
    }

    /**
     * Get graded_questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOutcomequestions()
    {
        return $this->outcomequestion;
    }


    /**
     * Add tf_question
     *
     * @param \Quiz\LectureQuizBundle\Entity\TfQuestion $tf_question
     * @return Quiz
     */
    public function addTfQuestions(\Quiz\LectureQuizBundle\Entity\TfQuestion $tfQuestion)
    {
        $this->tf_question[] = $tfQuestion;

        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Quiz\LectureQuizBundle\Entity\TfQuestion $tf_question
     */
    public function removeTfQuestions(\Quiz\LectureQuizBundle\Entity\TfQuestion $tfQuestion)
    {
        $this->tf_question->removeElement($tfQuestion);
    }

    /**
     * Get tf_question
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTfQuestions()
    {
        return $this->tf_question;
    }



    /**
     * Set expiredAt
     *
     * @param \DateTime $expiredAt
     * @return Quiz
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * Get expiredAt
     *
     * @return \DateTime 
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }
}
