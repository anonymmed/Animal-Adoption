<?php

namespace VenteBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Payment\CoreBundle\Form\ChoosePaymentMethodType;
use JMS\Payment\CoreBundle\PluginController\Result;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use VenteBundle\Entity\Commande;

class CommandeController extends Controller
{
    public function commanderAction($id)
    {
        $x=new ArrayCollection();
        $y=new ArrayCollection();
        $em = $this->getDoctrine()->getManager();

        $array2=$em->getRepository("VenteBundle:Ligne")->totalpanier($id);
        $array=$em->getRepository("VenteBundle:Ligne")->passerCommander($id);
        foreach ($array as $a)
        {
            $x->add($a['description']);
        }
        $description=implode("+",$x->getValues());
        foreach ($array2 as $am)
        {
            $y->add($am['total']);
        }
        $time = new \DateTime();
        $time->format(' Y-m-d');

        $amount=(int)implode('',$y->getValues());
        $order = new Commande($amount,$description,$time);
        $em->persist($order);
        $em->flush();
        $em->getRepository("VenteBundle:Ligne")->supprimerPanier($id);

        return $this->redirect($this->generateUrl('paypal', array( 'id' => $order->getId())));
    }

    public function showAction(Request $request, Commande $order)
    {
        $config = [
        'paypal_express_checkout' => [
            'return_url' => $this->generateUrl('createPayment', [
                'id' => $order->getId(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancelPayment', [
                'id' => $order->getId(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'useraction' => 'commit',
        ],
    ];

        $form = $this->createForm(ChoosePaymentMethodType::class, null, [
            'amount'   => $order->getAmount(),
            'currency' => 'EUR',
            'predefined_data' => $config,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ppc = $this->get('payment.plugin_controller');
            $ppc->createPaymentInstruction($instruction=$form->getData());
            $order->setPaymentInstruction($instruction);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush($order);

            return $this->redirect($this->generateUrl('createPayment', [
                'id' => $order->getId(),
            ]));
        }

        return $this->render('@Vente/Produit/commande.html.twig',array('form'=>$form->createView(),'order'=>$order));
    }

    private function createPayment($order)
    {
        $instruction = $order->getPaymentInstruction();
        $pendingTransaction = $instruction->getPendingTransaction();

        if ($pendingTransaction !== null) {
            return $pendingTransaction->getPayment();
        }

        $ppc = $this->get('payment.plugin_controller');
        $amount = $instruction->getAmount() - $instruction->getDepositedAmount();

        return $ppc->createPayment($instruction->getId(), $amount);
    }

    public function paymentCreateAction(Commande $order)
    {
        $payment = $this->createPayment($order);

        $ppc = $this->get('payment.plugin_controller');
        $result = $ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());

        if ($result->getStatus() === Result::STATUS_SUCCESS) {
            return $this->redirect($this->generateUrl('successPayment', [
                'id' => $order->getId(),
            ]));
        }
        if ($result->getStatus() === Result::STATUS_PENDING) {
            $ex = $result->getPluginException();

            if ($ex instanceof ActionRequiredException) {
                $action = $ex->getAction();

                if ($action instanceof VisitUrl) {
                    return $this->redirect($action->getUrl());
                }
            }
        }

        throw $result->getPluginException();



        // In a real-world application you wouldn't throw the exception. You would,
        // for example, redirect to the showAction with a flash message informing
        // the user that the payment was not successful.
    }

    public function paymentCompleteAction(Commande $order)
    {
        return new Response('Payment complete');
    }
    public function paymentCancelAction()
    {
        return new Response('Payment cancel');
    }
}
