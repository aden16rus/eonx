<?php

namespace App\Tests\Service;

use App\Entity\Customer;
use App\Interfaces\ImportCustomerServiceInterface;
use App\Provider\CustomerApiProvider;
use App\Service\ApiImportCustomerService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ImportServiceTest extends KernelTestCase
{
    /**
     * @var ApiImportCustomerService
     */
    private $service;
    private $em;
    
    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
    
        $this->em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        $this->truncateDb();
    
        // mock data provider
        $customerProvider = $this->getMockBuilder(CustomerApiProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCustomers'])
            ->getMock();
        $json = '[{"gender":"male","name":{"title":"Mr","first":"Gordon","last":"Burton"},"location":{"street":{"number":5492,"name":"Green Rd"},"city":"Tweed","state":"Victoria","country":"Australia","postcode":7031,"coordinates":{"latitude":"28.2762","longitude":"-0.1040"},"timezone":{"offset":"-12:00","description":"Eniwetok, Kwajalein"}},"email":"gordon.burton@example.com","login":{"uuid":"61085edf-6d7e-4473-bbb0-1fa117155f3f","username":"redbird161","password":"pictuers","salt":"z2E5rCl7","md5":"31036dc26977218bd2e620a7ea2d84b6","sha1":"c83250e2b3a149866700025ee92345d9219c49ba","sha256":"bf5231934c37222cca0462d09b748dfcd8fe69f6bafdc6d3804b57fad4ef8b70"},"phone":"01-4033-9192"},{"gender":"female","name":{"title":"Miss","first":"Frances","last":"Terry"},"location":{"street":{"number":7657,"name":"Oak Lawn Ave"},"city":"Rockhampton","state":"South Australia","country":"Australia","postcode":8120,"coordinates":{"latitude":"-53.2450","longitude":"56.5289"},"timezone":{"offset":"0:00","description":"Western Europe Time, London, Lisbon, Casablanca"}},"email":"frances.terry@example.com","login":{"uuid":"3e89104a-7847-493c-ab28-5e37c9b92f95","username":"angryfrog430","password":"panama","salt":"8KvadOAU","md5":"afc0acab5126244fd206fa67f5f1aad6","sha1":"e6c5b6ed1370976b4482338a70dcde37ae2d8b76","sha256":"6164c2dff2c786b652eb72ba5f1f03a7467ecb7788e16a31a08c91036507ab03"},"phone":"09-2622-5568"},{"gender":"male","name":{"title":"Mr","first":"Shawn","last":"Hunt"},"location":{"street":{"number":2736,"name":"Country Club Rd"},"city":"Bundaberg","state":"South Australia","country":"Australia","postcode":4017,"coordinates":{"latitude":"-89.4303","longitude":"-159.5720"},"timezone":{"offset":"+4:30","description":"Kabul"}},"email":"shawn.hunt@example.com","login":{"uuid":"a6ba055a-02e4-4a43-899d-00fbaaff204b","username":"tinyswan311","password":"microsof","salt":"4QZNL1Ym","md5":"b080e7d09d5d83a7dfb7939095caaa7e","sha1":"6196f54f8efbad2342d170321dcfe3ad17d1a273","sha256":"72fb3f4bdeeb4bbc448a5237522daed6760d5c6d0e3673c6be9f49da7811c2f9"},"phone":"04-5374-2944"},{"gender":"female","name":{"title":"Miss","first":"Zoe","last":"Lawson"},"location":{"street":{"number":2950,"name":"Daisy Dr"},"city":"Mildura","state":"Northern Territory","country":"Australia","postcode":9022,"coordinates":{"latitude":"25.5186","longitude":"76.9002"},"timezone":{"offset":"-8:00","description":"Pacific Time (US & Canada)"}},"email":"zoe.lawson@example.com","login":{"uuid":"20ac06e7-a423-49de-ada9-dc39ed3c4579","username":"sadlion806","password":"coolcool","salt":"VUqgrE93","md5":"2a71543ab9816600c6d7f4f0fa9c1497","sha1":"aa8aac9d5bf1f70b6ac5a706538ddd8fc1bfc3d1","sha256":"91dfd886010c8a0b637913432e886db80586879fbb1381c51f479d837aa110d5"},"phone":"01-8794-1034"},{"gender":"female","name":{"title":"Ms","first":"Suzanne","last":"Hawkins"},"location":{"street":{"number":8090,"name":"Samaritan Dr"},"city":"Adelaide","state":"Victoria","country":"Australia","postcode":5022,"coordinates":{"latitude":"-24.0087","longitude":"74.0633"},"timezone":{"offset":"+9:30","description":"Adelaide, Darwin"}},"email":"suzanne.hawkins@example.com","login":{"uuid":"74e77d46-6a39-48e1-93f2-f246def80242","username":"organicgorilla784","password":"stratus","salt":"d3f3K8yE","md5":"e194b19b07f9865a06bcf97d13f1a6dd","sha1":"468992029e7097ac74bb94e72b885d54d95dd5c7","sha256":"2e7ad6d80f55257c2409c2ca595c3591fcfeb7105bc7899ccc284db92ee6a07b"},"phone":"06-6486-7895"},{"gender":"female","name":{"title":"Ms","first":"Yolanda","last":"Medina"},"location":{"street":{"number":6474,"name":"W Gray St"},"city":"Kalgoorlie","state":"Victoria","country":"Australia","postcode":3620,"coordinates":{"latitude":"19.4263","longitude":"-65.9369"},"timezone":{"offset":"+5:45","description":"Kathmandu"}},"email":"yolanda.medina@example.com","login":{"uuid":"7cb4147f-e4b8-4c53-b1db-e72057a974d0","username":"lazybear669","password":"feeling","salt":"xO9zFi6R","md5":"0a68ed4980ec4e1c436b22ac617ade66","sha1":"f5f9e706e847e4ab0b5b4cbf8c77139571bd2e7f","sha256":"ce6876f1e9860f83d7461b28790e2b1c480a0c806cbea78342ece96ca2b91e93"},"phone":"08-2531-7860"},{"gender":"male","name":{"title":"Mr","first":"Ron","last":"Richardson"},"location":{"street":{"number":3400,"name":"Karen Dr"},"city":"Tamworth","state":"Northern Territory","country":"Australia","postcode":7807,"coordinates":{"latitude":"78.2640","longitude":"134.4856"},"timezone":{"offset":"+9:30","description":"Adelaide, Darwin"}},"email":"ron.richardson@example.com","login":{"uuid":"22522ea3-7eff-4e6d-acb0-cf0e1e9f67d9","username":"heavydog518","password":"brewer","salt":"qcS0VRCM","md5":"5c5fd92a778b0a6d7af337481b83e64b","sha1":"35d8150936d2a9fcabebf97d8eac33ee634c541d","sha256":"0a09d0f035548f35809fe39405fa06953c85cd4b059e6b39be568ccb3cd7df11"},"phone":"05-9147-2972"},{"gender":"male","name":{"title":"Mr","first":"Ron","last":"Carpenter"},"location":{"street":{"number":7353,"name":"Avondale Ave"},"city":"Traralgon","state":"Australian Capital Territory","country":"Australia","postcode":8347,"coordinates":{"latitude":"-15.1207","longitude":"93.6543"},"timezone":{"offset":"+1:00","description":"Brussels, Copenhagen, Madrid, Paris"}},"email":"ron.carpenter@example.com","login":{"uuid":"4c0a96ca-cdf9-4578-a8a6-6592aab94577","username":"greenpanda374","password":"band","salt":"1fhddtRX","md5":"c84568e2f0cadc2316efbbc5a8a2c94a","sha1":"e556bba1c2ee9bd5d476a8eb2a0f73c78e76dffe","sha256":"d4f921f99a9a2ddb5a27616155075ed076b802d96c7c7a72ddbbdbd2868c5dd8"},"phone":"06-0496-3706"},{"gender":"female","name":{"title":"Miss","first":"Daisy","last":"Byrd"},"location":{"street":{"number":660,"name":"Blossom Hill Rd"},"city":"Launceston","state":"New South Wales","country":"Australia","postcode":9536,"coordinates":{"latitude":"80.1374","longitude":"175.6978"},"timezone":{"offset":"-6:00","description":"Central Time (US & Canada), Mexico City"}},"email":"daisy.byrd@example.com","login":{"uuid":"9dcae7cd-6af6-482e-b580-ac34c4861cda","username":"organicleopard439","password":"onions","salt":"0nEvaUT2","md5":"627a42eb3e36cf58c3aad0f47d9228dc","sha1":"7291ed77ec6528159bada80063a2c1b02832f55c","sha256":"fff1eefbe1a3c44b4ded2b247d144914e33af2bd6ddaafcc85ff9f2c654379bd"},"phone":"01-4080-1341"},{"gender":"female","name":{"title":"Mrs","first":"Tracey","last":"Lawson"},"location":{"street":{"number":9458,"name":"Pecan Acres Ln"},"city":"Sydney","state":"Tasmania","country":"Australia","postcode":7548,"coordinates":{"latitude":"-72.8131","longitude":"95.7375"},"timezone":{"offset":"-2:00","description":"Mid-Atlantic"}},"email":"tracey.lawson@example.com","login":{"uuid":"794b5e4c-6636-4c60-95d6-dce56c5040bf","username":"angryleopard974","password":"wolf359","salt":"n12jJdJe","md5":"bda37d5bf5de4b9bebedc585e8fa2a55","sha1":"261a4b5de58b177a35fb64972d5c3059e8d399e9","sha256":"07a830e2a232a5b298642f0c53ce723002e0b87815e7facb656720b8612527fc"},"phone":"01-3369-6304"}]';
        $customerProvider->method('getCustomers')->willReturn(json_decode($json, true));
        $container->set('App\Interfaces\CustomerProviderInterface', $customerProvider);
        
        $this->service = $container->get(ImportCustomerServiceInterface::class);
    }
    
    private function truncateDb(): void
    {
        $connection = $this->em->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        
        $query = $databasePlatform->getTruncateTableSQL(
            $this->em->getClassMetadata(Customer::class)->getTableName()
        );
        $connection->executeUpdate($query);
    }
    
    public function testImport(): void
    {
        $this->service->importCustomers(10);
        $count = $this->em->getRepository(Customer::class)->count([]);
        $this->assertEquals(10, $count);
    }
}
