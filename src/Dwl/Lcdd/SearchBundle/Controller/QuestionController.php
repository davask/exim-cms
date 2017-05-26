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
use Dwl\Lcdd\SearchBundle\Form\SearchQuestionType;
use Dwl\Lcdd\SearchBundle\Form\QuestionType;

use Application\Sonata\ClassificationBundle\Entity\Tag;

/**
 * Question controller.
 *
 */
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
        $this->repoCustomer = $this->em->getRepository('ApplicationSonataCustomerBundle:Customer');
        $this->repoSpeaker = $this->em->getRepository('DwlLcddSpeakerBundle:Speaker');

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

    // /**
    //  * Lists all Question entities.
    //  *
    //  */
    // public function indexAction()
    // {
    //     $em = $this->getDoctrine()->getManager();

    //     $entities = $em->getRepository('DwlLcddSearchBundle:Question')->findAll();

    //     return $this->render('DwlLcddSearchBundle:Question:index.html.twig', array(
    //         'entities' => $entities,
    //     ));
    // }


    /**
     * @Rest\View
     */
    public function getAction($slug){

        $this->postConstruct();

        $question = $this->repo->findOneBy(array('slug' => $slug, 'qualified' => true));

        $views = $question->getViews();
        $question->setViews($views+1);
        $this->em->persist($question);
        $this->em->flush();

        $site = $this->get('sonata.page.manager.site')->findOneBy(array('id'=>1));
        $page = $this->get('sonata.page.cms_manager_selector')->retrieve()
            ->getPageByRouteName($site,$this->get('request')->get('_route'));

        $viewDatas = array(
            'page' => $page,
            'blocks' => $this->container->getParameter('lcdd.speaker.configuration.speaker_blocks'),
        );


        if (!$question instanceof Question) {
            $viewDatas['error_message'] = 'Cette question n\'existe pas ou n\'a pas encore &eacute;t&eacute; valid&eacute;e !';
            return $this->viewDatasRender($viewDatas, 'EximTheme'.$this->getParameter('exim.theme.name').'FrontBundle:Errors:404.html.twig');
        }

        $viewDatas['question'] = $question;

        return $this->viewDatasRender($viewDatas, 'DwlLcddSearchBundle:Question:get.html.twig');

    }

    // /**
    //  * Finds and displays a Question entity.
    //  *
    //  */
    // public function showAction($id)
    // {
    //     $em = $this->getDoctrine()->getManager();

    //     $entity = $em->getRepository('DwlLcddSearchBundle:Question')->find($id);

    //     if (!$entity) {
    //         throw $this->createNotFoundException('Unable to find Question entity.');
    //     }

    //     $deleteForm = $this->createDeleteForm($id);

    //     return $this->render('DwlLcddSearchBundle:Question:show.html.twig', array(
    //         'entity'      => $entity,
    //         'delete_form' => $deleteForm->createView(),
    //     ));
    // }

    /**
     * @Rest\View
     */
    public function SearchAction(){

        $this->postConstruct();

        $this->_format = $this->request->request->get('format', $this->_format);

        $viewDatas = array();
        $viewDatas['userLogged'] = $this->getUser();
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

    // public function qualifyAction($id)
    // {
    //     $this->postConstruct();

    //     $question = $this->repo->findOneById($id);

    //     if (!$question) {
    //         throw $this->createNotFoundException('Unable to find Question entity.');
    //     }

    //     $questionQualified = new Question();
    //     $questionQualified->addUnqualifiedQuestion($question);

    //     $editForm = $this->createCreateForm($questionQualified);

    //     $site = $this->get('sonata.page.manager.site')->findOneBy(array('id'=>1));
    //     $page = $this->get('sonata.page.cms_manager_selector')->retrieve()
    //         ->getPageByRouteName($site,$this->get('request')->get('_route'));

    //     return $this->render('DwlLcddSearchBundle:Question:new.html.twig', array(
    //         'question'      => $questionQualified,
    //         'edit_form'   => $editForm->createView(),
    //         'page' => $page,
    //         'blocks' => $this->container->getParameter('lcdd.speaker.configuration.speaker_blocks'),
    //     ));

    // }

    /**
     * Creates a new Question entity.
     *
     */
    public function createAction(Request $request)
    {

        $this->postConstruct();

        $entity = new Question();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_edit', array('id' => $entity->getId())));
        }

        return $this->render('DwlLcddSearchBundle:Question:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Question entity.
     *
     * @param Question $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Question $entity)
    {

        $user = $this->container->get('security.context')->getToken()->getUser();
        $customer = $this->repoCustomer->findOneByUser($user);
        $speaker = $this->repoSpeaker->findOneByCustomer($customer);

        $entity->setSpeaker($speaker);

        $form = $this->createForm(new QuestionType($this->em), $entity, array(
            'action' => $this->generateUrl('question_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Qualifier'));

        return $form;
    }

    /**
     * @Rest\View
     */
    public function newAction()
    {
        return $this->viewDatasRender($this->processForm(new Question()));
    }

    // /**
    //  * Displays a form to create a new Question entity.
    //  *
    //  */
    // public function newAction()
    // {
    //     $this->postConstruct();

    //     $question = new Question();

    //     $editForm = $this->createCreateForm($question);

    //     $site = $this->get('sonata.page.manager.site')->findOneBy(array('id'=>1));
    //     $page = $this->get('sonata.page.cms_manager_selector')->retrieve()
    //         ->getPageByRouteName($site,$this->get('request')->get('_route'));


    //     $viewDatas = array(
    //         'question'      => $question,
    //         'edit_form'   => $editForm->createView(),
    //         'page' => $page,
    //         'blocks' => $this->container->getParameter('lcdd.speaker.configuration.speaker_blocks'),
    //     );

    //     return $this->viewDatasRender($viewDatas, 'DwlLcddSearchBundle:Question:new.html.twig');

    // }

    /**
    * Creates a form to edit a Question entity.
    *
    * @param Question $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Question $entity)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $customer = $this->repoCustomer->findOneByUser($user);
        $speaker = $this->repoSpeaker->findOneByCustomer($customer);

        $entity->setSpeaker($speaker);

        $form = $this->createForm(new QuestionType($this->em), $entity, array(
            'action' => $this->generateUrl('question_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Displays a form to edit an existing Question entity.
     *
     */
    public function editAction($id)
    {

        $this->postConstruct();

        $question = $this->repo->findOneById($id);

        $site = $this->get('sonata.page.manager.site')->findOneBy(array('id'=>1));
        $page = $this->get('sonata.page.cms_manager_selector')->retrieve()
            ->getPageByRouteName($site,$this->get('request')->get('_route'));

        $viewDatas = array(
            'page' => $page,
            'blocks' => $this->container->getParameter('lcdd.speaker.configuration.speaker_blocks'),
        );


        if (!$question) {
            $viewDatas['error_message'] = 'Impossible de trouver cette question';
            return $this->viewDatasRender($viewDatas, 'EximTheme'.$this->getParameter('exim.theme.name').'FrontBundle:Errors:404.html.twig');
        }

        $editForm = $this->createEditForm($question);
        $deleteForm = $this->createDeleteForm($id);

        $viewDatas['question'] = $question;
        $viewDatas['edit_form'] = $editForm->createView();
        $viewDatas['delete_form'] = $deleteForm->createView();


        return $this->render('DwlLcddSearchBundle:Question:edit.html.twig', $viewDatas);
    }

    /**
     * Edits an existing Question entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $this->postConstruct();

        $question = $this->repo->findOneById($id);

        $site = $this->get('sonata.page.manager.site')->findOneBy(array('id'=>1));
        $page = $this->get('sonata.page.cms_manager_selector')->retrieve()
            ->getPageByRouteName($site,$this->get('request')->get('_route'));

        $viewDatas = array(
            'page' => $page,
            'blocks' => $this->container->getParameter('lcdd.speaker.configuration.speaker_blocks'),
        );

        if (!$question) {
            $viewDatas['error_message'] = 'Impossible de trouver cette question';

            return $this->viewDatasRender($viewDatas, 'EximTheme'.$this->getParameter('exim.theme.name').'FrontBundle:Errors:404.html.twig');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($question);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->em->flush();

            return $this->redirect($this->generateUrl('question_edit', array('id' => $id)));
        }

        $viewDatas['question'] = $question;
        $viewDatas['edit_form'] = $editForm->createView();
        $viewDatas['delete_form'] = $deleteForm->createView();

        return $this->render('DwlLcddSearchBundle:Question:edit.html.twig', array(
            'question'      => $question,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'page' => $page,
            'blocks' => $this->container->getParameter('lcdd.speaker.configuration.speaker_blocks'),
        ));
    }

    public function removeAction(Question $question)
    {
        $this->postConstruct();
        $this->em->remove($question);
        $this->em->flush();
    }

    /**
     * Deletes a Question entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        $site = $this->get('sonata.page.manager.site')->findOneBy(array('id'=>1));
        $page = $this->get('sonata.page.cms_manager_selector')->retrieve()
            ->getPageByRouteName($site,$this->get('request')->get('_route'));

        $viewDatas = array(
            'page' => $page,
            'blocks' => $this->container->getParameter('lcdd.speaker.configuration.speaker_blocks'),
        );

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DwlLcddSearchBundle:Question')->find($id);

            if (!$entity) {
                $viewDatas['error_message'] = 'Impossible de trouver cette question';
                throw $this->createNotFoundException($viewDatas['error_message']);
                // return $this->viewDatasRender($viewDatas, 'EximTheme'.$this->getParameter('exim.theme.name').'FrontBundle:Errors:404.html.twig');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dwl_lcdd_search_question'));
    }

    /**
     * Creates a form to delete a Question entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('question_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function likeAction($id)
    {
        $this->postConstruct();
        $viewDatas = array('like' => '+0');
        if ($this->get('request')->headers->get('host') === $this->get('request')->server->get('HTTP_HOST')) {

            $question = $this->repo->findOneById($id);
            $likes = $question->getLikes();
            $question->setLikes($likes+1);
            $this->em->persist($question);
            $this->em->flush();

            $viewDatas['like'] = '+1';


        }

        return new JsonResponse($viewDatas);
    }

    // /**
    //  * @Rest\View
    //  */
    // public function viewAction($id)
    // {
    //     if ($this->get('request')->headers->get('host') === $this->get('request')->server->get('HTTP_HOST')) {
    //         $this->postConstruct();

    //         $question = $this->repo->findOneById($id);
    //         $views = $question->getViews();
    //         $question->setViews($views+1);
    //         $this->em->persist($question);
    //         $this->em->flush();

    //         $viewDatas = array('view' => '+1');
    //         return $this->viewDatasRender($viewDatas);

    //     }
    // }

}
