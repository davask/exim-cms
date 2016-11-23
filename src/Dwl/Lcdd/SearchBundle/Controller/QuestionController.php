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
    public function SubmitAction(Request $request)
    {

        // is it an Ajax request?
        $isAjax = $request->isXmlHttpRequest();

        // what's the preferred language of the user?
        $language = $request->getPreferredLanguage(array('en', 'fr'));

        $viewDatas = array();

        $questionForm = $this->container->get( 'dwl.lcdd.block.search.form.question' );
        $newQuestion = $this->container->get( 'dwl.lcdd.block.search.form.entity.question' );

        if ( $request->isMethod( 'POST' ) ) {

          $questionForm->handleRequest($request);

          if ( $questionForm->isValid() ) {

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($questionForm->getData());
            $em->flush();

            $viewDatas['success'] = true;

          } else {

            $viewDatas['success'] = false;

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
            $viewDatas['isQualified'] = $newQuestion->getQualified() ? 'Yes' : 'No';

            return $this->render('DwlLcddSearchBundle:Question:submit.html.twig', $viewDatas);
        }

    }
}
