<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use CoursBundle\Entity\Cours;
use CoursBundle\Entity\Participer;
use CoursBundle\Entity\Discussion;
use CoursBundle\Entity\Avis;

class UserController extends Controller
{
    public function monEspaceAction()
    {
        $user = $this -> getUser();
        
        // On récupère l'EntityManager
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        //recuperation repository Cours
        $repositoryCours = $gestionnaireEntite -> getRepository('CoursBundle:Cours');
        $liste_cours_formateur = $repositoryCours -> findBy(array('formateurs'=>$user));
        
        //recuperation repository Participer
        $repositoryParticiper = $gestionnaireEntite -> getRepository('CoursBundle:Participer');
        $liste_cours_eleve = $repositoryParticiper -> findBy(array('utilisateurP' => $user));
       // $liste_participer = $repositoryParticiper -> findBy(array('coursP'=>$liste_cours,
       //                                                             'etatCours' => 0));
                                                                    
        $participations_0 = null;
        foreach($liste_cours_formateur as $c) 
        {
            $participations_0[] = $repositoryParticiper->findOneBy(array(
            'etatCours' => 0,
            'coursP' => $c));
        }
        
        $participations_1 = null;
        foreach($liste_cours_formateur as $c) 
        {
            $participations_1[] = $repositoryParticiper->findOneBy(array(
            'etatCours' => 1,
            'coursP' => $c));
        }
        
        $participations_2 = null;
        foreach($liste_cours_formateur as $c) 
        {
            $participations_2[] = $repositoryParticiper->findOneBy(array(
            'etatCours' => 2,
            'coursP' => $c));
        }
        
        return $this->render('UserBundle:User:monEspace.html.twig', array('liste_cours_eleve' => $liste_cours_eleve,
                                                                          'participations_0' => $participations_0,
                                                                          'participations_1' => $participations_1,
                                                                          'participations_2' => $participations_2));
    }
    
    public function mesAvisAction()
    {
        $user = $this->getUser();
        
        // On récupère l'EntityManager
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // Récupération repository Cours
        $repositoryCours = $gestionnaireEntite -> getRepository('CoursBundle:Cours');
        
        // Récupération repository Participer
        $repositoryParticiper = $gestionnaireEntite -> getRepository('CoursBundle:Participer');
        
        // Récupération liste de cours qu'on doit afficher
        $liste_recup_cours = $repositoryParticiper->findBy(array('etatCours' => 3, 'utilisateurP' => $user));
        
        // Envoyer à la vue
        return $this->render('UserBundle:User:mesAvis.html.twig', array('liste_recup_cours' => $liste_recup_cours));
    }
    
    
    
    
    public function mesInfosAction()
    {
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        $repositoryUser = $gestionnaireEntite -> getRepository('UserBundle:User');
        $user = $repositoryUser->getUser();
        
        return $this->render('UserBundle:User:mesInfos.html.twig', array('user' => $user));
    }
    
    
    public function MPDeletecompteAction()
    {
        return $this->render('UserBundle:User:MPDeletecompte.html.twig');
    }
    
    public function mesCoursAction()
    {
        return $this->render('UserBundle:User:mesCours.html.twig');
    }

    public function ModifierAction(Request $request)
    {
        //recuperation des champs du formulaire
        $nom = $request->get('nom');
        $prenom = $request->get('prenom');
        $mobile = $request->get('mobile');
        $ville = $request->get('ville');
        $presentation = $request->get('presentation');
        
        //création de l'entité
        $user = $this->getUser();
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->getEmail();
        $user->setMobile($mobile);
        $user->setVille($ville);
        $user->setPresentation($presentation);

        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
    
        $em->persist($user);
        $em->flush();
    
        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }
    
    //CELUI LA
    public function donnerAvisAction()
    {
        
        /*
        //recuperation repository Cours
        $repositoryAvis = $gestionnaireEntite -> getRepository('CoursBundle:Avis');
        $cours = $repositoryAvis -> findBy(array('UtilisateurA'=>$user);
        */
        
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
    
        $em->persist($user);
        $em->flush();
        
    }
    public function supprimerCompteAction()
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
        
