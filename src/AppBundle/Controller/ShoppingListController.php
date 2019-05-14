<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\ShoppingList;
use AppBundle\Form\ProduceItemType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoppingListController extends BaseController {

  /**
  * @Route("/produce", name="produce")
  */
  public function new(Request $request) {

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

      $item->getShoppingListItems();

      $item->getFridgeItems();

      return new Response(
        '<html><body>New produce item was added: '. $item->getName(). ' on ' .
         $item->getIcon() . '<img src="/uploads/' . $item->getIcon() . $item->getId() .
         $item->getInShoppingList() . $item->getShoppingListItems() . $item->getFridgeItems() . '"/></body></html>'
      );
    }

    return $this->render('item.html.twig', ['produceItem_form' => $form->createView()]);

  }

  /**
  * @Route("/list-produce", name="produce_list")
  */
  public function list() {
    $repository = $this->getDoctrine()->getRepository(ProduceItem::class);

    $produceItems = $repository->findAllProduceItems();

    return $this->render('produce/list.html.twig', ['produceItems' => $produceItems]);
  }

  /**
  * @Route("/produce/{id}", name="get_produce")
  */
  public function getProduce(int $id) {
    $repository = $this->getDoctrine()->getRepository(ProduceItem::class);

    $produceItem = $repository->find($id);

    return $this->render('produce/produce.html.twig', ['produceItem' => $produceItem]);
  }
  //
  // /**
  // * @ROUTE("produceItem/{id}", name="ajaxEditProduce")
  // * @METHOD("PUT")
  // */
  // public function ajaxEditProduce(int $id, Request $request) {
  //   $produceItem = $this->getDoctrine()->getRepository(ProduceItem::class)->find($id);
  //
  //   $data = $request->request-all();
  //
  //   $form = $this->createForm(ProduceItemType::class, $produceItem);
  //   $form->submit($data);
  //
  //   $entityManager = $this->getDoctrine()->getManager();
  //   $entityManager->persist($produceItem);
  //   $entityManager->flush();
  //
  //   return new JsonResponse([], Response:;HTTP_OK);
  // }

  /**
  * @ROUTE("produceItem/download", name="produceItem_download")
  */
  public function download() {
    $repository = $this->getDoctrine()->getRepository(ProduceItem::class);

    $produceItems = $repository->findAllProduceItems();

    $fp = fopen('produceItem_list.txt', 'w');

    $content = '';

    foreach($produceItems as $produceItem) {
      $fp = $produceItem->getName();
      $ln = $produceItem->getExpirationDate();
      $content .= "$fp $ln:\n";

      $shoppingListItems = $produceItem->getShoppingListItems();
      foreach ($shoppingListItem as $shoppingListItems) {
        $cn = $shoppingListItem->getProduceItem();
        $content .="\t$cn\n";
      }
    }

    fwrite($fp, $content);
    fclose($fp);

    return $this->file('produceItem_list.txt');

  }
}
