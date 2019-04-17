<?php

namespace CoursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Discussion
 *
 * @ORM\Table(name="discussion")
 * @ORM\Entity(repositoryClass="CoursBundle\Repository\DiscussionRepository")
 */
class Discussion
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
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message;

        /**
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     */
    private $utilisateurD;
    
        /**
     *
     * @ORM\ManyToOne(targetEntity="CoursBundle\Entity\Cours")
     */
    private $coursD;
    
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
     * Set message
     *
     * @param string $message
     *
     * @return Discussion
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set utilisateurD
     *
     * @param \UserBundle\Entity\User $utilisateurD
     *
     * @return Discussion
     */
    public function setUtilisateurD(\UserBundle\Entity\User $utilisateurD = null)
    {
        $this->utilisateurD = $utilisateurD;

        return $this;
    }

    /**
     * Get utilisateurD
     *
     * @return \UserBundle\Entity\User
     */
    public function getUtilisateurD()
    {
        return $this->utilisateurD;
    }

    /**
     * Set coursD
     *
     * @param \CoursBundle\Entity\Cours $coursD
     *
     * @return Discussion
     */
    public function setCoursD(\CoursBundle\Entity\Cours $coursD = null)
    {
        $this->coursD = $coursD;

        return $this;
    }

    /**
     * Get coursD
     *
     * @return \CoursBundle\Entity\Cours
     */
    public function getCoursD()
    {
        return $this->coursD;
    }
}
