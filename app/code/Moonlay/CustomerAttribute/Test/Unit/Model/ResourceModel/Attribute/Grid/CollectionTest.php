<?php
/**
 * Customer attribute controller
 */

namespace Moonlay\CustomerAttribute\Test\Unit\Model\ResourceModel\Attribute\Grid;

use Magento\Eav\Model\Entity as Entity;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ResourceConnection\ConfigInterface as ResourceConfigInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Model\ResourceModel\Type\Db\ConnectionFactoryInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomerAttribute\Model\ResourceModel\Attribute\Grid\Collection;
use Magento\TestFramework\Helper\Bootstrap;

class CollectionTest extends \PHPUnit\Framework\TestCase
{
    private $collectionMock;

    private $entityFactoryMock;

    private $loggerMock;

    private $fetchStrategyMock;

    private $eventManagerMock;

//    private $mainTableMock = 'testTable';
//
//    private $resourceModelMock = 'testModel';
    private $mainTableMock;

    private $resourceModelMock;

    private $entityModelMock;
    private $resourceConfigMock;
    private $connectionFactoryMock;
    private $deploymentConfigMock;
    private $collection;
    private $objectManager;
    private $targetObject;


    protected function setUp()
    {
        $this->entityFactoryMock = $this->createMock(\Magento\Framework\Data\Collection\EntityFactoryInterface::class);
        $this->resourceConfigMock = $this->createMock(ResourceConfigInterface::class);
        $this->connectionFactoryMock = $this->createMock(ConnectionFactoryInterface::class);
        $this->deploymentConfigMock = $this->createMock(DeploymentConfig::class);
//        $this->deploymentConfigMock->expects($this->any())->method('get')->willReturnSelf();

        $this->resourceModelMock = $this->getMockBuilder(ResourceConnection::class)
            ->disableOriginalConstructor()
//            ->setConstructorArgs([$this->resourceConfigMock, $this->connectionFactoryMock,$this->deploymentConfigMock,'my_'])
            ->getMock();

    //    $this->resourceModelMock->expects($this->once())->method('getTablePrefix')->willReturn('conn');


        $this->loggerMock = $this->createMock(\Psr\Log\LoggerInterface::class);

        $this->fetchStrategyMock = $this->createMock(\Magento\Framework\Data\Collection\Db\FetchStrategyInterface::class);

        $this->eventManagerMock = $this->createMock(\Magento\Framework\Event\ManagerInterface::class);
        $this->mainTableMock = 'MainTable';

        $this->entityModelMock = $this->createMock(\Magento\Eav\Model\Entity::class);


        $this->objectManagerHelper = new ObjectManagerHelper($this);
//        $this->gridMock = $this->objectManagerHelper->getObject(
//            \Moonlay\CustomerAttribute\Model\ResourceModel\Attribute\Grid\Collection::class,
//            [
//                'entityFactory' => $this->entityFactoryMock,
//                'logger' => $this->loggerMock,
//                'fetchStrategy' => $this->fetchStrategyMock,
//                'eventManager' => $this->eventManagerMock,
//                'mainTable' => 'customer_eav_attribute',
//                'resourceModel' => 'resource',
//                'identifierName' => 'any',
//                'connectionName' =>'any'
//            ]
//        );

//        EntityFactory $entityFactory,
//        Logger $logger,
//        FetchStrategy $fetchStrategy,
//        EventManager $eventManager,
//        $mainTable,
//        $resourceModel = null,
//        $identifierName = null,
//        $connectionName = null

    }


    public function test_initSelect()
    {
//        $refClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Model\ResourceModel\Attribute\Grid\Collection::class);
//        $_initSelect = $refClass->getMethod('_initSelect');
//        $_initSelect->setAccessible(true);
//              $_initSelect->invoke($this->collectionMock,[]);

      //  $this->assertEquals(true, $_initSelect->invoke($this->collectionMock, ['Moonlay_CustomerAttribute::customer_attributes']));
      //  $this->gridMock->_initSelect();
    }
}