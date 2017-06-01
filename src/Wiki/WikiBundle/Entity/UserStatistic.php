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
     *
     * Identifiant unique d'une statistique d'un utilisateur
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Id de l'utilisateur concernée
     *
     * @var int
     *
     * @ORM\Column(name="userId", type="integer")
     */
    private $userId;

    /**
     *
     * Score de l'utilisateur
     *
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserStatistic
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
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

