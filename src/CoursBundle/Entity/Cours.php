<?php

namespace CoursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cours
 *
 * @ORM\Table(name="cours")
 * @ORM\Entity(repositoryClass="CoursBundle\Repository\CoursRepository")
 */
class Cours
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
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deplacement", type="boolean", length=1, nullable=true)
     */
    private $deplacement;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="accueille", type="boolean", length=1, nullable=true)
     */
    private $accueille;
    
    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255)
     */
    private $commentaire;
    
    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string")
     */
    private $date;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="villeAccueil", type="string", nullable=true)
     */
     private $villeAccueil;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="CoursBundle\Entity\Formation")
     */
    private $formations;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="CoursBundle\Entity\AnneeEtude")
     */
    private $anneeEtudes;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="CoursBundle\Entity\Module")
     */
    private $modules;
    
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     */
    private $formateurs;
    
    /**
     * @ORM\OneToMany(targetEntity="CoursBundle\Entity\Participer", mappedBy="coursP")
     */
    private $participer;

    /**
    * @var int
    * 
    * @ORM\Column(name="etatReservation", type="integer")
    */
    private $etatReservation;

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
     * Set prix
     *
     * @param integer $prix
     *
     * @return Cours
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set deplacement
     *
     * @param boolean $deplacement
     *
     * @return Cours
     */
    public function setDeplacement($deplacement)
    {
        $this->deplacement = $deplacement;

        return $this;
    }

    /**
     * Get deplacement
     *
     * @return boolean
     */
    public function getDeplacement()
    {
        return $this->deplacement;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Cours
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set accueille
     *
     * @param boolean $accueille
     *
     * @return Cours
     */
    public function setAccueille($accueille)
    {
        $this->accueille = $accueille;

        return $this;
    }

    /**
     * Get accueille
     *
     * @return boolean
     */
    public function getAccueille()
    {
        return $this->accueille;
    }

    /**
     * Set formations
     *
     * @param \CoursBundle\Entity\Formation $formations
     *
     * @return Cours
     */
    public function setFormations(\CoursBundle\Entity\Formation $formations = null)
    {
        $this->formations = $formations;

        return $this;
    }

    /**
     * Get formations
     *
     * @return \CoursBundle\Entity\Formation
     */
    public function getFormations()
    {
        return $this->formations;
    }

    /**
     * Set anneeEtudes
     *
     * @param \CoursBundle\Entity\AnneeEtude $anneeEtudes
     *
     * @return Cours
     */
    public function setAnneeEtudes(\CoursBundle\Entity\AnneeEtude $anneeEtudes = null)
    {
        $this->anneeEtudes = $anneeEtudes;

        return $this;
    }

    /**
     * Get anneeEtudes
     *
     * @return \CoursBundle\Entity\AnneeEtude
     */
    public function getAnneeEtudes()
    {
        return $this->anneeEtudes;
    }

    /**
     * Set modules
     *
     * @param \CoursBundle\Entity\Module $modules
     *
     * @return Cours
     */
    public function setModules(\CoursBundle\Entity\Module $modules = null)
    {
        $this->modules = $modules;

        return $this;
    }

    /**
     * Get modules
     *
     * @return \CoursBundle\Entity\Module
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Cours
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set formateurs
     *
     * @param \UserBundle\Entity\User $formateurs
     *
     * @return Cours
     */
    public function setFormateurs(\UserBundle\Entity\User $formateurs = null)
    {
        $this->formateurs = $formateurs;

        return $this;
    }

    /**
     * Get formateurs
     *
     * @return \UserBundle\Entity\User
     */
    public function getFormateurs()
    {
        return $this->formateurs;
    }

    /**
     * Set villeAccueil
     *
     * @param string $villeAccueil
     *
     * @return Cours
     */
    public function setVilleAccueil($villeAccueil)
    {
        $this->villeAccueil = $villeAccueil;

        return $this;
    }

    /**
     * Get villeAccueil
     *
     * @return string
     */
    public function getVilleAccueil()
    {
        return $this->villeAccueil;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->participer = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add participer
     *
     * @param \CoursBundle\Entity\Participer $participer
     *
     * @return Cours
     */
    public function addParticiper(\CoursBundle\Entity\Participer $participer)
    {
        $this->participer[] = $participer;

        return $this;
    }

    /**
     * Remove participer
     *
     * @param \CoursBundle\Entity\Participer $participer
     */
    public function removeParticiper(\CoursBundle\Entity\Participer $participer)
    {
        $this->participer->removeElement($participer);
    }

    /**
     * Get participer
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticiper()
    {
        return $this->participer;
    }
    
    /**
     * Set etatReservation
     *
     * @param integer $etatReservation
     *
     * @return Cours
     */
    public function setEtatReservation($etatReservation)
    {
        $this->etatReservation = $etatReservation;

        return $this;
    }

    /**
     * Get etatReservation
     *
     * @return integer
     */
    public function getEtatReservation()
    {
        return $this->etatReservation;
    }
}
