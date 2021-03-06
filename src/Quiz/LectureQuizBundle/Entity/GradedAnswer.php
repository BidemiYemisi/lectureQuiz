<?php

namespace Quiz\LectureQuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * GradedAnswer
 *
 * @ORM\Table(name="graded_answer", indexes={@ORM\Index(name="fk_graded_answer_graded_question1_idx", columns={"graded_question_id"})})
 * @ORM\Entity
 */
class GradedAnswer
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Quiz\LectureQuizBundle\Entity\GradedQuestion
     *
     * @ORM\ManyToOne(targetEntity="Quiz\LectureQuizBundle\Entity\GradedQuestion", inversedBy ="graded_answer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="graded_question_id", referencedColumnName="id")
     * })
     */
    private $gradedQuestion;


    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="answer", type="string",  nullable=false)
     */
    private $answer;


    /**
     * @var string
     * @ORM\Column(name="correct", type="string" ,length= 300, nullable=false)
     */
    private $correct;

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
     * Set gradedQuestion
     *
     * @param \Quiz\LectureQuizBundle\Entity\GradedQuestion $gradedQuestion
     * @return GradedAnswer
     */
    public function setGradedQuestion(\Quiz\LectureQuizBundle\Entity\GradedQuestion $gradedQuestion = null)
    {
        $this->gradedQuestion = $gradedQuestion;

        return $this;
    }

    /**
     * Get gradedQuestion
     *
     * @return \Quiz\LectureQuizBundle\Entity\GradedQuestion
     */
    public function getGradedQuestion()
    {
        return $this->gradedQuestion;
    }


    /**
     * Set answer
     *
     * @param string $answer
     * @return GradedAnswer
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
     * @return GradedAnswer
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



    public function color($code)
    {
        if ($code == 1) {
            return '#0066FF';

        } elseif ($code == 2) {
            return '#FF6600';

        } elseif ($code == 3) {
            return '#33CC33';

        } else
            return '#FF5050';

    }
    /**
     * @var integer
     */
    private $vote;


    /**
     * Set vote
     *
     * @param integer $vote
     * @return GradedAnswer
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
