<?php

namespace CoursBundle\Controller;

use CoursBundle\Form\CoursType;
use CoursBundle\Entity\Cours;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CoursBundle\Entity\Formation;
use CoursBundle\Entity\Participer;
use UserBundle\Entity\User;
use CoursBundle\Entity\Discussion;
use CoursBundle\Entity\Avis;


class CoursController extends Controller
{
    //Affichage de la page d'Accueil qui est par default la page MonEspace
    public function accueilAction()
    {
        return $this->render('CoursBundle:Cours:accueil.html.twig');
    }
    
    //Affichage de la page A propos
    public function aboutAction()
    {
        return $this->render('CoursBundle:Cours:about.html.twig');
    }
    
    //Récuperation valeurs en BD pour remplir liste déroulante & affichage Vue partie PC
    public function proposerCoursAction()
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
        
        $repositoryFormation = $gestionnaireEntite -> getRepository('CoursBundle:Formation');
        $liste_formation = $repositoryFormation -> findAll();
                                
        $repositoryAnneeEtude = $gestionnaireEntite -> getRepository('CoursBundle:AnneeEtude');
        $liste_anneeEtude = $repositoryAnneeEtude -> findAll();
        
        $repositoryModule = $gestionnaireEntite -> getRepository('CoursBundle:Module');
        $liste_module = $repositoryModule -> findAll();
        
