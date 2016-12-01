<?php

namespace Dwl\Lcdd\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Dwl\Lcdd\SearchBundle\Entity\Question;
use Dwl\Lcdd\SearchBundle\Form\QuestionType;

class QuestionController extends Controller
{
    public function SearchAction(Request $request){

        $t = $translator = $this->get('translator');

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getEntityManager();
        $questionRepository = $em->getRepository('DwlLcddSearchBundle:Question');

        $userQuestion = $request->request->get('question', '');
        $format = $request->request->get('format', 'html');

        $viewDatas = array(
            'userQuestion' => $userQuestion,
        );

        if($format == 'json') {

            $qs = $questionRepository->findAllAsArray();
            $viewDatas['qs'] = $qs;

            return new JsonResponse( $viewDatas );
        } else {

            $qs = $questionRepository->findAll();
            $viewDatas['qs'] = $qs;

            $categoryRepository = $em->getRepository('ApplicationSonataClassificationBundle:Category');
            $cs = $categoryRepository->findAll();
            $viewDatas['cs'] = $cs;

            $tagRepository = $em->getRepository('ApplicationSonataClassificationBundle:Tag');
            $ts = $tagRepository->findAll();
            $viewDatas['ts'] = $ts;

            return $this->render('DwlLcddSearchBundle:Question:search.html.twig', $viewDatas);
        }

    }

    public function ShowAction(Request $request, $id){
        $t = $translator = $this->get('translator');

        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository('DwlLcddSearchBundle:Question');
        $question = $repository->findOneById($id);

        return $this->render('DwlLcddSearchBundle:Question:show.html.twig', array(
            'id' => $question->getId(),
            'question' => $question->getQuestion(),
            'qualified' => $question->getQualified() ? 'Qualifie' : 'Non qualifie',
        ));
    }

    public function SubmitAction(Request $request)
    {

        $t = $translator = $this->get('translator');

        // is it an Ajax request?
        $isAjax = $request->isXmlHttpRequest();

        // what's the preferred language of the user?
        $language = $request->getLocale();

        $viewDatas = array();
        $viewDatas['success'] = false;
        $viewDatas['message'] = $t->trans('_q._s.none', array(), 'DwlLcddSearchBundle');

        $questionForm = $this->container->get( 'dwl.lcdd.block.search.form.question' );
        $newQuestion = $this->container->get( 'dwl.lcdd.block.search.form.entity.question' );

        if ( $request->isMethod( 'POST' ) ) {

          $questionForm->handleRequest($request);

          if ( $questionForm->isValid() ) {

            $doctrine = $this->getDoctrine();
            $em = $doctrine->getEntityManager();
            $repository = $doctrine->getRepository('DwlLcddSearchBundle:Question');
            $newQuestion = $questionForm->getData();

            if (!$repository->findOneByQuestion($newQuestion->getQuestion())) {
                $em->persist($newQuestion);
                $em->flush();
                $viewDatas['success'] = true;
                $viewDatas['message'] = $t->trans('_q._s.thanks', array(), 'DwlLcddSearchBundle');
            } else {
                $viewDatas['message'] = $t->trans('_q._s.already', array(), 'DwlLcddSearchBundle');
            }

          }

        }

        if ($isAjax) {
            return new JsonResponse( $viewDatas );
        } else {

            if ($viewDatas['success']) {
                $newQuestion = $questionForm->getData();
            }

            $viewDatas['language'] = $language;
            $viewDatas['questionString'] = $newQuestion->getQuestion();
            $viewDatas['isQualified'] = $newQuestion->getQualified() ? $t->trans('_g.yes', array(), 'DwlLcddSearchBundle') : $t->trans('_g.no', array(), 'DwlLcddSearchBundle');
            $viewDatas['success'] = $viewDatas['success'] ? $t->trans('_g.yes', array(), 'DwlLcddSearchBundle') : $t->trans('_g.no', array(), 'DwlLcddSearchBundle');
            $viewDatas['message'] = $viewDatas['message'];

            return $this->render('DwlLcddSearchBundle:Question:submit.html.twig', $viewDatas);
        }

    }
}