        $user = $this->getUser();
        
        //Recuperation repository Cours
        $repositoryCours = $gestionnaireEntite -> getRepository('CoursBundle:Cours');
        
        //Recuperation valeur à afficher
        $liste_cours = $repositoryCours -> findAll();
        
        //recuperation repository Participer
        $repositoryParticiper = $gestionnaireEntite -> getRepository('CoursBundle:Participer');
        
        //recuperation repository Discussion
        $repositoryDiscussion = $gestionnaireEntite -> getRepository('CoursBundle:Discussion');
        
        foreach ($liste_cours as $cours)
        {
            $formateurs = $cours->getFormateurs();
            
            $participer = $repositoryParticiper -> findOneBy(array('coursP'=>$cours->getId()));
            $eleve = $participer->getUtilisateurP();
            
            if($formateurs == $user)
            {
                $liste_discussions = $repositoryDiscussion -> findBy(array('coursD'=>$cours->getId()));
                foreach ($liste_discussions as $discussion)
                {
                    $gestionnaireEntite->remove($discussion);
                }
                $gestionnaireEntite->remove($participer);
                $gestionnaireEntite->remove($cours);
            }
            elseif ($eleve == $user) 
            {
                $liste_discussions = $repositoryDiscussion -> findBy(array('coursD'=>$cours->getId(),
                                                                        'utilisateurD' => $user));
                foreach ($liste_discussions as $discussion)
                {
                    $gestionnaireEntite->remove($discussion);
                }
                $participer->setEtatCours(0);
                $cours->setEtatReservation(0);
                $participer->setUtilisateurP(null);
            }
        }
        $gestionnaireEntite->remove($user);
        $gestionnaireEntite->flush();
        
        return $this->redirect($this->generateUrl('accueil'));
    }
    
    
    
    /*public function rechercherUtilisateurAction(Request $requeteUtilisateur)
    {
        $gestionnaireEntite = $this-> getDoctrine()-> getManager();
        
        //Recuperation repository User
        $repositoryUser = $gestionnaireEntite -> getRepository('UserBundle:User');
        $user = $repositoryUser -> findAll(array('coursP'=>$cours->getId()));
        
        $form = $requeteUtilisateur->getData();
        
        if($form->isSubmit())
        {
        
        //$urlRedirection = $this->generateUrl('rechercherUtilisateur', array('id' => ));
        
        //return $this->redirect($urlRedirection);
     
            return $this->redirect($this->generateUrl('rechercherUtilisateur' ,array('user'=>$user)));
        }
        //return $this->render('UserBundle:User:rechercherUtilisateur.html.twig');*/
        
        
    public function rechercherUtilisateurAction()
    {
        return $this->render('UserBundle:User:rechercherUtilisateur.html.twig');
    }
    
     public function lancerRechercherUtilisateurAction(Request $request)
     {
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        //Recuperation repository User
        $repositoryUser = $gestionnaireEntite->getRepository('UserBundle:User');
        
        $user = $request->get('utilisateur');
        
        $userSearch = $repositoryUser->getUser($user);
        
        return $this->render('UserBundle:User:resultatRechercheutilisateur.html.twig', array('userSearch' => $userSearch));
    }
    
    
    public function profilCompletAction ($id)
    {
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        $repositoryCours = $gestionnaireEntite->getRepository('CoursBundle:Cours');
        $repositoryUser = $gestionnaireEntite->getRepository('UserBundle:User');
        
        $liste_cours_formateur = $repositoryCours->getCoursFormateurDuUser($id);
        $liste_cours_eleve = $repositoryCours->getCoursEleveDuUser($id);
        $user = $repositoryUser->findOneById($id);
        
        return $this->render('UserBundle:User:profilComplet.html.twig', array('liste_cours_formateur' => $liste_cours_formateur, 'liste_cours_eleve' => $liste_cours_eleve, 'user' => $user));
    }
}
