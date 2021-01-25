<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Favoris;
use App\Repository\CategoryRepository;
use App\Repository\RessourcesRepository;
use App\Repository\FavorisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class NewsFeedController extends AbstractController
{
    /**
     * @Route("/", name="news_feed")
     */
    public function index(RessourcesRepository $ressourcesRepository, Security $security, FavorisRepository $favorisRepository, CategoryRepository $categoryRepository): Response
    {
        $newsFeed = $ressourcesRepository->getAllNewsFeed();
        $category = $categoryRepository->findAll();
        $userCo = $security->getUser();
        $favoList = $favorisRepository->findByUser($userCo);

        return $this->render('news_feed/index.html.twig', [
            'news' => $newsFeed,
            'userCo' => $userCo,
            'favoList' => $favoList,
            'categories' => $category
        ]);
    }

    /**
     * @Route("/comments/{id}", name="add_comment")
     */
    public function addComments(Request $request, RessourcesRepository $ressourcesRepository, EntityManagerInterface $entityManager, Security $security, $id)
    {

        if ($request->request->get('comments') !== [] && $request->request->get('comments') !== null && $request->request->get('comments') !== "") {
            $comments = new Comments();
            $comments->setUser($security->getUser())
                ->setContenu($request->request->get('comments'))
                ->setRessource($ressourcesRepository->findOneBy(["id" => $id]))
                ->setCreatedAt(new \DateTime('now', new \DateTimeZone("Europe/Paris")));
            $entityManager->persist($comments);
            $entityManager->flush();
        } else {
            return $this->redirectToRoute('news_feed');
        }

        return $this->redirectToRoute('news_feed');
    }


    /**
     * @Route("/favoris/{id}", name="add_favoris", methods={"GET", "POST"})
     */
    public function addFavoris(Request $request, Security $security, RessourcesRepository $ressourcesRepository, FavorisRepository $favorisRepository, $id): Response
    {
        $favori = new Favoris();
        $favori->setUser($security->getUser())
            ->setRessource($ressourcesRepository->findOneBy(["id" => $id]));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($favori);
        $entityManager->flush();
        return $this->redirectToRoute('news_feed');

        //return $this->redirectToRoute('news_feed');

    }

    /**
     * @Route("/delete/favoris/{id}", name="remove_favoris", methods={"GET", "DELETE"})
     */
    public function removeFavoris(Request $request, RessourcesRepository $ressourcesRepository, FavorisRepository $favorisRepository, Favoris $favori, $id): Response
    {
        $favoris = $favorisRepository->findOneBy(["id" => $id]);
        //dd($favoris->getRessource()->getFavoris());
        //$ressource->removeFavori($favori);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($favori);
        $entityManager->flush();


        return $this->redirectToRoute('news_feed');
    }

    /**
     * @Route("/user/favoris", name="get_favoris_user", methods={"GET"})
     */
    public function getAllFavorisOfUser(FavorisRepository $favorisRepository, Security $security)
    {

        $favoris = $favorisRepository->getFavorisAboutUser($security->getUser());
        $userCo = $security->getUser();
        $favoList = $favorisRepository->findByUser($userCo);


        return $this->render('news_feed/favoris.html.twig', [
            'news' => $favoris,
            'userCo' => $userCo,
            'favoList' => $favoList,
        ]);
    }




    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search", name="ajax_search", methods={"POST"})
     */
    public function searchAction(Request $request, RessourcesRepository $ressourcesRepository)
    {
        // reception du contenu de la searchbar
        $requestData = json_decode($request->getContent(), true);
        //récupère la valeur de l'objet envoyé
        $searchData = $requestData["searchContent"];
        //rechere dans la base les ressources correspondant à la valeur envoyé
        $dbData = $ressourcesRepository->findRessourcesByString($searchData);
        //formater ces données en Json pour les renvoyées 
        $arrayDbData = [];

        foreach ($dbData as $data) {
            $arrayDbData[] = array(
                'id' => $data->getId(),
                'title' => $data->getTitle(),
                'user' => [
                    'id' => $data->getUser()->getId(),
                    'lastname' => $data->getUser()->getLastName(),
                    'firstname' => $data->getUser()->getFirstName()

                ],
                'article' => $data->getArticle() ? $data->getArticle()->getId() : null,
                'evenement' => $data->getEvenement() ? $data->getEvenement()->getId() : null,
                'information' => $data->getInformation() ? $data->getInformation()->getId() : null,
                'photo' => $data->getPhoto() ? $data->getPhoto()->getId() : null,
                'video' => $data->getVideo() ? $data->getVideo()->getId() : null,
                'date' => $data->getUpdatedAt() ?  $data->getUpdatedAt() :  $data->getCreatedAt()


            );
        }
        // on va les envoyer
        $response = new Response(json_encode($arrayDbData));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function getRealRessources($ressources)
    {

        foreach ($ressources as $ressource) {
            $realressources[$ressource->getId()] = $ressource->getTitle();
        }

        return $realressources;
    }


    /**
     * @Route("/ressources/filtre", name="filtre")
     */
    public function ressourceFilter(RessourcesRepository $ressources, Request $request)
    {
        // On récupere les données envoyées de JS
        $requete = json_decode($request->getContent(), true);

        // On récupère les filtres et tris
        $catId = $requete && $requete['idCheck'] ? $requete['idCheck'] : null;
        $tris = $requete && $requete['idTri'] ? $requete['idTri'] : null;

        // Si il y'a que des filtres
        if ($catId && $catId !== [] && $tris === null || $tris === []) {

            $ressourcesFilters = $ressources->getRessourcesByCat($catId);
        } // Si il y'a que des tris

        elseif ($catId === [] || $catId === null && $tris !== null && $tris !== []) {

            if (in_array('decroissant', $tris)) {
                $ressourcesFilters = $ressources->getRessourcesByDesc();
            } else {
                $ressourcesFilters = $ressources->getRessourcesByAsc();
            }
        } //Si il y'a des filtres et tris

        elseif ($tris !== null && $tris !== [] && $catId && $catId !== []) {

            if (in_array('decroissant', $tris)) {
                $ressourcesFilters = $ressources->getRessourcesByTriAndFilterDesc($catId);
            } else {
                $ressourcesFilters = $ressources->getRessourcesByTriAndFilterAsc($catId);
            }
        }
        //Si il y'a rien
        else {
            $ressourcesFilters = $ressources->findAll();
        };

        //
        $ressource = [];
        foreach ($ressourcesFilters as $ressourceFilter) {
            $ressource[] = array(
                'id' => $ressourceFilter->getId(),
                'user' => [
                    "id" => $ressourceFilter->getUser()->getId(),
                    "lastName" => $ressourceFilter->getUser()->getLastName(),
                    "firstName" => $ressourceFilter->getUser()->getFirstName()
                ],
                'article' => $ressourceFilter->getArticle() ? [
                    'id' => $ressourceFilter->getArticle()->getId(),
                    'titre' =>  $ressourceFilter->getArticle()->getTitre(),
                    'description' => $ressourceFilter->getArticle()->getDescription(),
                    'video' =>  $ressourceFilter->getArticle()->getVideo(),
                    'photo' =>  $ressourceFilter->getArticle()->getPhoto(),
                ] :  null,
                'evenement' => $ressourceFilter->getEvenement() ? [
                    'id' => $ressourceFilter->getEvenement()->getId(),
                    'titre' =>  $ressourceFilter->getEvenement()->getTitre(),
                    'description' => $ressourceFilter->getEvenement()->getDescription(),
                    'dateDebut' =>  $ressourceFilter->getEvenement()->getDateDebut(),
                    'dateFin' =>  $ressourceFilter->getEvenement()->getDateFin(),
                    'heureDebut' =>  $ressourceFilter->getEvenement()->getHeureDebut(),
                    'heureFin' =>  $ressourceFilter->getEvenement()->getHeureFin()
                ] : null,
                'information' => $ressourceFilter->getInformation() ? [
                    'id' => $ressourceFilter->getInformation()->getId(),
                    'titre' =>  $ressourceFilter->getInformation()->getTitre(),
                    'description' => $ressourceFilter->getInformation()->getDescription(),
                    'contenu' =>  $ressourceFilter->getInformation()->getContenu(),
                ] : null,
                'photo' => $ressourceFilter->getPhoto() ? [
                    'id' => $ressourceFilter->getPhoto()->getId(),
                    'titre' =>  $ressourceFilter->getPhoto()->getTitre(),
                    'image' => $ressourceFilter->getPhoto()->getImage(),
                    'description' => $ressourceFilter->getPhoto()->getDescription(),
                ] : null,

                'video' => $ressourceFilter->getVideo() ? [
                    'id' => $ressourceFilter->getVideo()->getId(),
                    'titre' =>  $ressourceFilter->getVideo()->getTitre(),
                    'video' => $ressourceFilter->getVideo()->getVideo(),
                    'description' => $ressourceFilter->getVideo()->getDescription(),
                ] : null,
                "datePubli" => $ressourceFilter->getUpdatedAt() ?  $ressourceFilter->getUpdatedAt() :  $ressourceFilter->getCreatedAt()
            );
        }
        $response = new Response(json_encode($ressource));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
