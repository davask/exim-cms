<?php

namespace Dwl\Lcdd\SpeakerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dwl\Lcdd\SpeakerBundle\Entity\Speaker;
use Dwl\Lcdd\SpeakerBundle\Form\SpeakerType;

/**
 * Speaker controller.
 *
 */
class SpeakerController extends Controller
{

    private function postConstruct() {

        $this->doctrine = $this->getDoctrine();
        $this->em = $this->doctrine->getEntityManager();
        $this->repo = $this->em->getRepository('DwlLcddSpeakerBundle:Speaker');

        $this->request = $this->getRequest();
        $this->translator = $this->get('translator');

    }

    /**
     * Lists all Speaker entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DwlLcddSpeakerBundle:Speaker')->findAll();

        return $this->render('DwlLcddSpeakerBundle:Speaker:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Speaker entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Speaker();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lcdd_speaker_get', array('username' => $entity->getUsername())));
        }

        return $this->render('DwlLcddSpeakerBundle:Speaker:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Speaker entity.
     *
     * @param Speaker $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Speaker $entity)
    {
        $form = $this->createForm(new SpeakerType(), $entity, array(
            'action' => $this->generateUrl('lcdd_speaker_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Speaker entity.
     *
     */
    public function newAction()
    {
        $entity = new Speaker();
        $form   = $this->createCreateForm($entity);

        return $this->render('DwlLcddSpeakerBundle:Speaker:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Speaker entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DwlLcddSpeakerBundle:Speaker')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Speaker entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DwlLcddSpeakerBundle:Speaker:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Speaker entity.
     *
     */
    public function editAction()
    {

        $this->postConstruct();

        $username = $this->request->attributes->get('username', 'me');

        $user = $this->getUser();

        if($username == 'me') {
            $entity = $user;
        } else {
            $entity = $this->repo->findOneByUsernameCanonical($username);
        }

        if(empty($entity)) {
            // throw $this->createNotFoundException('Unable to find Speaker entity.');
            return $this->redirect($this->generateUrl('lcdd_speaker_all'));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity->getId());

        $site = $this->get('sonata.page.manager.site')->findOneBy(array('id'=>1));
        $page = $this->get('sonata.page.cms_manager_selector')->retrieve()
            ->getPageByRouteName($site,$this->get('request')->get('_route'));


        return $this->render('DwlLcddSpeakerBundle:Speaker:edit.html.twig', array(
            'speaker'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'page' => $page,
            'blocks' => $this->container->getParameter('lcdd.speaker.configuration.speaker_blocks'),
        ));
    }

    /**
    * Creates a form to edit a Speaker entity.
    *
    * @param Speaker $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Speaker $entity)
    {

        $this->postConstruct();

        $form = $this->createForm(new SpeakerType($this->container->get('sonata.admin.pool'), $this->container), $entity, array(
            'action' => $this->generateUrl('lcdd_speaker_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array(
            'label' => $this->translator->trans('Enregistrer', array(), 'DwlLcddSpeakerBundle'),
            'attr' => array('class' => 'btn btn-link'),
        ));

        return $form;
    }
    /**
     * Edits an existing Speaker entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $this->postConstruct();

        $entity = $this->repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Speaker entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        dump($request);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->em->flush();

            return $this->redirect($this->generateUrl('lcdd_speaker_edit', array('username' => $entity->getCustomer()->getUser()->getUsername())));
        }

        return $this->render('DwlLcddSpeakerBundle:Speaker:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Speaker entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DwlLcddSpeakerBundle:Speaker')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Speaker entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('lcdd_speaker_all'));
    }

    /**
     * Creates a form to delete a Speaker entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lcdd_speaker_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'label' => $this->translator->trans('Supprimer cet utilisateur', array(), 'DwlLcddSpeakerBundle'),
                'attr' => array('class' => 'btn btn-link'),
            ))
            ->getForm()
        ;
    }
}
