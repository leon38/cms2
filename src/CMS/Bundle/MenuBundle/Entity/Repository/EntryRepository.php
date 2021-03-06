<?php

namespace CMS\Bundle\MenuBundle\Entity\Repository;

use CMS\Bundle\MenuBundle\Entity\Entry;
use CMS\Bundle\MenuBundle\Entity\MenuTaxonomy;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * EntryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntryRepository extends NestedTreeRepository
{
    
    public function findByMenuTaxonomy($slug)
    {
        return $this->_em
            ->createQueryBuilder('e')
            ->select('e')
            ->from('MenuBundle:Entry', 'e')
            ->leftJoin('e.menu_taxonomy', 'mt')
            ->where('mt.slug = :slug')
            ->setParameter('slug', $slug)
            ->andWhere('e.lvl > 0')
            ->orderby('e.lft', 'asc')
            ->getQuery()
            ->getResult();
        
    }
    
    public function findAll()
    {
        return $this->_em
            ->createQueryBuilder('e')
            ->select('e')
            ->from('MenuBundle:Entry', 'e')
            ->where('e.lvl > 0')
            ->orderby('e.lft', 'asc')
            ->getQuery()
            ->getResult();
    }
    
    public function getAllEntriesMenu(MenuTaxonomy $menu_taxonomy)
    {
        return $this->_em
            ->createQueryBuilder('e')
            ->select('e')
            ->from('MenuBundle:Entry', 'e')
            ->leftJoin('e.menu_taxonomy', 'mt')
            ->where('e.lvl > 0')
            ->andWhere('mt.id = :mt_id')
            ->setParameter('mt_id', $menu_taxonomy->getId());
    }
    
    public function getSiblings(Entry $entity)
    {
        if (is_null($entity->getId())) {
            return $this->_em
                ->createQueryBuilder('e')
                ->select('e')
                ->from('MenuBundle:Entry', 'e')
                ->leftJoin('e.menu_taxonomy', 'mt')
                ->where('e.lvl > 0')
                ->andWhere('mt.id = :mt_id')
                ->setParameter('mt_id', $entity->getMenuTaxonomy()->getId());
        }
        
        return $this->_em
            ->createQueryBuilder('e')
            ->select('e')
            ->from('MenuBundle:Entry', 'e')
            ->leftJoin('e.menu_taxonomy', 'mt')
            ->where('e.id != :id')
            ->setParameter('id', $entity->getId())
            ->andWhere('e.lvl = :lvl')
            ->setParameter('lvl', $entity->getLvl())
            ->andWhere('mt.id = :mt_id')
            ->setParameter('mt_id', $entity->getMenuTaxonomy()->getId());
    }
}
