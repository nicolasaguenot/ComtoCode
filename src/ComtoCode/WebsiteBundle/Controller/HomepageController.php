<?php

namespace ComtoCode\WebsiteBundle\Controller;




use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use Symfony\Component\HttpFoundation\Response;
use eZ\Bundle\EzPublishCoreBundle\Controller;

use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator;



class HomepageController extends Controller
{



    public function listSkillsProjectAction( $locationId, $viewType, $layout = false, array $params = []){


        $response = new Response();
        $response->setSharedMaxAge(86400);
        $response->headers->set("X-Location-Id", $locationId );


        $searchService = $this->getRepository()->getSearchService();

        // See comtocde.yml to see params
        $skillsParentNode = $this->container->getParameter("comtocode.node.skills");
        $contentTypeIdentifier = $this->container->getParameter("comtocode.homepage.skills_contenttype");


        // Load Skills
        $criterionSkills = array(
            new Criterion\ParentLocationId( $locationId ),
            new Criterion\ContentTypeIdentifier( $contentTypeIdentifier ),
            new Criterion\Visibility( Criterion\Visibility::VISIBLE )
        );

        $querySkills = new LocationQuery();
        $querySkills->criterion = new Criterion\LogicalAnd( $criterionSkills );
        $listSkills = $searchService->findLocations($querySkills);


       return $this->get( 'ez_content' )->viewLocation(
            $locationId, $viewType, $layout,
            [
                'skills' => $listSkills->searchHits,
                'skillsParentNode' => $skillsParentNode
            ] + $params
        );
    }
    public function urlRedirectAction( $urlRedirect = "")
    {

        return $this->redirect($this->generateUrl($urlRedirect));
    }

}

?>