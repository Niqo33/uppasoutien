<?php

namespace CoursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avis
 *
 * @ORM\Table(name="avis")
 * @ORM\Entity(repositoryClass="CoursBundle\Repository\AvisRepository")
 */
class Avis
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
     * @ORM\Column(name="contenu", type="string", length=255)
     */
    private $contenu;

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer")
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     */
    private $utilisateurA;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="CoursBundle\Entity\Cours")
     */
    private $coursA;
    
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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Avis
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return Avis
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return int
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Avis
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set utilisateurA
     *
     * @param \UserBundle\Entity\User $utilisateurA
     *
     * @return Avis
     */
    public function setUtilisateurA(\UserBundle\Entity\User $utilisateurA = null)
    {
        $this->utilisateurA = $utilisateurA;

        return $this;
    }

    /**
     * Get utilisateurA
     *
     * @return \UserBundle\Entity\User
     */
    public function getUtilisateurA()
    {
        return $this->utilisateurA;
    }

    /**
     * Set coursA
     *
     * @param \CoursBundle\Entity\Cours $coursA
     *
     * @return Avis
     */
    public function setCoursA(\CoursBundle\Entity\Cours $coursA = null)
    {
        $this->coursA = $coursA;

        return $this;
    }

    /**
     * Get coursA
     *
     * @return \CoursBundle\Entity\Cours
     */
    public function getCoursA()
    {
        return $this->coursA;
    }
}
