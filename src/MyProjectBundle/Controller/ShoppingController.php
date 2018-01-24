<?php

namespace MyProjectBundle\Controller;

use MyProjectBundle\Entity\Order;
use MyProjectBundle\MyProjectBundle;
use MyProjectBundle\PaymentManager\PaymentManager;
use MyProjectBundle\QueueManager\QueueManager;
use phpseclib\Crypt\RSA;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ShoppingController
 * @package MyProjectBundle\Controller
 */
class ShoppingController extends Controller
{
    public function indexAction()
    {
        $private = '-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQDes6uHp1e3axYaK4VDeUbk4cliqj+jt5PHsJvCWGJphLjErmFR
2hyE60l6Oz/WVXOnXg3C72iGWjEH+mhFV5NzO+Z7Y68rDhjRxYgD5LFa93DdPHKS
aEhU+XhOeURusQKeP/ixLPPDjXZmeszE9tE6qZmM1jdmMlVvOO/nYKkBMQIDAQAB
AoGBAJ8kw/Inur4/D0daTFwgYXIUi6YvRVUITdnIsjYalREUoDkk6DTm6bRus05e
4sqWvBQhWTIxhX9lGl66KiNLZvj/TZDm8HoIlOuvOB6dL+h13ujAOzuBC88w4Zza
mKIf6PEMj6uDLTZtfgtCdxCM3wT44wC74EoDtbTKvOMQ2PolAkEA/H4qIK1fht3l
OqS5ZDhb+6gTl6okKzVdKgGkuzffi2ykL0DDa38BOgJ08aQHgUS+k/PV0aTDByrQ
njItka/SLwJBAOHLknc1G1Ai4zSX8gYUtBu6GhsYGM+P2SWDljBDZ0SHojb6aJwt
iq5uJpD9j+5o1E9e8LK82/De4zxsTUnyap8CQCMDW9QsNpL9Mkxvi0Xaiuba59yg
O2PCgqjQkYdkuBsddacX1AGUGxdwP8dCd8f1QHhD1+K6dlEIHuXRD4CkYQECQQC7
PgCwoDaNJtE5TQOj7UlKcPKzIzF39ncBN/S1A0BAwSVQ6CkV0mJdsfSDafIQp5fg
Vf/OiPTu8zaHZdxWBRRLAkEAgULmQG52qC+3vPaTRRAGf2oK9vTsNgqSD1Rej2mr
W5JqSc2Vj38MgoXhI/oIMHi9qa2sAhANFagl6IWnhJBB/w==
-----END RSA PRIVATE KEY-----';
        $public = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDes6uHp1e3axYaK4VDeUbk4cli
qj+jt5PHsJvCWGJphLjErmFR2hyE60l6Oz/WVXOnXg3C72iGWjEH+mhFV5NzO+Z7
Y68rDhjRxYgD5LFa93DdPHKSaEhU+XhOeURusQKeP/ixLPPDjXZmeszE9tE6qZmM
1jdmMlVvOO/nYKkBMQIDAQAB
-----END PUBLIC KEY-----';

//        $rsa = new RSA();
//        $key = $rsa->createKey();
//
//        $rsa->loadKey($key['publickey']);
//        $rsa->loadKey($key['privatekey']);
//        $encrypt =$rsa->encrypt('message');
//
//        $rsa->loadKey($key['publickey']);
//        $rsa->loadKey($key['privatekey']);
//        $message = $rsa->decrypt($encrypt);

        $rsa = new RSA();
        $rsa->loadKey($public);
        $encrypt = $rsa->encrypt('meaage');
        $rsa->loadKey($private);
$fp = fopen('/var/www/myproject/data2.txt', 'w+');
fwrite($fp, $encrypt);
fclose($fp);

        return $this->render(
            '@MyProject/test.html.twig',
            [
                'private_key' => $private,
                'public_key' => $public,
                'message' => $rsa->decrypt($encrypt)
            ]
        );
    }

    public function ajaxAction(Request $request)
    {
        $private = '-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQDes6uHp1e3axYaK4VDeUbk4cliqj+jt5PHsJvCWGJphLjErmFR
2hyE60l6Oz/WVXOnXg3C72iGWjEH+mhFV5NzO+Z7Y68rDhjRxYgD5LFa93DdPHKS
aEhU+XhOeURusQKeP/ixLPPDjXZmeszE9tE6qZmM1jdmMlVvOO/nYKkBMQIDAQAB
AoGBAJ8kw/Inur4/D0daTFwgYXIUi6YvRVUITdnIsjYalREUoDkk6DTm6bRus05e
4sqWvBQhWTIxhX9lGl66KiNLZvj/TZDm8HoIlOuvOB6dL+h13ujAOzuBC88w4Zza
mKIf6PEMj6uDLTZtfgtCdxCM3wT44wC74EoDtbTKvOMQ2PolAkEA/H4qIK1fht3l
OqS5ZDhb+6gTl6okKzVdKgGkuzffi2ykL0DDa38BOgJ08aQHgUS+k/PV0aTDByrQ
njItka/SLwJBAOHLknc1G1Ai4zSX8gYUtBu6GhsYGM+P2SWDljBDZ0SHojb6aJwt
iq5uJpD9j+5o1E9e8LK82/De4zxsTUnyap8CQCMDW9QsNpL9Mkxvi0Xaiuba59yg
O2PCgqjQkYdkuBsddacX1AGUGxdwP8dCd8f1QHhD1+K6dlEIHuXRD4CkYQECQQC7
PgCwoDaNJtE5TQOj7UlKcPKzIzF39ncBN/S1A0BAwSVQ6CkV0mJdsfSDafIQp5fg
Vf/OiPTu8zaHZdxWBRRLAkEAgULmQG52qC+3vPaTRRAGf2oK9vTsNgqSD1Rej2mr
W5JqSc2Vj38MgoXhI/oIMHi9qa2sAhANFagl6IWnhJBB/w==
-----END RSA PRIVATE KEY-----';

        $data = $request->request->all();
        $rsa = new RSA();
        $rsa->loadKey($private);

        $message = $rsa->decrypt($data['message']);
        $fp = fopen('/var/www/myproject/data1.txt', 'w+');
        fwrite($fp, $data['message']);
        fclose($fp);
        return new JsonResponse(['message' => $message]);
    }

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
