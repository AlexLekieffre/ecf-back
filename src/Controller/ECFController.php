<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\DBAL\Query;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ECFController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $produits = $doctrine->getRepository(Produit::class)->findAll();
        
        return $this->render('ecf/index.html.twig', [
            'produits' => $produits,
        ]);
    }

   /*  #[Route('/produit/{id}', name : 'produit')]
    public function showProduit($id , ManagerRegistry $doctrine):Response
    {
        $produit = $doctrine->getRepository(Produit::class)->find(
           ['id' => $id]
        );
        return $this->render('ecf/produit.html.twig',['produit' => $produit,'id'=>$id]);
    }
 */
    
    #[Route('/produit/{id}', name : 'ajouterpanier', requirements: ['id'=>"\d+",'slug'=>'.{1,}'])]
    #[ParamConverter('Produit',class: Produit::class)]
    public function produit(Produit $produit, Request $request, SessionInterface $session):Response
    {
        if($request->request->get('ajout')){
// Pas eu le temps de finir désolé. légé manque de temps
            if($session->get('panier')== null){
            $session->set('panier',1);
            $session->set('panierId',$request->request->get('produit'));
            $session->set('panierQuantite',$request->request->get('quantite'));
            }else{
            $session->set('panier',+1);
            if($session.panierID == $request->request->get('produit')){
                //ne fait rien 
            }else{
            $session->set('panierId',$request->request->get('produit'));
            $session->set('panierQuantite',$request->request->get('quantite'));}
            }   
            dump($session);
        }
        return $this->render('ecf/produit.html.twig',[
            'produit' => $produit,
        ]);
    }

    #[Route('/panier', name:'panier')]
    public function panier()
    {
        
      
        return $this->render("ecf/panier.html.twig");
    }
    

    

    


    
    public function menu()
    {
        $listMenu = array(
            array('title'=>'SHOP','texte'=>'Accueil','url'=>$this->generateUrl('homepage',[],UrlGeneratorInterface::ABSOLUTE_URL)),
            array('title'=>'Panier','texte'=>'Panier','url'=>"/panier")
        );
        
        return $this->render("parts/menu.html.twig",array(
            'listmenu'=>$listMenu
        ));
    }
    
    
    
}