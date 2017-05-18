<?php

namespace Wiki\WikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserStatistic
 *
 * @ORM\Table(name="user_statistic")
 * @ORM\Entity(repositoryClass="Wiki\WikiBundle\Repository\UserStatisticRepository")
 */
class UserStatistic
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return UserStatistic
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }
}
