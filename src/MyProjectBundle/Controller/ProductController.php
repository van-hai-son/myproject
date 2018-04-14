<?php

namespace MyProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 * @package MyProjectBundle\Controller
 */
class ProductController extends Controller
{
    /**
     * @return Response
     */
    public function viewProductAction()
    {
        $data = $this->get('repository.elastic_search.product')->findBy([]);
        $viewData = $this->get('transform.product')->transformProduct($data->getAggregations());

        return $this->render('@MyProject/product/view_product.html.twig', $viewData);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function ajaxGetProductByConditionsAction(Request $request)
    {
        return [
            'status' => Response::HTTP_OK,
            'data' => $request->request->all()
        ];

        if (!$request->isXmlHttpRequest()) {
            return ['status' => Response::HTTP_BAD_REQUEST];
        }
        $param = $request->request->all();
        if (!$this->get('validation.product.get')->validGetProductRequest($param)) {
            return ['status' => Response::HTTP_BAD_REQUEST];
        };
        $data = $this->get('repository.elastic_search.product')->findBy($param['query']);
        $viewData = $this->get('transform.product')->transformProductForAjax($data);

        return [
            'status' => Response::HTTP_OK,
            'data' => $viewData
        ];
    }
}
