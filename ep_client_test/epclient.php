<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
ini_set('opcache.enable', 0);

if (extension_loaded('apcu')) {
    echo "APC-User cache: " . apc_clear_cache('user') . "\n";
}

if (function_exists('opcache_reset')) {
    // Clear it twice to avoid some internal issues...
    opcache_reset();
    opcache_reset();
}

class SoapClientNG extends \SoapClient{

    public function __doRequest($req, $location, $action, $version = SOAP_1_1){

        $xml = explode("\r\n", parent::__doRequest($req, $location, $action, $version));

        var_dump($xml);
        $response = preg_replace( '/^(\x00\x00\xFE\xFF|\xFF\xFE\x00\x00|\xFE\xFF|\xFF\xFE|\xEF\xBB\xBF)/', "", $xml[5] );
        return $response;

    }

}

class EpClient {
    public $ids;
    public $fileds;
    public $key;
    public $title;
    public $creators_name;
    public $date;
    public $abstract;
    public $url_file;
    public $type;
    public $data_range = "1980-";
    public $order = "-date";




    public function search() {
#        $client = new SoapClient("SearchServ2.wsdl", array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
        $client = new SoapClientNG("http://lib.iitta.gov.ua/SearchServ2.wsdl", array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));




        if (isset($this->data_range)) {
            $result = $client->searchEprint($this->key, $this->fileds, $this->data_range, $this->order);
        } else {
            $result = $client->searchEprint($this->key, $this->fileds, $this->order);
        }
        //debug
        var_dump($client->__getLastRequest());
        return $result;
    }

    public function getMetadata() {
        $client = new SoapClient("MetaDataServ2.wsdl", array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));


        $ObjectXML = '<listId>';
        foreach ($this->ids as $id) {
            $ObjectXML.='<item>' . $id . '</item>';
        }
        $ObjectXML .= '</listId>';
        $ItemObject = new SoapVar($ObjectXML, XSD_ANYXML);

        $result = $client->getEprint($ItemObject);
//        var_dump($client->__getLastRequest());
        return $result;
    }

    public function put() {

        $ObjectXML = '<creators_name>';
        foreach ($this->creators_name as $creators) {
            $ObjectXML.='<item>
            			<given xsi:type="xsd:string">' . $creators['given'] . '</given>
				<family xsi:type="xsd:string">' . $creators['family'] . '</family>
			</item>';
        }
        $ObjectXML.='</creators_name>';

        $ItemObject = new SoapVar($ObjectXML, XSD_ANYXML);
        $client = new SoapClient("putEprints2.wsdl", array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
        $result = $client->putEprint($this->title, $ItemObject, $this->date, $this->abstract, $this->url_file, $this->type);
        var_dump($client->__getLastRequest());
        return $result;
    }

}

$search = new EpClient();

/**
 * search data, return list id
 */
/* search by title */

//$search->fileds = array('fileds' => 'title');
//$search->key = 'MP4 of wgsn';
//#$search->fileds = array('title');
//#$search->key = 'Kirklees College Ceramics Video Sketchbook Kat Morton';
//$search->data_range = '2000-';
//#$search->order='title';
//$result_search = $search->search();

$search->fileds = array('title');
$search->key = 'Googlegggdsfsdf';
$result_search = $search->search();


echo "============ Search result ============== ";
var_dump($result_search);
/* search by creators_name */
//$search->fileds = array('creators_name');
//$search->key = 'Mark Clough';
//order filds
//"field1/-field2/field3"
//Order the search results by field order. prefixing the field name with a "-" results in reverse ordering 
//$search->order = "date";
////data range in form yyyy- or -yyyy or yyyy-zzzz
//$search->data_range = '2009-';



/**
 * get medatada by id item, return list metadata
 */
#$search->ids = array('1');
#$result_metadata = $search->getMetadata();

echo "============= Metadata result =========== ";
var_dump($result_metadata);
/**
 * put metadata 
 */

echo "============= Input items result =========== ";
#$search->title = 'Test soap client php 2';
#$search->abstract = 'testing soap client php';
#$search->creators_name = array(array('family' => 'test family1'), array('family' => 'test family2'));
#$search->date = '2012-09-30';
#$search->type = 'article';
#$search->url_file = 'http://eprints.zu.edu.ua/7799/1/%D0%9C%D0%B0%D1%80%D0%BA%D0%B5%D0%B2%D0%B8%D1%87.pdf';
#$result_put = $search->put();
#var_dump($result_put);


?>
