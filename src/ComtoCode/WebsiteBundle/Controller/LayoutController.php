<?php

namespace ComtoCode\WebsiteBundle\Controller;


use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use Symfony\Component\HttpFoundation\Response;
use eZ\Bundle\EzPublishCoreBundle\Controller;

use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator;



class LayoutController extends Controller
{


    public function topMenuAction()
    {
        $response = new Response();
        // Mise en cache de la réponse pendant 3600 secondes
        $response->setSharedMaxAge(86400);
        // La ligne suivante permet de faire expirer ce cache si on modifie le noeud numéro 2 (principe de l’attribut subtree_expiry sur les anciens cache-blocks)
        $response->headers->set("X-Location-Id", 2);

        $searchService = $this->getRepository()->getSearchService();

        // See comtocde.yml to see params
        $menuNode = $this->container->getParameter("comtocode.menu.main_node");
        $menuContentTypeIdentifier = $this->container->getParameter("comtocode.menu.contentTypeIdentifier");

        $criterionMenu = array(
            new Criterion\ParentLocationId( $menuNode ),
            new Criterion\ContentTypeIdentifier( $menuContentTypeIdentifier ),
            new Criterion\Visibility( Criterion\Visibility::VISIBLE )
        );

        $queryMenu = new LocationQuery();
        $queryMenu->criterion = new Criterion\LogicalAnd( $criterionMenu );
        $queryMenu->sortClauses = array(
            new SortClause\Location\Priority( LocationQuery::SORT_ASC )
        );
        $menuList = $searchService->findLocations($queryMenu);


        return $this->render(
            'ComtoCodeWebsiteBundle:global:top_menu.html.twig',
            array( 'menulist' => $menuList->searchHits),
            $response
        );


    }
}