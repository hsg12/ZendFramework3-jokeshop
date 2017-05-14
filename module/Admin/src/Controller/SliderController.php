<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Admin\Service\FormServiceInterface;
use Application\Entity\Slider;
use Zend\Form\FormInterface;

class SliderController extends AbstractActionController
{
    private $entityManager;
    private $formService;
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormServiceInterface $formService
    ) {
        $this->entityManager = $entityManager;
        $this->formService   = $formService;
        $this->repository    = $this->entityManager->getRepository(Slider::class);
    }

    public function indexAction()
    {
        $slider = $this->repository->getSlider(false);
        return new ViewModel([
            'slider' => $slider,
        ]);
    }

    public function addAction()
    {
        $slider = new Slider();
        $form = $this->formService->getAnnotationForm($this->entityManager, $slider);
        $form->setValidationGroup(FormInterface::VALIDATE_ALL);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $files = $request->getFiles()->toArray();
            if ($files) { $fileName = $files['file']['name']; }

            $form->setData($data);

            if ($form->isValid()) {
                $slider = $form->getData();

                if ($fileName) {
                    $slider->setImage('/img/slider/' . $fileName);
                }

                $this->entityManager->persist($slider);
                $this->entityManager->flush();

                $this->flashMessenger()->setNamespace('success')->addMessage('Added to slider');

                return $this->redirect()->toRoute('admin/slider');
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id', 0);
        $slider = $this->repository->find($id);

        if (! $id || ! $slider) {
            return $this->notFoundAction();
        }

        $form = $this->formService->getAnnotationForm($this->entityManager, $slider);
        $form->setValidationGroup(FormInterface::VALIDATE_ALL);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $files = $request->getFiles()->toArray();
            if ($files) { $fileName = $files['file']['name']; }

            $form->setData($data);

            if ($form->isValid()) {
                $slider = $form->getData();

                if ($fileName) {
                    $oldImage = $slider->getImage();
                    if (is_file(getcwd() . '/public_html' . $oldImage)) {
                        unlink(getcwd() . '/public_html' . $oldImage);
                    }

                    $slider->setImage('/img/slider/' . $fileName);
                }

                $this->entityManager->persist($slider);
                $this->entityManager->flush();

                $this->flashMessenger()->setNamespace('success')->addMessage('Slider edited');

                return $this->redirect()->toRoute('admin/slider');
            }
        }

        return new ViewModel([
            'id'   => $id,
            'form' => $form,
        ]);
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id', 0);
        $slider = $this->repository->find($id);

        if (! $request->isPost() || ! $slider) {
            return $this->notFoundAction();
        }

        /* Block for deletion slider image */
        if ($slider) {
            if (is_file(getcwd() . '/public_html' . $slider->getImage())) {
                unlink(getcwd() . '/public_html' . $slider->getImage());
            }
        }
        /* End block */

        $this->entityManager->remove($slider);
        $this->entityManager->flush();

        $this->flashMessenger()->addSuccessMessage('Delete from slider');
        return $this->redirect()->toRoute('admin/slider');
    }
}
