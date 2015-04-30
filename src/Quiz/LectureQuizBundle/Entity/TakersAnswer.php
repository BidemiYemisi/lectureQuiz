<?php

namespace Quiz\LectureQuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TakersAnswer
 */
class TakersAnswer
{
    /**
     * @var string
     * @ORM\Column(name="answercode", type="string", length= 300, nullable=false)
     */
    private $answercode;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     *
     * @var integer
     * @ORM\Column(name="outcomeQuestionId", type="integer", nullable=false)
     *
     */
    private $outcomeQuestionId;


/**
     *
     * @var integer
     * @ORM\Column(name="quizId", type="integer", nullable=false)
     *
     */
    private $quizId;


    /**
     * Set answercode
     *
     * @param string $answercode
     * @return TakersAnswer
     */
    public function setAnswercode($answercode)
    {
        $this->answercode = $answercode;

        return $this;
    }

    /**
     * Get answercode
     *
     * @return string
     */
    public function getAnswercode()
    {
        return $this->answercode;
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
     * @param $outcomeQuestionId
     * @return $this
     */
    public function setOutcomeQuestion($outcomeQuestionId)
    {
        $this->outcomeQuestionId = $outcomeQuestionId;

        return $this;
    }

    /**
     * @return int
     */
    public function getOutcomeQuestion()
    {
        return $this->outcomeQuestionId;
    }


    /**
     * @param $quizId
     * @return $this
     */
    public function setQuizId($quizId)
    {
        $this->quizId = $quizId;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuizId()
    {
        return $this->quizId;
    }
}
