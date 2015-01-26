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

    /* Top menu */
    public function topMenuAction()
    {
        $response = new Response();

        $searchService = $this->getRepository()->getSearchService();

        // See comtocde.yml to see params
        $menuNode = $this->container->getParameter("comtocode.menu.main_node");
        $menuContentTypeIdentifier = $this->container->getParameter("comtocode.menu.contentTypeIdentifier");

        $response->setSharedMaxAge(86400);
        $response->headers->set("X-Location-Id", $menuNode);

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
    public function footerAction()
    {
        $response = new Response();

        // See comtocde.yml to see params
        $mainNode = $this->container->getParameter("comtocode.menu.main_node");

        $response->setSharedMaxAge(86400);
        $response->headers->set("X-Location-Id", $mainNode);


        $searchService = $this->getRepository()->getSearchService();
        $criterionMain = array(
            new Criterion\LocationId( $mainNode )
        );

        $queryMain = new LocationQuery();
        $queryMain->criterion = new Criterion\LogicalAnd( $criterionMain  );
        $mainContent = $searchService->findContent($queryMain);



        return $this->render(
            'ComtoCodeWebsiteBundle:global:footer.html.twig',
            array( 'mainContent' => $mainContent->searchHits),
            $response
        );
    }
}