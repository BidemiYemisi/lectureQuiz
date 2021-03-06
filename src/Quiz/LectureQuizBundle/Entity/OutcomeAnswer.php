<?php

namespace Quiz\LectureQuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * OutcomeAnswer
 *
 * @ORM\Table(name="outcome_answer", indexes={@ORM\Index(name="fk_outcome_answer_outcome_question1_idx", columns={"outcome_question_id"})})
 * @ORM\Entity
 */
class OutcomeAnswer
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="answer", type="text", nullable=false)
     */
    private $answer;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Quiz\LectureQuizBundle\Entity\OutcomeQuestion
     *
     * @ORM\ManyToOne(targetEntity="Quiz\LectureQuizBundle\Entity\OutcomeQuestion", inversedBy="outcome_answer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="outcome_question_id", referencedColumnName="id")
     * })
     */
    private $outcomeQuestion;



    /**
     * Set answer
     *
     * @param string $answer
     * @return OutcomeAnswer
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

 

    /**
     * Set outcomeQuestion
     *
     * @param \Quiz\LectureQuizBundle\Entity\OutcomeQuestion $outcomeQuestion
     * @return OutcomeAnswer
     */
    public function setOutcomeQuestion(\Quiz\LectureQuizBundle\Entity\OutcomeQuestion $outcomeQuestion = null)
    {
        $this->outcomeQuestion = $outcomeQuestion;

        return $this;
    }

    /**
     * Get outcomeQuestion
     *
     * @return \Quiz\LectureQuizBundle\Entity\OutcomeQuestion 
     */
    public function getOutcomeQuestion()
    {
        return $this->outcomeQuestion;
    }
}
