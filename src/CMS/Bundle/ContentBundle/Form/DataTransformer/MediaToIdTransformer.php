<?php
/**
 * User: DCA
 * Date: 19/08/2016
 * Time: 14:42
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Form\DataTransformer;

use CMS\Bundle\MediaBundle\Entity\Media;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class MediaToIdTransformer implements DataTransformerInterface
{
    private $manager;
    
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    /**
     * Transforms a string of id in array of media
     *
     * @param mixed $ids
     * @return string
     * @internal Media $media
     */
    public function transform($ids)
    {
        if ($ids == "" || $ids == null) {
            return '';
        }
        $media = $this->manager
            ->getRepository('MediaBundle:Media')
            // query for the issue with this id
            ->findMediaIn($ids)
        ;
        return $media;
    }
    
    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $mediaNumbers
     * @return mixed|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($mediaNumbers)
    {
        // no issue number? It's optional, so that's ok
        if (!$mediaNumbers) {
            return;
        }
        
        $media = $this->manager
            ->getRepository('MediaBundle:Media')
            // query for the issue with this id
            ->findMediaIn($mediaNumbers)
        ;
        
        if (empty($media)) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $mediaNumbers
            ));
        }
        
        return $media;
    }
}