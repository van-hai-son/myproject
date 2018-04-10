<?php

namespace MyProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ShoppingController
 * @package MyProjectBundle\Controller
 */
class ShoppingController extends Controller
{
    /**
     * @return Response
     */
    public function viewProductAction()
    {
        $data = $this->get('repository.elastic_search.product')->findBy([]);
        $viewData = $this->get('transform.product')->transformProduct($data->getAggregations());

        return $this->render('@MyProject/order/view_product.html.twig', $viewData);
    }

    /**
     * @return Response
     */
    public function viewOrderAction()
    {
        $data = $this->get('repository.mysql.order')->findAll();
        $viewData = $this->get('transform.order')->transformOrder($data);

        return $this->render('@MyProject/order/view_order.html.twig', $viewData);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function ajaxGetProductByConditionsAction(Request $request)
    {
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

    /**
     * @param Request $request
     * @return array
     */
    public function ajaxCreateOrderAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return ['status' => Response::HTTP_BAD_REQUEST];
        }
        $param = $request->request->all();
        if (!$this->get('validation.order')->validCreateOrderRequest($param)) {
            return ['status' => Response::HTTP_BAD_REQUEST];
        };
        if (!$this->get('use_case.order')->createOrderUseCase($param)) {
            return ['status' => Response::HTTP_BAD_REQUEST];
        };

        return ['status' => Response::HTTP_OK];
    }
}
