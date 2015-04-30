<?php

namespace Quiz\LectureQuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TfAnswer
 */
class TfAnswer
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="answercode", type="string", length= 300, nullable=false)
     */
    private $answer;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     */
    private $correct;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Quiz\LectureQuizBundle\Entity\TfQuestion
     *
     * @ORM\ManyToOne(targetEntity="Quiz\LectureQuizBundle\Entity\TfQuestion", inversedBy ="tf_answer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tf_question_id", referencedColumnName="id")
     * })
     */
    private $tfQuestion;


    /**
     * Set answer
     *
     * @param string $answer
     * @return TfAnswer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set correct
     *
     * @param string $correct
     * @return TfAnswer
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return string 
     */
    public function getCorrect()
    {
        return $this->correct;
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
     * Set tfQuestion
     *
     * @param \Quiz\LectureQuizBundle\Entity\TfQuestion $tfQuestion
     * @return TfAnswer
     */
    public function setTfQuestion(\Quiz\LectureQuizBundle\Entity\TfQuestion $tfQuestion = null)
    {
        $this->tfQuestion = $tfQuestion;

        return $this;
    }

    /**
     * Get tfQuestion
     *
     * @return \Quiz\LectureQuizBundle\Entity\TfQuestion 
     */
    public function getTfQuestion()
    {
        return $this->tfQuestion;
    }


    /**
     * @var integer
     */
    private $vote;


    /**
     * Set vote
     *
     * @param integer $vote
     * @return TfAnswer
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return integer
     */
    public function getVote()
    {
        return $this->vote;
    }
}
