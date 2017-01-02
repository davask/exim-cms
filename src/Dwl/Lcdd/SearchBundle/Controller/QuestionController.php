<?php

namespace Dwl\Lcdd\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Dwl\Lcdd\SearchBundle\Entity\Question;
use Dwl\Lcdd\SearchBundle\Form\QuestionType;

class QuestionController extends Controller
{
    private $doctrine;

    private $em;

    private $request;

    private $_format;

    private $translator;

    private function postConstruct() {

        $this->doctrine = $this->getDoctrine();
        $this->em = $this->doctrine->getEntityManager();
        $this->repo = $this->em->getRepository('DwlLcddSearchBundle:Question');

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
     *  description="Return all questions",
     *  requirements={
     *      {"name"="_method", "requirement"="GET"},
     *      {"name"="_locale", "requirement"="fr"},
     *      {"name"="_format", "requirement"="html|json"}
     *  },
     *  output={"class"="Dwl\Lcdd\SearchBundle\Entity\Question", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *  }
     * )

     */
    public function allAction()
    {
        $this->postConstruct();

        $viewDatas = array('questions' => $this->repo->findAll());

        return $this->viewDatasRender($viewDatas, 'DwlLcddSearchBundle:Question:all.html.twig');

    }

    /**
     * @Rest\View
     */
    public function getAction($slug){

        $this->postConstruct();

        $question = $this->repo->findOneBySlug($slug);

        dump($question);

        if (!$question instanceof Question) {
            throw new NotFoundHttpException('Question not found');
        }

        $this->container->get('sonata.seo.page')
            ->setTitle($question->getQuestion())
            ->addMeta('property', 'og:title', $question->getQuestion())
            ->addMeta('property', 'og:type', 'question')
            ->addMeta('property', 'og:url',  $this->generateUrl('dwl_lcdd_get_question', array(
                'slug'  => $question->getSlug()
            ), true))
        ;

        $viewDatas = array('question' => $question);

        return $this->viewDatasRender($viewDatas, 'DwlLcddSearchBundle:Question:get.html.twig');

    }

    /**
     * @Rest\View
     */
    public function SearchAction(){

        $this->postConstruct();

        $this->_format = $this->request->request->get('format', $this->_format);

        $viewDatas = array();
        $viewDatas['userQuestion'] = $this->request->request->get('question', '');
        $viewDatas['qs'] = $this->repo->findAll();
        $viewDatas['cs'] = $this->em->getRepository('ApplicationSonataClassificationBundle:Category')->findAll();
        $viewDatas['ts'] = $this->em->getRepository('ApplicationSonataClassificationBundle:Tag')->findAll();

        return $this->viewDatasRender($viewDatas, 'DwlLcddSearchBundle:Question:search.html.twig');

    }

    public function processForm(Question $question)
    {

        $this->postConstruct();

        // is it an Ajax request?
        $_format = $this->request->isXmlHttpRequest() ? 'json' : $this->request->attributes->get('_format', $this->getParameter('exim.theme.front.format'));

        $viewDatas = array();
        // what's the preferred language of the user?
        $viewDatas['language'] = $this->request->getLocale();
        $viewDatas['questionString'] = '';
        $viewDatas['isQualified'] = null;

        $viewDatas['success'] = false;
        $viewDatas['message'] = $this->translator->trans('_q._s.none', array(), 'DwlLcddSearchBundle');

        $form = $this->container->get( 'dwl.lcdd.block.search.form.question' );

        $form->handleRequest($this->request);

        if ($form->isValid()) {

            if(is_null($question->getId())) {
                $question = $form->getData();
            }

            if (!$this->repo->findOneByQuestion($question->getQuestion())) {

                $this->em->persist($question);
                $this->em->flush();

                $viewDatas['success'] = true;
                $viewDatas['message'] = $this->translator->trans('_q._s.thanks', array(), 'DwlLcddSearchBundle');
                $viewDatas['questionString'] = $question->getQuestion();
                $viewDatas['isQualified'] = $question->getQualified() ? $this->translator->trans('_g.yes', array(), 'DwlLcddSearchBundle') : $this->translator->trans('_g.no', array(), 'DwlLcddSearchBundle');

            } else {
                $viewDatas['message'] = $this->translator->trans('_q._s.already', array(), 'DwlLcddSearchBundle');
            }
        }

        return $viewDatas;
    }

    /**
     * @Rest\View
     */
    public function newAction()
    {
        return $this->viewDatasRender($this->processForm(new Question()));
    }

    public function editAction(Question $question)
    {
        return $this->viewDatasRender($this->processForm($question));
    }

    public function removeAction(Question $question)
    {
        $this->postConstruct();
        $this->em->remove($question);
        $this->em->flush();
    }
}