        return $this->render('CoursBundle:Cours:proposerCours.html.twig',
                                array('liste_formation' => $liste_formation,
                                      'liste_anneeEtude' => $liste_anneeEtude,
                                      'liste_module' => $liste_module));
    }
    
    //Ajouter un Cours en BD
    public function ajouterCoursAction(Request $requeteUtilisateur){
        
        // Créer un objet cours vide
        $cours = new Cours();
        
        //Creer un objet participer vide
        $participer = new Participer();
        
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
            
        //Recuperation des données envoyés par le formulaire
        $formation = $requeteUtilisateur->get('formation');
        $anneeEtude = $requeteUtilisateur->get('anneeEtude');
        $module = $requeteUtilisateur->get('module');
        $date = $requeteUtilisateur->get('date');
        $prix = $requeteUtilisateur->get('prix');
        $accueillir = $requeteUtilisateur->get('accueillir');
        $seDeplacer = $requeteUtilisateur->get('seDeplacer');
        $commentaire = $requeteUtilisateur->get('commentaire');
        $villeAccueil = $requeteUtilisateur->get('villeAccueil');
        
        //Recuperation repository Formation
        $repositoryFormation = $gestionnaireEntite -> getRepository('CoursBundle:Formation');
        $formationString = $repositoryFormation -> findOneBy(array('nom' => $formation));
        
        //Recuperation repository Module
        $repositoryModule = $gestionnaireEntite -> getRepository('CoursBundle:Module');
        $moduleString = $repositoryModule -> findOneBy(array('nom' => $module));
        
        //Recuperation repository AnneeEtude
        $repositoryAnneeEtude = $gestionnaireEntite -> getRepository('CoursBundle:AnneeEtude');
        $anneeEtudeString = $repositoryAnneeEtude -> findOneBy(array('nom' => $anneeEtude));
        
        //Recuperation repository User
        $user = $this->getUser();
        $repositoryUser = $gestionnaireEntite -> getRepository('UserBundle:User');
        $userString = $repositoryUser -> findOneBy(array('id' => $user));
        
        //Recuperation repository Participer
        //$repositoryParticiper = $gestionnaireEntite -> getRepository('CoursBundle:Participer');
        //$participer = $repositoryParticiper -> findOneBy(array('cours_p_id'=>$id));
        
        //Association des valeurs à l'objet Cours
        $cours->setFormateurs($userString);
        $cours->setFormations($formationString);
        $cours->setAnneeEtudes($anneeEtudeString);
        $cours->setModules($moduleString);
        $cours->setDate($date);
        $cours->setPrix($prix);
        $cours->setAccueille($accueillir);
        
            if($seDeplacer == NULL){
                $cours->setDeplacement(0);
            }
            else { $cours->setDeplacement(1); }
            
            if($accueillir == NULL){
                $cours->setAccueille(0);
            }
            else { $cours->setAccueille(1); }
            
        $cours->setCommentaire($commentaire);
        $cours->setVilleAccueil($villeAccueil);
        $cours->setEtatReservation(0);
        
        //Association des valeurs à l'objet Participer
        $participer->setEtatCours(0);
        $participer->setCoursP($cours);
        
        // On enregistre le cours en BD
        $gestionnaireEntite -> persist($participer);
        $gestionnaireEntite -> persist($cours);
        $gestionnaireEntite -> flush();
        
        $this->get('session')->getFlashBag()->add('success', 'Le cours a bien été mis en ligne !');
        
        /*
        A DECOMMENTER POUR LES ENVOIS DE MAILS
        //Creation et envoie d'un mail de confirmation
        $message = \Swift_Message::newInstance()
        ->setSubject('Confirmation mise en ligne')
        ->setFrom('adresseenvoyeur')
        ->setTo('adressedestinataire')
        ->setCharset('utf-8')
        ->setContentType('text/html')
        ->setBody('Votre cours a bien été mis en ligne'));
        $this->get('mailer')->send($message);*/
        
        return $this->redirect($this->generateUrl('proposerCours'));
    }
    
    //Recupération valeurs en BD pour les listes déroulantes & affichage Vue partie RC    
    public function rechercherCoursAction()
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
        
        $repositoryFormation = $gestionnaireEntite -> getRepository('CoursBundle:Formation');
        $liste_formation = $repositoryFormation -> findAll();
                                
        $repositoryAnneeEtude = $gestionnaireEntite -> getRepository('CoursBundle:AnneeEtude');
        $liste_anneeEtude = $repositoryAnneeEtude -> findAll();
        
        $repositoryModule = $gestionnaireEntite -> getRepository('CoursBundle:Module');
        $liste_module = $repositoryModule -> findAll();
        
        return $this->render('CoursBundle:Cours:rechercherCours.html.twig',
                                array('liste_formation' => $liste_formation,
                                      'liste_anneeEtude' => $liste_anneeEtude,
                                      'liste_module' => $liste_module));
    }
        
    //Action Rechercher lors du clic bouton "Rechercher"
    public function resultatRCAction(Request $requeteUtilisateur)
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
            
        $repositoryCours = $gestionnaireEntite -> getRepository('CoursBundle:Cours');
        $repositoryParticiper = $gestionnaireEntite->getRepository('CoursBundle:Participer');
            
        $formation = $requeteUtilisateur->get('formation');
        $anneeEtude = $requeteUtilisateur->get('anneeEtude');
        $module = $requeteUtilisateur->get('module');
        $date = $requeteUtilisateur->get('date');
        $tri = $requeteUtilisateur->get('tri');
        $prix = $requeteUtilisateur->get('prix');
        $deplacement = $requeteUtilisateur->get('deplacement');
        
        
     //   switch ($deplacement)
       // {
         //   case "accueille": 
           //     $v
    //    }
       
        $user = $this -> getUser();
        
        $liste_cours = $repositoryCours->getCours($formation, $anneeEtude, $module, $date, $tri, $deplacement);
        
        $countCours = count($liste_cours);
    
        return $this->render('CoursBundle:Cours:resultatRC.html.twig',
                            array('liste_cours' => $liste_cours,
                            'user' => $user, 
                            'countCours' => $countCours,
                            'f' => $formation,
                            'm' => $module,
                            'a' => $anneeEtude,
                            'd' => $date));
    }
     
    
    //Affichage des détails de l'annonce selectionnée
    public function annonceAction($id)
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
        
        //recuperation repository Cours
        $repositoryCours = $gestionnaireEntite -> getRepository('CoursBundle:Cours');
        $cours = $repositoryCours -> findOneBy(array('id'=>$id));
        
        $repositoryDiscussion = $gestionnaireEntite -> getRepository('CoursBundle:Discussion');
        $liste_coursD = $repositoryDiscussion -> findBy(array('coursD' => $cours->getId()));
        
        //Recuperation valeur à afficher
        $prix = $cours->getPrix();
        $formation = $cours->getFormations()->getNom();
        $anneeEtude = $cours->getAnneeEtudes()->getNom();
        $module = $cours->getModules()->getNom();
        $commentaire = $cours->getCommentaire();
        $date = $cours->getDate();
        
        //recuperation repository Participer
        $repositoryParticiper = $gestionnaireEntite -> getRepository('CoursBundle:Participer');
        $participer = $repositoryParticiper -> findOneBy(array( 'coursP' => $cours->getId()));
        
        //recuperation repository User
        $repositoryUser = $gestionnaireEntite -> getRepository('UserBundle:User');
        $formateur = $repositoryUser -> findOneBy(array('id'=>$cours->getFormateurs()));
        
        $user = $this -> getUser();

        return $this->render('CoursBundle:Cours:annonce.html.twig',
                            array('cours' => $cours,
                                  'formateur' => $formateur,
                                  'participer' => $participer,
                                  'user' => $user,
                                  'liste_coursD' => $liste_coursD));
    }
    
    // Supprimer un cours lorsque l'utilisateur est un formateur
    public function supprimerCoursAction($id)
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
        
        $user = $this->getUser();
        
        //Recuperation repository Cours
        $repositoryCours = $gestionnaireEntite -> getRepository('CoursBundle:Cours');
            
        //Recuperation valeur à afficher
        $cours = $repositoryCours -> find($id);
        $formateurs = $cours->getFormateurs();
        
        //recuperation repository Participer
        $repositoryParticiper = $gestionnaireEntite -> getRepository('CoursBundle:Participer');
        $participer = $repositoryParticiper -> findOneBy(array('coursP'=>$cours->getId()));
        
        //recuperation repository Discussion
        $repositoryDiscussion = $gestionnaireEntite -> getRepository('CoursBundle:Discussion');
        $discussions = $repositoryDiscussion -> findBy(array('coursD'=>$cours->getId()));
        
        if($formateurs == $user)
        {
            foreach ($discussions as $discussion)
            {
                $gestionnaireEntite->remove($discussion);
            }
            $gestionnaireEntite->remove($participer);
            $gestionnaireEntite->remove($cours);
            $gestionnaireEntite->flush();
        }
        
        $this->get('session')->getFlashBag()->add('success', 'Le cours a bien été supprimé !');
        
        /*
        A DECOMMENTER POUR LES ENVOIS DE MAILS
        //Creation et envoie d'un mail de confirmation
        $message = \Swift_Message::newInstance()
        ->setSubject('Confirmation Suppression')
        ->setFrom('adresseenvoyeur')
        ->setTo('adressedestinataire')
        ->setCharset('utf-8')
        ->setContentType('text/html')
        ->setBody('Votre cours a bien été supprimé'));
        $this->get('mailer')->send($message);*/
        
        return $this->redirect($this->generateUrl('proposerCours'));
    }
    
    //Reserver un cours poster par un formateur
    public function reserverCoursAction($id)
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
        
        //recuperation repository Cours
        $repositoryCours = $gestionnaireEntite -> getRepository('CoursBundle:Cours');
        $cours = $repositoryCours -> find($id);
        
        //Recuperation repository Participer
        $repositoryParticiper = $gestionnaireEntite -> getRepository('CoursBundle:Participer');
        $participer = $repositoryParticiper -> findOneBy(array('coursP'=>$cours->getId()));
        
        /*
        $user = $this->getUser()->getId();
        $repositoryUser = $gestionnaireEntite -> getRepository('UserBundle:User');
        $userString = $repositoryUser -> findOneBy(array('id' => $user));*/
        
        $userConnected = $this->getUser();
        
        if(($participer->getEtatCours()) == 0)
        {
            //Association des valeurs à l'objet Cours
            $participer->setEtatCours(1);
            $participer->setUtilisateurP($userConnected);
            $cours->setEtatReservation(1);
            
            //Enregistrement en BD
            $gestionnaireEntite->persist($participer);
            $gestionnaireEntite->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'La demande de réservation a bien été envoyé !');
            /*
        A DECOMMENTER POUR LES ENVOIS DE MAILS
        //Creation et envoie d'un mail de confirmation
        $message = \Swift_Message::newInstance()
        ->setSubject('Confirmation Reservation')
        ->setFrom('adresseenvoyeur')
        ->setTo('adressedestinataire')
        ->setCharset('utf-8')
        ->setContentType('text/html')
        ->setBody('Votre reservation de cours a bien été prise en compte'));
        $this->get('mailer')->send($message);*/
        
        }
        return $this->redirect($this->generateUrl('rechercherCours_resultat_annonce', array('id' => $cours->getId())));
    }
    
    //Annuler la reservation d'un cours reserve precedement
    public function annulerReservationCoursAction( $id)
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
        
        //recuperation repository Cours
        $repositoryCours = $gestionnaireEntite -> getRepository('CoursBundle:Cours');
        $cours = $repositoryCours -> find($id);
        
        //Recuperation repository Participer
        $repositoryParticiper = $gestionnaireEntite -> getRepository('CoursBundle:Participer');
        $participer = $repositoryParticiper -> findOneBy(array('coursP'=>$cours->getId()));
        
        //Recuperation de l'utilisateur connecté
        $userConnected = $this->getUser();
        
        //Recuperation de l'utilisateur participant déjà au cours
        $eleve = $participer->getUtilisateurP();
        
        //On verifie que l'utilisateur connecté est bien celui qui a auparavant réserver le cours
        if($userConnected == $eleve)
        {
        
            //Association des valeurs à l'objet Cours
            $participer->setEtatCours(0);
            $participer->setUtilisateurP(null);
            $cours->setEtatReservation(0);
            
            //Enregistrement en BD
            $gestionnaireEntite->persist($participer);
            $gestionnaireEntite->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'La réservation à ce cours a bien été annulé !');
            
            /*
        A DECOMMENTER POUR LES ENVOIS DE MAILS
        //Creation et envoie d'un mail de confirmation
        $message = \Swift_Message::newInstance()
        ->setSubject('Confirmation Annulation')
        ->setFrom('adresseenvoyeur')
        ->setTo('adressedestinataire')
        ->setCharset('utf-8')
        ->setContentType('text/html')
        ->setBody('Votre reservation de cours a bien été annulé'));
        $this->get('mailer')->send($message);*/
        
        }
        
        return $this->redirect($this->generateUrl('rechercherCours_resultat_annonce', array('id' => $cours->getId())));
        
    }
    
    public function accepterDemandeReservationCoursAction($id)
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
        
        //recuperation repository Cours
        $repositoryCours = $gestionnaireEntite -> getRepository('CoursBundle:Cours');
        $cours = $repositoryCours -> find($id);
        
        //Recuperation repository Participer
        $repositoryParticiper = $gestionnaireEntite -> getRepository('CoursBundle:Participer');
        $participer = $repositoryParticiper -> findOneBy(array('coursP'=>$cours->getId()));
        
        //Recuperation de l'id de l'utilisateur connecté
        $userConnected = $this->getUser();
        
        //Recuperation de l'utilisateur qui a crée le cours
        $formateur = $cours -> getFormateurs();
        
        //On verifie que l'utilisateur connecté est bien celui qui a auparavant proposé le cours
        if($userConnected == $formateur)
        {
            //Association des valeurs à l'objet Cours
            $participer->setEtatCours(2);
            $cours->setEtatReservation(2);
            
            //Enregistrement en BD
            $gestionnaireEntite->persist($participer);
            $gestionnaireEntite->flush();
        }
        $this->get('session')->getFlashBag()->add('info', 'Vous venez d`accepter la demande de réservation !');
        /*
        A DECOMMENTER POUR LES ENVOIS DE MAILS
        //Creation et envoie d'un mail de confirmation
        $message = \Swift_Message::newInstance()
        ->setSubject('Confirmation Acceptation')
        ->setFrom('adresseenvoyeur')
        ->setTo('adressedestinataire')
        ->setCharset('utf-8')
        ->setContentType('text/html')
        ->setBody('Votre acceptation d'un eleve à votre cours a bien été prise en compte'));
        $this->get('mailer')->send($message);*/
        
        return $this->redirect($this->generateUrl('rechercherCours_resultat_annonce', array('id' => $cours->getId())));
    }
    
    public function refuserDemandeReservationCoursAction($id)
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
        
        //recuperation repository Cours
        $repositoryCours = $gestionnaireEntite -> getRepository('CoursBundle:Cours');
        $cours = $repositoryCours -> find($id);
        
        //Recuperation repository Participer
        $repositoryParticiper = $gestionnaireEntite -> getRepository('CoursBundle:Participer');
        $participer = $repositoryParticiper -> findOneBy(array('coursP'=>$cours->getId()));
        
        //Recuperation de l'id de l'utilisateur connecté
        $userConnected = $this->getUser();
        
        //Recuperation de l'utilisateur qui a crée le cours
        $formateur = $cours -> getFormateurs();
        
        //On verifie que l'utilisateur connecté est bien celui qui a auparavant proposé le cours
        if($userConnected == $formateur)
        {
            $etatCours = $participer->getEtatCours();
            
            //On vérifie que le cours est au préalable réservé par quelqu'un
            if($etatCours == 1)
            {
                //Association des valeurs à l'objet Cours
                $participer->setEtatCours(0);
                $participer->setUtilisateurP(null);
                $cours->setEtatReservation(0);
                
                //Enregistrement en BD
                $gestionnaireEntite->persist($participer);
                $gestionnaireEntite->flush();
            }
        }
        
        $this->get('session')->getFlashBag()->add('info', 'Vous venez de refuser la demande de réservation !');
        
        /*
        A DECOMMENTER POUR LES ENVOIS DE MAILS
        //Creation et envoie d'un mail de confirmation
        $message = \Swift_Message::newInstance()
        ->setSubject('Confirmation Refus')
        ->setFrom('adresseenvoyeur')
        ->setTo('adressedestinataire')
        ->setCharset('utf-8')
        ->setContentType('text/html')
        ->setBody('Votre refus d'un eleve à votre cours a bien été prise en compte'));
        $this->get('mailer')->send($message);*/
        
        return $this->redirect($this->generateUrl('rechercherCours_resultat_annonce', array('id' => $cours->getId())));
    }
    
    
    public function poserQuestionAction($id, Request $requeteUtilisateur)
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
        
        //recuperation repository Cours
        $repositoryCours = $gestionnaireEntite -> getRepository('CoursBundle:Cours');
        $cours = $repositoryCours -> find($id);
        
        //Creation de l'objet Discussion
        $discussion = new Discussion();
        
        //Recuperation des données envoyés par le formulaire
        $message = $requeteUtilisateur->get('message');
        
        //Recuperation de l'utilisateur connecté
        $user = $this->getUser();
        
        //Attribution des valeurs à l'objet Discussion
        $discussion->setMessage($message);
        $discussion->setCoursD($cours);
        $discussion->setUtilisateurD($user);
        
        //Enregistrement en BD
        $gestionnaireEntite->persist($discussion);
        $gestionnaireEntite->flush();
        
        return $this->redirect($this->generateUrl('rechercherCours_resultat_annonce', array('id' => $cours->getId())));
    }
    
}

