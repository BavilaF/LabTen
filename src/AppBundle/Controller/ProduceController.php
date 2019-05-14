<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\ProduceItem;
use AppBundle\Form\ProduceItemType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ProduceController extends BaseController {

  /**
  * @Route("/produce", name="produce")
  */
  public function new(Request $request) {

    $item = new ProduceItem("", new \DateTime("today"), "");

    $form = $this->createForm(ProduceItemType::class, $item);

    $form->handleRequest($request);

    if($form->isSubmitted()) {

      $entityManager = $this->getDoctrine()->getManager();

      $entityManager->persist($icon);

      $entityManager->flush();

      $imageFile = $item->getIcon();

      $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();

      $rootDirPath = $this->get('kernel')->getRootDir() . '/../public/uploads';

      $imageFile->move($rootDirPath, $fileName);

      $item->setIcon($fileName);

      $item->getInShoppingList();

      $item->setInShoppingList();

      return new Response(
        '<html><body>New produce item was added: '. $item->getName(). ' on ' . $item->getExpirationDate()->format('Y-m-d') .
        ' Hashed file name: ' . $item->getIcon() . '<img src="/uploads/' . $item->getIcon() . $item->getId() . $item->getInShoppingList() . '"/></body></html>'
      );
    }

    return $this->render('item.html.twig', ['produceItem_form' => $form->createView(), 'label' => 'Add Produce']);

  }

  /**
  * @Route("/produceItem", name="produceItem")
  */
  public function list(Request $request) {
    $repository = $this->getDoctrine()->getRepository(ProduceItem::class);

    $produceItems = $repository->findAll();

    return $this->render('produce/list.html.twig', ['produceItems' => $produceItems]);
  }

  /**
  * @Route("/produceItem/{produceItem}", name="produceItem")
  */
  public function listByProduceItem(Request $request, $produceItem) {
    $repository = $this->getDoctrine()->getRepository(ProduceItem::class);

    $produceItems = $repository->findAllProduceItemsByProduce($produceItem);

    return $this->render('produce/list.html.twig', ['produceItems' => $produceItems]);
  }


  /**
  * @Route("/produceItems/{id}", name="get_produceItem")
  * @Method("GET")
  */
  public function getProduce(int $id) {
    $repository = $this->getDoctrine()->getRepository(ProduceItem::class);

    $produceItem = $repository->find($id);

    return $this->render('produce/produce.html.twig', ['produceItem' => $produceItem]);
  }

  /**
  * @Route("/produceItem/{id}", name="delete_produceItem")
  * @Method("DELETE")
  */
  public function deleteProduceItem(int $id) {
    $repo = $this->getDoctrine()->getRepository(ProduceItem::class);
    $produceItem = $repo->find($id);

    $em = $this->getDoctrine()->getManager();
    $em=>remove($produceItem);
    $em->flush();

    return new JsonResponse([], Response:HTTP_N0_CONTENT);
  }

  /**
  * @ROUTE("produceItem/{id}/edit", name="edit_produceItem")
  */
  public function editProduceItem(int $id $request) {
    $repo = $this->getDoctrine()->getRepository(ProduceItem::class);
    $produceItem = $repo->find($id);

    $form = $this->createForm(ProduceItemType::class, $produceItem);

    $form->handleRequest($request);

    if ($form->isSubmitted()) {

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($produceItem);
      $entityManager->flush();

      return new Response('Produce ' . $produceItem->getId() . 'got updated');
    }

    return $this->render('ProduceItem/index.html.twig', ['form' => $form->createView(), 'label' = 'Edit Produce']);
  }

  /**
  * @ROUTE("produceItem/{id}", name="ajaxEditProduce")
  * @METHOD("PUT")
  */
  public function ajaxEditProduce(int $id, Request $request) {
    $produceItem = $this->getDoctrine()->getRepository(ProduceItem::class)->find($id);

    $data = $request->request-all();

    $form = $this->createForm(ProduceItemType::class, $produceItem);
    $form->submit($data);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($produceItem);
    $entityManager->flush();

    return new JsonResponse([], Response:;HTTP_OK);
  }
}
