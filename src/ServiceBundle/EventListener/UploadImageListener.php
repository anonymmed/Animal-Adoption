<?php
/**
 * Created by PhpStorm.
 * User: jabou
 * Date: 20/02/2018
 * Time: 22:53
 */

namespace ServiceBundle\EventListener;


use Doctrine\ORM\Event\PreUpdateEventArgs;
use ServiceBundle\Entity\CentreDressage;
use ServiceBundle\ImageUpload;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;


class UploadImageListener
{

    private $uploader;

    public function __construct(ImageUpload $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof CentreDressage) {
            return;
        }

        $file = $entity->getImage();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploader->upload($file);
        $entity->setImage($fileName);
    }

}