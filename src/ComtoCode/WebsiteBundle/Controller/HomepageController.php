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
        $confNode = $this->container->getParameter("comtocode.node.conf");
        $skillsParentNode = $this->container->getParameter("comtocode.node.skills");
        $refsParentNode = $this->container->getParameter("comtocode.node.refs");

        $skillsContentTypeIdentifier = $this->container->getParameter("comtocode.homepage.skills_contenttype");
        $refsContentTypeIdentifier = $this->container->getParameter("comtocode.homepage.refs_contenttype");

        $maxRefs = $this->container->getParameter("comtocode.homepage.max_refs_number");



        // Load Skills on direct children
        $criterionSkills = array(
            new Criterion\ParentLocationId( $locationId ),
            new Criterion\ContentTypeIdentifier( $skillsContentTypeIdentifier ),
            new Criterion\Visibility( Criterion\Visibility::VISIBLE )
        );

        $querySkills = new LocationQuery();
        $querySkills->criterion = new Criterion\LogicalAnd( $criterionSkills );
        $listSkills = $searchService->findLocations($querySkills);

        // Load $maxRefs latest Refs
        $criterionRefs = array(
            new Criterion\ParentLocationId( $refsParentNode ),
            new Criterion\ContentTypeIdentifier( $refsContentTypeIdentifier ),
            new Criterion\Visibility( Criterion\Visibility::VISIBLE ),

        );
        $queryRefs = new LocationQuery();
        $queryRefs->criterion = new Criterion\LogicalAnd( $criterionRefs );
        $queryRefs->limit = $maxRefs;
        $listRefs = $searchService->findContent($queryRefs);

        // Load conf params
        $criterionConf = array(
            new Criterion\LocationId( $confNode )

        );
        $queryConf = new LocationQuery();
        $queryConf->criterion = new Criterion\LogicalAnd( $criterionConf );
        $confContent = $searchService->findContent($queryConf);

       return $this->get( 'ez_content' )->viewLocation(
            $locationId, $viewType, $layout,
            [
                'skills' => $listSkills->searchHits,
                'skillsParentNode' => $skillsParentNode,
                'refs' => $listRefs->searchHits,
                'refsParentNode' => $refsParentNode,
                'confNode' => $confContent
            ] + $params
        );
    }
    public function urlRedirectAction( $urlRedirect = "")
    {

        return $this->redirect($this->generateUrl($urlRedirect));
    }

}

?>