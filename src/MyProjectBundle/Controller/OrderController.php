<?php

namespace MyProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OrderController
 * @package MyProjectBundle\Controller
 */
class OrderController extends Controller
{
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
