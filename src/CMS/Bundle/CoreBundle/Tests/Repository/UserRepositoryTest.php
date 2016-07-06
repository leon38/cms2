<?php
/**
 * User: DCA
 * Date: 05/07/2016
 * Time: 16:02
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
  /**
   * @var \Doctrine\ORM\EntityManager
   */
  private $em;

  /**
   * {@inheritDoc}
   */
  protected function setUp()
  {
    self::bootKernel();

    $this->em = static::$kernel->getContainer()
      ->get('doctrine')
      ->getManager();
  }

  public function testLoadUserByUsername()
  {
    $user = $this->em
    ->getRepository('CoreBundle:User')
    ->loadUserByUsername('leoncorono@gmail.com')
  ;

    $this->assertNotNull($user);
  }

  public function testLoadUserByEmail()
  {
    $user = $this->em
      ->getRepository('CoreBundle:User')
      ->loadUserByUsername('leoncorono@gmail.com')
    ;

    $this->assertNotNull($user);
  }

  /**
   * {@inheritDoc}
   */
  protected function tearDown()
  {
    parent::tearDown();

    $this->em->close();
    $this->em = null; // avoid memory leaks
  }
}