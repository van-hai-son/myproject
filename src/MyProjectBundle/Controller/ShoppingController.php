<?php

namespace MyProjectBundle\Controller;

use MyProjectBundle\Entity\Order;
use MyProjectBundle\MyProjectBundle;
use MyProjectBundle\PaymentManager\PaymentManager;
use MyProjectBundle\QueueManager\QueueManager;
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
        return $this->render('@MyProject/order/view_product.html.twig', []);
    }

    /**
     * @return Response
     */
    public function viewOrderAction()
    {
        return $this->render('@MyProject/order/view_order.html.twig', []);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function ajaxGetProductByConditionsAction(Request $request)
    {
        $param = $request->request->all();
        if (!$request->isXmlHttpRequest() || !isset($param['query'])) {
            return ['status' => Response::HTTP_BAD_REQUEST];
        }

        $data = $this->get('myproject.shopping_model')->getProduct($param['query']);
        return [
            'status' => Response::HTTP_OK,
            'data' => $data
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function ajaxCreateOrderAction(Request $request)
    {
        $param = $request->request->all();
        if (!$request->isXmlHttpRequest()) {
            return ['status' => Response::HTTP_BAD_REQUEST];
        }

        if (!isset($param['name']) ||
            !isset($param['phone']) ||
            !isset($param['address']) ||
            !isset($param['date']) ||
            !isset($param['email']) ||
            !isset($param['order']))
        {
            return ['status' => Response::HTTP_BAD_REQUEST];
        }

        $totalPrice = $this->get('myproject.shopping_model')->calculatePrice($param['order']);
        $param['status'] = Order::NOT_PAY;
        if (isset($param['is_paid']) &&
            $param['is_paid'] == MyProjectBundle::ENABLE &&
            isset($param['card_info']))
        {
            $param['card_info']['amount'] = $totalPrice;
            if ($this->get('payment_manager')->pay(PaymentManager::CREATE_ORDER_PAYMENT, $param['card_info'])) {
                $param['status'] = Order::IS_PAID;
            };
        }
        $code = $this->get('myproject.shopping_model')->createOrder($param);
        $this->get('queue_manager')->pushMessage(
            QueueManager::SEND_CREATE_ORDER_MAIL,
            json_encode(['code' => $code])
        );

        return ['status' => Response::HTTP_OK];
    }
}
