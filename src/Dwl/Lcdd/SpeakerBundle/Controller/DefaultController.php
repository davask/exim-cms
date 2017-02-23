<?php

namespace Dwl\Lcdd\SpeakerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    private function postConstruct() {

        $this->doctrine = $this->getDoctrine();
        $this->em = $this->doctrine->getEntityManager();
        $this->repo = $this->em->getRepository('DwlLcddSpeakerBundle:Speaker');

        $this->request = $this->getRequest();
        $this->_format = $this->request->attributes->get('_format', $this->getParameter('exim.theme.front.format'));

        $this->translator = $this->get('translator');

    }

    /**
     * @Rest\View
     */
    private function viewDatasRender($viewDatas, $_template = null) {

        if($this->_format == 'json' || is_null($_template)) {
            return $viewDatas;
        } else {
            return $this->render($_template, $viewDatas);
        }

    }

    /**
     * @Rest\View
     *
     * @ApiDoc(
     *  description="Return all speakers",
     *  requirements={
     *      {"name"="_method", "requirement"="GET"},
     *      {"name"="_locale", "requirement"="fr"},
     *      {"name"="_format", "requirement"="html|json"}
     *  },
     *  output={"class"="Application\Sonata\UserBundle\Entity\User", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *  }
     * )

     */
    public function allAction()
    {
        $this->postConstruct();

        $speakerGroupId = 1;

        $viewDatas = array(
            'speakers' => $this->repo->findUsersByGroupId($speakerGroupId),
        );

        return $this->viewDatasRender($viewDatas, 'DwlLcddSpeakerBundle:Speaker:all.html.twig');

    }

    /**
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function getAction()
    {
        $this->postConstruct();

        $username = $this->request->attributes->get('username', 'me');

        $user = $this->getUser();

        if($username == 'me') {
            $speaker = $user;
        } else {
            $speaker = $this->repo->findOneByUsernameCanonical($username);
        }

        if(empty($speaker)) {
            return $this->redirect($this->generateUrl('lcdd_speaker_all'));
        }

        // Retrieve information from the current user (by its IP address)
        $result = $this->container
            ->get('bazinga_geocoder.geocoder')
            ->using('google_maps')
            ->geocode('Kirchenstrasse 9, Erlangen, Allemagne');

        // Find the 5 nearest objects (15km) from the current user.
        $address = $result->first();
        // $objects = ObjectQuery::create()
        //     ->filterByDistanceFrom($address->getLatitude(), $address->getLongitude(), 15)
        //     ->limit(5)
        //     ->find();

        dump(
            $address
            // $objects
        );


        $site = $this->get('sonata.page.manager.site')->findOneBy(array('id'=>1));
        $page = $this->get('sonata.page.cms_manager_selector')->retrieve()
            ->getPageByRouteName($site,$this->get('request')->get('_route'));

        $viewDatas = array(
            'speaker' => $speaker,
            'page' => $page,
            'blocks' => $this->container->getParameter('lcdd.speaker.configuration.speaker_blocks'),
        );

        return $this->viewDatasRender($viewDatas, 'DwlLcddSpeakerBundle:Speaker:get.html.twig');
    }

}
