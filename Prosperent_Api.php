<?php
/**
 * Prosperent API PHP Library
 *
 * NOTE: This PHP code relies on PHP 5.2 and above.
 *
 * NEW BSD LICENSE
 *
 * Copyright (c) 2009-2011, Prosperent, Inc.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *     * Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *
 *     * Neither the name of Prosperent, Inc. nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL PROSPERENT, INC OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * The Prosperent API Class was developed to simplify the process of
 * connecting to the Prosperent API and parsing the results.
 *
 * @copyright Copyright (c) 2009-2011 Prosperent, Inc. (http://prosperent.com)
 * @license   See above (New BSD License)
 * @example   http://prosperent.com/affiliate/api View documentation on Prosperent.com
 * @package   Prosperent_Api
 */
class Prosperent_Api implements Iterator
{
    //constants
    const VERSION = '2.1.9';

    const ENDPOINT_PRODUCT           = 'product';
    const ENDPOINT_UKPRODUCT         = 'ukproduct';
    const ENDPOINT_CAPRODUCT         = 'caproduct';
    const ENDPOINT_COUPON            = 'coupon';
    const ENDPOINT_MERCHANT          = 'merchant';
    const ENDPOINT_BRAND             = 'brand';
    const ENDPOINT_CELEBRITY         = 'celebrity';
    const ENDPOINT_CLICK        	 = 'click';
    const ENDPOINT_COMMISSION        = 'commission';
    const ENDPOINT_TRANSACTION       = 'transaction';
    const ENDPOINT_PAYMENT      	 = 'payment';
    const ENDPOINT_TRENDS            = 'trends';
    const ENDPOINT_TRAVEL			 = 'travel';
    const ENDPOINT_LOCAL			 = 'local';
    const ENDPOINT_CONTENT_ANALYZER	 = 'contentanalyzer';

    public static $endpoints = array(
        self::ENDPOINT_PRODUCT,
        self::ENDPOINT_UKPRODUCT,
        self::ENDPOINT_CAPRODUCT,
        self::ENDPOINT_COUPON,
        self::ENDPOINT_MERCHANT,
        self::ENDPOINT_BRAND,
        self::ENDPOINT_CELEBRITY,
        self::ENDPOINT_CLICK,
        self::ENDPOINT_COMMISSION,
        self::ENDPOINT_TRANSACTION,
        self::ENDPOINT_PAYMENT,
        self::ENDPOINT_TRENDS,
        self::ENDPOINT_TRAVEL,
        self::ENDPOINT_LOCAL,
    	self::ENDPOINT_CONTENT_ANALYZER
    );

    public static $endpointRoutes = array(
        self::ENDPOINT_PRODUCT           => 'search',
        self::ENDPOINT_UKPRODUCT         => 'uk/search',
        self::ENDPOINT_CAPRODUCT         => 'ca/search',
        self::ENDPOINT_COUPON            => 'coupon/search',
        self::ENDPOINT_MERCHANT          => 'merchant',
        self::ENDPOINT_BRAND             => 'brand',
        self::ENDPOINT_CELEBRITY         => 'celebrity',
        self::ENDPOINT_CLICK	         => 'clicks',
        self::ENDPOINT_COMMISSION        => 'commissions',
        self::ENDPOINT_TRANSACTION       => 'commissions/transactions',
        self::ENDPOINT_PAYMENT			 => 'payments',
        self::ENDPOINT_TRENDS            => 'trends',
        self::ENDPOINT_TRAVEL			 => 'travel/search',
        self::ENDPOINT_LOCAL			 => 'local/search',
    	self::ENDPOINT_CONTENT_ANALYZER  => 'content/analyzer'
    );

    /**
     * Image Size
     *
     * @var string
     */
    public static $imageUrlSize = '250x250';

    /**
     * Logo Size
     *
     * @var string
     */
    public static $logoUrlSize  = '120x60';

    /**
     * Photo Size
     *
     * @var string
     */
    public static $photoUrlSize  = '250x250';

    /**
     * cURL options
     *
     * @var unknown_type
     */
    public static $CURL_OPTS = array(
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_FRESH_CONNECT  => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30
    );

    /**
     * API Url
     * @var string
     */
    public static $api_url = 'http://api.prosperent.com/api/';

    /**
     * @var string
     */
    protected $_endpoint;

    /**
     * @var string
     */
    protected $_accessKey;

    /**
     * @var string|array
     */
    protected $_commissionId;

    /**
     * @var string
     */
    protected $_commissionDateRange;

    /**
     * @var string
     */
    protected $_paidDateRange;

    /**
     * @var string
     */
    protected $_expirationDateRange;

    /**
     * @var string
     */
    protected $_starRatingRange;

    /**
     * @var string
     */
    protected $_startDateRange;

    /**
     * @var string
     */
    protected $_clickDateRange;

    /**
     * @var string
     */
    protected $_api_key;

    /**
     * @var string
     */
    protected $_query;

    /**
     * @var array
     */
    protected $_filters = array();

    /**
     * @var string
     */
    protected $_extendedQuery;

    /**
     * @var string
     */
    protected $_extendedSortMode;

    /**
     * @var float
     */
    protected $_minPrice;

    /**
     * @var float
     */
    protected $_maxPrice;

    /**
     * @var float
     */
    protected $_minPriceSale;

    /**
     * @var float
     */
    protected $_maxPriceSale;

    /**
     * @var string
     */
    protected $_sortPrice;

    /**
     * @var string
     */
    protected $_sortPriceSale;

    /**
     * @var float
     */
    protected $_minPaymentAmount;

    /**
     * @var float
     */
    protected $_maxPaymentAmount;

    /**
     * @var float
     */
    protected $_minDaysToSale;

    /**
     * @var float
     */
    protected $_maxDaysToSale;

    /**
     * @var string
     */
    protected $_visitor_ip;

    /**
     * @var string
     */
    protected $_userAgent;

    /**
     * @var string
     */
    protected $_referrer;

    /**
     * @var string
     */
    protected $_location;

    /**
     * @var string
     */
    protected $_serpQuery;

    /**
     * @var string
     */
    protected $_sid;

    /**
     * @var string
     */
    protected $_url;

    /**
     * @var int
     */
    protected $_page = 1;

    /**
     * @var string
     */
    protected $_groupBy;

    /**
     * @var string
     */
    protected $_imageMaskDomain;    
    
    /**
     * @var string
     */
    protected $_clickMaskDomain;    

    /**
     * @var string
     */
    protected $_imageType = 'original';    
    
    /**
     * @var string
     */
    protected $_sortBy;

    /**
     * @var int
     */
    protected $_limit = 10;

    /**
     * @var int
     */
    protected $_imageSize;

    /**
     * @var float
     */
    protected $_relevancyThreshold;

    /**
     * @var bool
     */
    protected $_debugMode = false;

    /**
     * @var bool
     */
    protected $_enableCoupons = false;

    /**
     * @var bool
     */
    protected $_enableFacets = false;

    /**
     * @var bool
     */
    protected $_enableQuerySuggestion = false;

    /**
     * @var bool
     */
    protected $_enableJsonCompression = true;

    /**
     * @var bool
     */
    protected $_enableFullData = true;

    /**
     * Data response results
     *
     * @var array
     */
    protected $_results = array();

    /**
     * Raw string response
     *
     * @var string
     */
    protected $_response;

    /**
     * Decoded JSON response
     *
     * @var array
     */
    protected $_jsonResponse;

    /**
     * API Response Error Array
     *
     * @var null|array
     */
    protected $_errors;

    /**
     * API Response Warning Array
     * @var null|array
     */
    protected $_warnings;

    /**
     * Which array to access for pointer
     *
     * Possible values are: _data, _coupons, _facets
     *
     * @var string
     */
    protected $_arrayAccess = '_data';

    /**
     * Which base url to prepend to affiliate urls
     *
     * Values are null by default, and set from
     * API response
     *
     * @var array
     */
    protected $_baseUrls = array(
        '_data'    => null,
        '_coupons' => null
    );

    /**
     * Attach a image hostname to the image URL (if JSON
     * Compression is enabled)
     *
     * @var null|string
     */
    protected $_imageBaseUrls = null;

    /**
     * Array container for all endpoint
     * keys
     *
     * @var array
     */
    protected $_keys = array(
        '_data'    => null,
        '_coupons' => null,
        '_facets'  => null
    );

    /**
     * Array of key mappers to rebuild
     * the affiliate URLs
     *
     * @var array
     */
    protected $_urlKeyMappers = array(
        '_data'    => null,
        '_coupons' => null
    );

    /**
     * API Response Data Array
     *
     * @var array
     */
    protected $_data = array();

    /**
     * API Response Coupons Array
     *
     * @var array
     */
    protected $_coupons = array();

    /**
     * API Response Facets Array
     *
     * @var array
     */
    protected $_facets = array();

    /**
     * The name of the class to throw exceptions with
     * @var string
     */
    protected $_exceptionHandler = 'Exception';

    /**
     * Caching object
     *
     * @var null|Prosperent_Api_Cache_Core
     */
    protected $_cache;

    /**
     * Is the response a cached object?
     *
     * @var bool
     */
    public $isCachedResponse = false;

    /**
     * Cache keys used to create unique cache key
     *
     * @var array
     */
    public static $cacheKeys = array(
        'accessKey',
        'commissionId',
        'api_key',
        'commissionDateRange',
        'expirationDateRange',
        'startDateRange',
        'starRatingRange',
        'clickDateRange',
        'query',
        //filters are imploded
        'extendedQuery',
        'extendedSortMode',
        'minPrice',
        'maxPrice',
        'minPriceSale',
        'maxPriceSale',
        'sortPrice',
        'sortPriceSale',
        'minPaymentAmount',
        'maxPaymentAmount',
        'minDaysToSale',
        'maxDaysToSale',
        'page',
        'limit',
        'imageSize',
        'debugMode',
        'enableCoupons',
        'enableFacets',
        'enableQuerySuggestion',
        'enableJsonCompression',
        'enableFullData'
    );

    /**
     * Product Image sizes
     *
     * @var array
     */
    protected $_imageSizes = array(
        '250x250',
        '125x125',
        '75x75'
    );

    /**
     * Logo image sizes
     *
     * @var array
     */
    protected $_logoImageSizes = array(
        '120x60',
        '60x30'
    );

    /**
     * Photo image sizes
     *
     * @var array
     */
    protected $_photoImageSizes = array(
        '100x100',
        '250x250',
        '500x500'
    );

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($properties=array())
    {
        if (!function_exists('curl_init'))
        {
            throw new Exception('Prosperent_Api needs the CURL PHP extension.');
        }
        if (!function_exists('json_decode'))
        {
            throw new Exception('Prosperent_Api needs the JSON PHP extension.');
        }

        /*
         * set defaults
         */
        $this->_userAgent  = $_SERVER['HTTP_USER_AGENT'];
        $this->_visitor_ip = $_SERVER['REMOTE_ADDR'];
        $this->_referrer   = $_SERVER['HTTP_REFERER'];
        $this->_location   = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        /*
         * if a query can be retrieved from the
         * referrer, set the serpQuery
         */
        if (false != ($query = self::getQueryFromReferrer($this->_referrer)))
        {
            $this->_serpQuery = $query;
        }

        if (is_array($properties))
        {
            $this->setProperties($properties);
        }

        /*
         * set caching object if enabled
         */
        if (!$this->_isFalse($properties['cacheBackend']))
        {
            $this->_cache = Prosperent_Api_Cache::factory(
                $properties['cacheBackend'],
                (array) $properties['cacheOptions']
            );
        }
    }

    /**
     * Overload to getData() and getCoupons()
     * -> getNode()
     *
     * Overloads to filter methods as well
     *
     * @param  string $method getData or getCoupon, or filter*
     * @param  array  $args   ignored
     * @return array
     */
    public function __call($method, $args)
    {
        /*
         * if this is a filter call, add to the filter stack
         */
        if (preg_match('/^(g|s)et_(filter[a-z]{1,})$/i', $method, $regs))
        {
            /*
             * if get, the return the value
             */
            if ('g' == $regs[1])
            {
                return $this->_filters[$regs[2]];
            }

            $this->_filters[$regs[2]] = (array) $args[0];

            return $this;
        }

        // if requesting array of specific node data
        if (preg_match('/^get(.+)Data$/i', $method, $regs))
        {
            $node = strtolower($regs[1]);

            if ($node != 'all')
            {
                $this->getAllData();
                $nodeData = $this->_resultsNode[strtolower($regs[1])];

                // if unique is set to true
                if ($args[0] == true)
                {
                    $nodeData = array_unique((array) $nodeData);
                }

                return $nodeData ? $nodeData : array();
            }
        }

        $accessPortion = '_' . strtolower(str_replace('get', '', $method));

        /*
         * reset node back to _data since coupons are in the data node
         */
        if ('_coupons' == $accessPortion && $this->getEndpoint() == self::ENDPOINT_COUPON)
        {
            $accessPortion = '_data';
        }

        /*
         * set the array access pointer
         */
        $this->_arrayAccess = $accessPortion;

        if (stristr($method, 'facet'))
        {
            if (!is_string($args[0]))
            {
                self::throwException('You must pass a valid facet to return');
            }

            //_facet -> _facets
            $accessPortion .= 's';
            $subNode = $args[0];
            $this->_arrayAccess = $accessPortion . '-' . $subNode;
        }

        if (!property_exists($this, $accessPortion))
        {
            self::throwException('Call to undefined method Prosperent_Api::' . $method . '()', $this->get_exceptionHandler());
        }

        /*
         * if json compression is off, then simply return
         * the node since it's already in its prepared
         * state
         */
        if (false == $this->get_enableJsonCompression())
        {
            if (is_string($subNode))
            {
                $accessPortion = $this->$accessPortion;
                return $accessPortion[$subNode];
            }

            return $this->$accessPortion;
        }

        return $this->getNode((true === $args[0] ? true : false));
    }

    /**
     * Set object state
     *
     * @param  array $properties
     * @return Default_Model_Users_User
     */
    public function setProperties(array $properties)
    {
        $methods = get_class_methods($this);
        foreach ($properties as $key => $value)
        {
            $method = 'set_' . $key;
            if (in_array($method, $methods) || stristr($method, 'filter'))
            {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Get all properties in array format
     *
     * @return array
     */
    public function getProperties()
    {
        $methods = get_class_methods($this);
        $propArray = array();
        foreach ($methods as $method)
        {
            if (preg_match('/^get_/', $method))
            {
                $propArray[str_replace('get_', '', $method)] = $this->$method();
            }
        }

        if ($this->hasFilters())
        {
            foreach ($this->_filters as $key => $value)
            {
                $propArray[$key] = $value;
            }
        }

        return $propArray;
    }

    /**
     * Return instanciated object
     *
     * @param  string $endpointName
     * @param  array $properties
     * @return Prosperent_Api
     */
    public static function endpoint($endpointName, $properties = array())
    {
        $properties['endpoint'] = $endpointName;

        $class = get_called_class();
        $api = new $class($properties);

        return $api;
    }

    /**
     * Returns endpoint route
     *
     * @param  string $endpoint
     * @return string
     */
    public function getEndpointRoute($endpoint = null)
    {
        $endpoint = $endpoint ? $endpoint : self::ENDPOINT_PRODUCT;

        return self::$endpointRoutes[$endpoint];
    }

    /**
     * Throws an exception
     *
     * @param  string $msg
     */
    public static function throwException($msg, $exceptionClass = 'Exception')
    {
        throw new $exceptionClass($msg);
    }

    /**
     * Logs an impression
     *
     * @return array
     */
    public function log()
    {
        //do we have the visitor IP?
        if (!$this->get_visitor_ip())
        {
            self::throwException('A visitor IP must be passed to the logger', $this->get_exceptionHandler());
        }

        $url = $this->getUrl($this->getProperties(), 'log');

        return $this->makeRequest($url);
    }

    /**
     * Searches API and returns parsed JSON response
     *
     * @param  string $endpoint
     * @return array
     */
    public function fetch($endpoint = null)
    {
        if (!$this->get_endpoint())
        {
            $this->set_endpoint($endpoint);
        }

        //do we have a query?
        if (in_array($this->_endpoint, array(self::ENDPOINT_PRODUCT, self::ENDPOINT_UKPRODUCT, self::ENDPOINT_CAPRODUCT)) && (!$this->get_query() && !$this->hasFilters() && !$this->get_extendedQuery()))
        {
            self::throwException('No query/filters or extendedQuery were specified for the Prosperent API', $this->get_exceptionHandler());
        }

        $url = $this->getUrl($this->getProperties(), $this->getEndpointRoute($this->get_endpoint()));

        return $this->makeRequest($url);
    }

    /**
     * Searches API for products
     *
     * @return array
     */
    public function fetchProducts()
    {
        return $this->fetch(self::ENDPOINT_PRODUCT);
    }

    /**
     * Searches API for products
     *
     * @return array
     */
    public function fetchUkProducts()
    {
        return $this->fetch(self::ENDPOINT_UKPRODUCT);
    }

    /**
     * Searches API for products
     *
     * @return array
     */
    public function fetchCaProducts()
    {
        return $this->fetch(self::ENDPOINT_CAPRODUCT);
    }


    /**
     * Searches API for coupons
     *
     * @return array
     */
    public function fetchCoupons()
    {
        return $this->fetch(self::ENDPOINT_COUPON);
    }

    /**
     * Searches API for clicks
     *
     * @return array
     */
    public function fetchClicks()
    {
        return $this->fetch(self::ENDPOINT_CLICK);
    }

    /**
     * Searches API for payments
     *
     * @return array
     */
    public function fetchPayments()
    {
        return $this->fetch(self::ENDPOINT_PAYMENT);
    }

    /**
     * Searches API for commissions
     *
     * @return array
     */
    public function fetchCommissions()
    {
        return $this->fetch(self::ENDPOINT_COMMISSION);
    }

    /**
     * Searches API for commission transactions
     *
     * @return array
     */
    public function fetchCommissionTransactions()
    {
        return $this->fetch(self::ENDPOINT_TRANSACTION);
    }

    /**
     * Searches API for merchants
     *
     * @param  array $merchants
     * @return array
     */
    public function fetchMerchants(array $merchants = array())
    {
        if (count($merchants))
        {
            $this->set_filterMerchant($merchants);
        }

        return $this->fetch(self::ENDPOINT_MERCHANT);
    }

    /**
     * Search the merchant endpoint for a
     * specific merchant
     *
     * @param  null|string|array $merchant
     * @return array
     */
    public function fetchMerchant($merchant = null)
    {
        return $this->fetchMerchants((array) $merchant);
    }

    /**
     * Searches API for brands
     *
     * @param  array $brands
     * @return array
     */
    public function fetchBrands(array $brands = array())
    {
        if (count($brands))
        {
            $this->set_filterBrand($brands);
        }

        return $this->fetch(self::ENDPOINT_BRAND);
    }

    /**
     * Search the brand endpoint for a
     * specific brand
     *
     * @param  null|string|array $brand
     * @return array
     */
    public function fetchBrand($brand = null)
    {
        return $this->fetchBrands((array) $brand);
    }

    /**
     * Searches API for celebrities
     *
     * @param  array $celebrities
     * @return array
     */
    public function fetchCelebrities(array $celebrities = array())
    {
        if (count($celebrities))
        {
            $this->set_filterCelebrity($celebrities);
        }

        return $this->fetch(self::ENDPOINT_CELEBRITY);
    }

    /**
     * Search the celebrity endpoint for a
     * specific celebrity
     *
     * @param  null|string|array $celebrity
     * @return array
     */
    public function fetchCelebrity($celebrity = null)
    {
        return $this->fetchCelebrities((array) $celebrity);
    }

    /**
     * Search the trends endpoint
     *
     * @return array
     */
    public function fetchTrends()
    {
        return $this->fetch(self::ENDPOINT_TRENDS);
    }

    /**
     * Search the travel endpoint
     *
     * @return array
     */
    public function fetchTravel()
    {
        return $this->fetch(self::ENDPOINT_TRAVEL);
    }

    /**
     * Search the local endpoint
     *
     * @return array
     */
    public function fetchLocal()
    {
        return $this->fetch(self::ENDPOINT_LOCAL);
    }

    /**
     * Search the content analyzer endpoint
     *
     * @return array
     */
    public function fetchContentanalyzer()
    {
    	return $this->fetch(self::ENDPOINT_CONTENT_ANALYZER);
    }

    /**
     * Determines if any filters were applied
     *
     * @return boolean
     */
    public function hasFilters()
    {
        if (count($this->_filters) > 0)
        {
            return true;
        }

        return false;
    }

    /**
     * Makes an HTTP request to the Prosperent API
     *
     * @param  string $url
     * @return false|array
     */
    protected function makeRequest($url)
    {
        //create the cache key
        $cacheKey = 'prosperent_api_cache::';

        foreach (self::$cacheKeys as $key)
        {
            $method = 'get_' . $key;
            $value  = $this->$method();
            $cacheKey .= $key . ':' . (is_array($value) ? implode('-', $value) : (string) $value);
        }
        if (is_array($this->_filters) && count($this->_filters) > 0)
        {
            foreach ($this->_filters as $key => $value)
            {
                $cacheKey .= $key . ':' . (is_array($value) ? implode('-', $value) : (string) $value);
            }
        }
        $cacheKey = md5($cacheKey);

        /*
         * if cache is enabled, attempt to load the document
         */
        if (is_object($this->_cache) && null != ($result = $this->_cache->load($cacheKey)))
        {
            $this->isCachedResponse = true;
        }
        /*
         * if fetch has already been run, return the json
         * response
         */
        else if ($cacheKey == $this->_cacheKey)
        {
            return $this->getJsonResponse();
        }
        /*
         * run cURL as usual
         */
        else
        {
            //init curl
            $ch = curl_init();

            //init options
            $opts = self::$CURL_OPTS;
            $opts[CURLOPT_URL] = $url;

            //disable the 'Expect: 100-continue' behaviour. This causes CURL to wait
            // for 2 seconds if the server does not support this header.
            if (isset($opts[CURLOPT_HTTPHEADER]))
            {
                $existing_headers = $opts[CURLOPT_HTTPHEADER];
                $existing_headers[] = 'Expect:';
                $opts[CURLOPT_HTTPHEADER] = $existing_headers;
            }
            else
            {
                $opts[CURLOPT_HTTPHEADER] = array('Expect:');
            }

            //set curl options
            curl_setopt_array($ch, $opts);

            //send request
            $result = curl_exec($ch);

            //check for false response
            if ($result === false)
            {
                $error = curl_error($ch);

                if (stristr($error, 'Operation timed out'))
                {
                    $result = array(
                        'errors'   => array(
                            array(
                                'code' => 'timeout',
                                'msg'  => 'The request to timed out'
                            )
                        ),
                        'warnings' => array(),
                        'data'     => array()
                    );
                }
                else
                {
                    self::throwException($error, $this->get_exceptionHandler());
                }
            }

            //set the info to a public property
            $this->curlInfo = curl_getinfo($ch);

            //deconstruct the url
            if ($url = @parse_url($originalUrl = $this->curlInfo['url']))
            {
                $this->curlInfo['url'] = $url;
                $this->curlInfo['url']['fullUrl'] = $originalUrl;
                @parse_str($this->curlInfo['url']['query'], $this->curlInfo['url']['query']);
            }

            //close curl
            curl_close($ch);

            //return false if the result is empty
            if (!is_array($result) && !strlen($result))
            {
                return false;
            }

            //save the cache
            if (is_object($this->_cache) && !(is_array($result) && count($result['errors']) > 0))
            {
                $this->_cache->save($result, $cacheKey);
                $this->_cacheKey = $cacheKey;
            }
        }

        //parse result
        try
        {
            $this->_response = $result;

            if (!is_array($result))
            {
                $result = json_decode($result, true);
            }

            $this->_jsonResponse = $result;

            if (is_array($result))
            {
                if (count($result['errors']))
                {
                    $this->_errors = $result['errors'];
                }

                if (count($result['warnings']))
                {
                    $this->_warnings = $result['warnings'];
                }

                if ($this->get_enableJsonCompression())
                {
                    /*
                     * set the endpoint keys if they were returned
                     */
                    foreach ($this->_keys as $key => $v)
                    {
                        $keyName = str_replace('_', '', $key) . 'Keys';
                        $this->_keys[$key] = (is_array($result[$keyName]) ? $result[$keyName] : null);
                    }

                    /*
                     * set the base urls
                     */
                    foreach ($this->_baseUrls as $key => $v)
                    {
                        $keyName = str_replace('_', '', $key) . 'BaseUrl';
                        $this->_baseUrls[$key] = (!empty($result[$keyName]) ? $result[$keyName] : null);
                    }

                    /*
                     * set the affiliateUrlKeys
                     */
                    foreach ($this->_urlKeyMappers as $key => $v)
                    {
                        $keyName = str_replace('_', '', $key) . 'UrlKeyMapper';
                        $this->_urlKeyMappers[$key] = (!empty($result[$keyName]) ? $result[$keyName] : null);
                    }

                    /*
                     * set the imageBaseUrls
                     */
                    $this->_imageBaseUrls = ($result['imageBaseUrls'] ? $result['imageBaseUrls'] : null);
                }

                $this->_data             = (is_array($result['data'])    ? $result['data']    : array());
                $this->_coupons          = (is_array($result['coupons']) ? $result['coupons'] : array());
                $this->_facets           = (is_array($result['facets'])  ? $result['facets']  : array());
            }
        }
        catch (Exception $e)
        {
            self::throwException('The Prosperent API response could not be decoded: ' . $e->getMessage(), $this->get_exceptionHandler());
        }

        return $result;
    }

    /**
     * Build the URL with the given parameters.
     *
     * @param array  $params
     * @param string $path
     */
    protected function getUrl($params=array(), $path = null)
    {
        if (!$path)
        {
            $path = $this->getEndpointRoute(self::ENDPOINT_PRODUCT);
        }

        $url = self::$api_url;

        //append class version with params
        $params['v'] = self::VERSION;

        //no need to send certain parameters
        unset($params['exceptionHandler']);

        //set the path
        if ($path)
        {
            if ($path[0] === '/')
            {
                $path = substr($path, 1);
            }

            $url .= $path;
        }

        if ($params)
        {
            $url .= '?' . http_build_query($params, null, '&');
        }

        return $url;
    }

    /**
     * Returns the raw string response
     *
     * @return null|string
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * Returns the decoded JSON response
     *
     * @return array
     */
    public function getJsonResponse()
    {
        return $this->_jsonResponse;
    }

    /**
     * Returns all data
     *
     * @return array
     */
    public function getAllData()
    {
        if (!count($this->_results))
        {
            foreach ($this->getData() as $key => $v)
            {
                $this->_results[$key] = $v;

                foreach ((array) $v as $node => $value)
                {
                    $node = strtolower(preg_replace('/_/', '', $node));
                    $this->_resultsNode[$node][] = $value;
                }
            }
        }

        return $this->_results;
    }

    /**
     * Returns all coupons
     *
     * @return array
     */
    public function getAllCoupons()
    {
        $array = array();

        foreach ($this->getCoupons() as $key => $v)
        {
            $array[$key] = $v;
        }

        return $array;
    }

    /**
     * Returns all facets
     *
     * @param  string $facet Returns a specific facet
     * @return array
     */
    public function getFacets($facet=null)
    {
        $array = array();

        if (null != ($key = $facet))
        {
            if (!isset($this->_facets[$facet]))
            {
                return array();
            }

            foreach ($this->getFacet($key) as $facet)
            {
                $array[] = $facet;
            }
        }
        else
        {
            foreach ($this->_facets as $key => $v)
            {
                foreach ($this->getFacet($key) as $facet)
                {
                    $array[$key][] = $facet;
                }
            }
        }

        return $array;
    }

    /**
     * Returns the country code found
     *
     * @return null|string
     */
    public function getCountryCode()
    {
        return (isset($this->_jsonResponse['countryCode']) ? $this->_jsonResponse['countryCode'] : null);
    }

    /**
     * Returns the number of records in this response
     *
     * @return int
     */
    public function getTotalRecords()
    {
        return (int) $this->_jsonResponse['totalRecords'];
    }

    /**
     * Returns the number of records available in this response
     *
     * @return int
     */
    public function getTotalRecordsAvailable()
    {
        return (int) $this->_jsonResponse['totalRecordsAvailable'];
    }

    /**
     * Returns the number of records found
     *
     * @return int
     */
    public function getTotalRecordsFound()
    {
        return (int) $this->_jsonResponse['totalRecordsFound'];
    }

    /**
     * Returns the extendedQuery used to search
     *
     * @return null|string
     */
    public function getExtendedQuery()
    {
        return (isset($this->_jsonResponse['extendedQuery']) ? $this->_jsonResponse['extendedQuery'] : null);
    }

    /**
     * Returns the query suggestion in the event
     * of a misspelling
     *
     * @return null|string
     */
    public function getQuerySuggestion()
    {
        return (isset($this->_jsonResponse['querySuggestion']) ? $this->_jsonResponse['querySuggestion'] : null);
    }

    /**
     * Returns any discovered errors
     *
     * @return null|array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Are there errors?
     *
     * @return bool
     */
    public function hasErrors()
    {
        return (is_array($this->_errors) && count($this->_errors));
    }

    /**
     * Returns any discovered warnings
     *
     * @return null|array
     */
    public function getWarnings()
    {
        return $this->_warnings;
    }

    /**
     * Are there warnings?
     *
     * @return bool
     */
    public function hasWarnings()
    {
        return (is_array($this->_warnings) && count($this->_warnings));
    }

    /**
     * Sets a date range on either the commission or click date range
     *
     * @param  string $type
     * @param  mixed  $startDate
     * @param  mixed  $endDate
     * @return Prosperent_Api
     */
    public function setDateRange($type, $startDate, $endDate=null)
    {
        $method = 'set_' . $type . 'DateRange';

        /*
         * ignore call if the method doesn't exist
         */
        if (!method_exists($this, $method))
        {
            return $this;
        }

        /*
         * detect date string, otherwise throw error
         *
         * it should be in Y-m-d or Ymd format
         */
        foreach (array('startDate', 'endDate') as $date)
        {
            //if the end date is not set, then set to yesterday
            if ('endDate' == $date && !${$date})
            {
                ${$date} = $endDate = date('Ymd', strtotime($type == 'commission' ? 'yesterday' : 'today'));
            }

            if (!preg_match('/^20[0-9]{2}-?[0-9]{2}-?[0-9]{2}$/', ${$date}))
            {
                self::throwException('The ' . $type . ' ' . $date . ' must be in Ymd (' . date('Ymd') . ') format');
            }

            ${$date} = str_replace('-', '', ${$date});
        }

        if ($type == 'commission')
        {
            /*
             * if the end date is >= today, set to yesterday
             */
            if ($endDate >= date('Ymd'))
            {
                $endDate = date('Ymd', strtotime('yesterday'));
            }

            /*
             * if the start date is > the end date, equal out
             */
            if ($startDate > $endDate)
            {
                $startDate = $endDate;
            }
        }

        $this->$method(
            $startDate
            . ','
            . $endDate
        );

        return $this;
    }

    /**
     * Sets a range on the star rating range
     *
     * @param  string $type
     * @param  mixed  $low
     * @param  mixed  $high
     * @return Prosperent_Api
     */
    public function setRange($type, $low, $high=null)
    {
        $method = 'set_' . $type . 'Range';

        /*
         * ignore call if the method doesn't exist
         */
        if (!method_exists($this, $method))
        {
            return $this;
        }

        foreach (array('low', 'high') as $star)
        {
            //if the high star is not set, then set to to low
            if ('high' != $star)
            {
                $high = $low;
            }

            if (!preg_match('/^\d(\.)?\d?$/', $high))
            {
                self::throwException('The ' . $type . ' ' . $high . ' must be a float value.');
            }
        }

        $this->$method(
            (float)$low
            . ','
            . (float)$high
        );

        return $this;
    }

    /**
     * Returns the requested array node
     *
     * @param  bool $returnRawArray
     * @return null|array
     */
    protected function getNode($returnRawArray=false)
    {
        if (true === $returnRawArray)
        {
            $nodeName = $this->_arrayAccess;
            return $this->$nodeName;
        }

        /*
         * return this object to access Object Iterator
         */
        return $this;
    }

    /**
     * Returns the node and sub node
     * from a given string
     *
     * @param  string $nodeName
     * @return array
     */
    protected function getNodeParts($nodeName)
    {
        $parts = explode('-', $nodeName);
        return array('node' => $parts[0], 'subNode' => $parts[1]);
    }

    /**
     * Rewinds data array
     *
     * @see    Iterator::rewind()
     * @return void
     */
    public function rewind()
    {
        $nodeName = $this->_arrayAccess;

        if (strstr($nodeName, '-'))
        {
            extract($this->getNodeParts($nodeName));
            $array = $this->$node;
            $this->facetPointer[$subNode] = 0;
        }
        else
        {
            reset($this->$nodeName);
        }
    }

    /**
     * Returns current node from array
     *
     * @see    Iterator::current()
     * @return array
     */
    public function current()
    {
        /*
         * get current node
         */
        $nodeName = $this->_arrayAccess;

        if (strstr($nodeName, '-'))
        {
            extract($this->getNodeParts($nodeName));
            $array = $this->$node;
            $current = $array[$subNode][(int) $this->facetPointer[$subNode]];
        }
        else
        {
            $current = current($this->$nodeName);
        }

        /*
         * replace -facet
         */
        $nodeName = preg_replace('/-[a-z]+$/i', '', $nodeName);

        /*
         * set keys back in place
         */
        if (is_array($current) && is_array($this->_keys[$nodeName]))
        {
        	$current = array_combine($this->_keys[$nodeName], $current);
        }

        /*
         * set the image urls back up
         */

        /*if (isset($current['image_url']))
        {
            $defaultImageSize = self::$imageUrlSize;
            $imageSizes       = $this->_imageSizes;

            if (in_array($this->_endpoint, array(self::ENDPOINT_CELEBRITY)))
            {
                $defaultImageSize = self::$photoUrlSize;
                $imageSizes       = $this->_photoImageSizes;
            }

            if (in_array($this->_endpoint, array(self::ENDPOINT_COUPON)))
            {
                $defaultImageSize = self::$logoUrlSize;
                $imageSizes       = $this->_logoImageSizes;
            }

            shuffle($this->_imageBaseUrls);
            $imageUrl = $current['image_url'];
            $size = in_array(($size = $this->get_imageSize()), $imageSizes) ? $size : $defaultImageSize;
            $current['image_url'] = $this->_imageBaseUrls[0] . $size . '/' . $imageUrl;
        }*/

        /*
         * set the logo urls back up
         */
        /*if (isset($current['logoUrl']))
        {
            //shuffle($this->_imageBaseUrls);
            $logoUrl = $current['logoUrl'];
            $size = in_array(($size = $this->get_imageSize()), $this->_logoImageSizes) ? $size : self::$logoUrlSize;
            $current['logoUrl'] = $this->_imageBaseUrls[0] . $size . '/' . $logoUrl;
        }*/

        /*
         * set the affiliate url back up
         */
        if (isset($current['affiliate_url']))
        {
            $affiliateUrl = $this->_baseUrls[$nodeName];

            $params = array();
            foreach ($this->_urlKeyMappers[$nodeName] as $key => $value)
            {
                $params[$key] = $current[$value];
            }

            /*
             * add in sid if it's set
             */
            if (null != ($sid = $this->get_sid()))
            {
                $params['sid'] = $sid;
            }

            /*
             * add in referrer, location and serpQuery
             */
            foreach (array('referrer', 'location', 'serpQuery') as $type)
            {
                $getMethod = 'get_' . $type;
                if (null == ($value = $this->$getMethod()))
                {
                    switch($type)
                    {
                        case 'referrer':
                            $value = $_SERVER['HTTP_REFERER'];
                            $this->set_referrer($value);
                            break;
                        case 'location':
                            $value = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                            $this->set_location($value);
                            break;
                        case 'serpQuery':
                            $value = self::getQueryFromReferrer($this->get_referrer());
                            $this->set_serpQuery($value);
                            break;

                    }
                }

                if (null != ($value = $this->$getMethod()))
                {
                    $params[$type] = $this->$getMethod();
                }
            }

            /*
             * add in the query used
             */
            if (null != ($query = $this->get_query()))
            {
                $params['query'] = $query;
            }
            else if (null != ($query = $this->_filters['keyword']))
            {
                $params['query'] = $query;
            }

            /*
             * append params
             */
            $affiliateUrl .= '/?' . http_build_query($params);

            $current['affiliate_url'] = $affiliateUrl;
        }

        return $current;
    }

    /**
     * Returns the key of the current
     * row in the array
     *
     * @see    Iterator::key()
     * @return mixed
     */
    public function key()
    {
        $nodeName = $this->_arrayAccess;

        if (strstr($nodeName, '-'))
        {
            extract($this->getNodeParts($nodeName));
            $array = $this->$node;
            return (int) $this->facetPointer[$subNode];
        }

        return key($this->$nodeName);
    }

    /**
     * Returns next row in array
     *
     * @see    Iterator::next()
     * @return mixed
     */
    public function next()
    {
        $nodeName = $this->_arrayAccess;

        if (strstr($nodeName, '-'))
        {
            extract($this->getNodeParts($nodeName));
            $array = $this->$node;
            return (int) ++$this->facetPointer[$subNode];
        }

        return next($this->$nodeName);
    }

    /**
     * Validates whether we've reached the end of
     * the array
     *
     * @see    Iterator::valid()
     * @return bool
     */
    public function valid()
    {
        $nodeName = $this->_arrayAccess;

        if (strstr($nodeName, '-'))
        {
            extract($this->getNodeParts($nodeName));
            $array = $this->$node;
            return isset($array[$subNode][(int) $this->facetPointer[$subNode]]);
        }

        $key = key($this->$nodeName);
        return ($key !== null && $key !== false);
    }

    /**
     * Returns endpoint name
     *
     * @return string
     */
    public function get_endpoint()
    {
        return $this->_endpoint;
    }

    /**
     * Set endpoint
     *
     * @param  string $endpoint
     * @return Prosperent_Api
     */
    public function set_endpoint($endpoint)
    {
        if (!in_array($endpoint, self::$endpoints))
        {
            $endpoint = self::ENDPOINT_PRODUCT;
        }

        $this->_endpoint = (string) $endpoint;
        return $this;
    }

    /**
     * Get the name of the exception handler
     *
     * @return string
     */
    public function get_exceptionHandler()
    {
        return $this->_exceptionHandler;
    }

    /**
     * Sets the name of the exception handler to use
     *
     * @param  string $exceptionHandlerName
     * @return Prosperent_Api
     */
    public function set_exceptionHandler($exceptionHandlerName)
    {
        $this->_exceptionHandler = (string) $exceptionHandlerName;
        return $this;
    }

    /**
     * Get access key
     *
     * @return null|string
     */
    public function get_accessKey()
    {
        return $this->_accessKey;
    }

    /**
     * Set access key
     *
     * @param  string $accessKey
     * @return Prosperent_Api
     */
    public function set_accessKey($accessKey)
    {
        $this->_accessKey = (string) $accessKey;
        return $this;
    }

    /**
     * Get commissionId
     *
     * @return null|string|array
     */
    public function get_commissionId()
    {
        return $this->_commissionId;
    }

    /**
     * Set commissionId
     *
     * @param  string|array $commissionId
     * @return Prosperent_Api
     */
    public function set_commissionId($commissionId)
    {
        $this->_commissionId = $commissionId;
        return $this;
    }

    /**
     * Get api key
     *
     * @return null|string
     */
    public function get_api_key()
    {
        return $this->_api_key;
    }

    /**
     * Set api key
     *
     * @param  string $api_key
     * @return Prosperent_Api
     */
    public function set_api_key($api_key)
    {
        $this->_api_key = (string) $api_key;
        return $this;
    }

    /**
     * Get commissionDateRange
     *
     * @return null|string
     */
    public function get_commissionDateRange()
    {
        return $this->_commissionDateRange;
    }

    /**
     * Set commissionDateRange
     *
     * @param  string $commissionDateRange
     * @return Prosperent_Api
     */
    public function set_commissionDateRange($commissionDateRange)
    {
        $this->_commissionDateRange = (string) $commissionDateRange;
        return $this;
    }

    /**
     * Get expirationDateRange
     *
     * @return null|string
     */
    public function get_expirationDateRange()
    {
        return $this->_expirationDateRange;
    }

    /**
     * Set expirationDateRange
     *
     * @param  string $expirationDateRange
     * @return Prosperent_Api
     */
    public function set_expirationDateRange($expirationDateRange)
    {
        $this->_expirationDateRange = (string) $expirationDateRange;
        return $this;
    }

    /**
     * Get startDateRange
     *
     * @return null|string
     */
    public function get_startDateRange()
    {
        return $this->_startDateRange;
    }

    /**
     * Set startDateRange
     *
     * @param  string $startDateRange
     * @return Prosperent_Api
     */
    public function set_startDateRange($startDateRange)
    {
        $this->_startDateRange = (string) $startDateRange;
        return $this;
    }

    /**
     * Get starRatingRange
     *
     * @return null|string
     */
    public function get_starRatingRange()
    {
        return $this->_starRatingRange;
    }

    /**
     * Set starRatingRange
     *
     * @param  string $starRatingRange
     * @return Prosperent_Api
     */
    public function set_starRatingRange($starRatingRange)
    {
        $this->_starRatingRange = (string) $starRatingRange;
        return $this;
    }

    /**
     * Get clickDateRange
     *
     * @return null|string
     */
    public function get_clickDateRange()
    {
        return $this->_clickDateRange;
    }

    /**
     * Set clickDateRange
     *
     * @param  string $clickDateRange
     * @return Prosperent_Api
     */
    public function set_clickDateRange($clickDateRange)
    {
        $this->_clickDateRange = (string) $clickDateRange;
        return $this;
    }

    /**
     * Get query
     *
     * @return null|string
     */
    public function get_query()
    {
        return $this->_query;
    }

    /**
     * Set query
     *
     * @param  string $query
     * @return Prosperent_Api
     */
    public function set_query($query)
    {
        $this->_query = (string) $query;
        return $this;
    }

    /**
     * Get extendedQuery
     *
     * @return null|string
     */
    public function get_extendedQuery()
    {
        return $this->_extendedQuery;
    }

    /**
     * Set extendedQuery
     *
     * @param  string $extendedQuery
     * @return Prosperent_Api
     */
    public function set_extendedQuery($extendedQuery)
    {
        $this->_extendedQuery = (string) $extendedQuery;
        return $this;
    }

    /**
     * Get extendedSortMode
     *
     * @return null|string
     */
    public function get_extendedSortMode()
    {
        return $this->_extendedSortMode;
    }

    /**
     * Set extendedSortMode
     *
     * @param  string $extendedSortMode
     * @return Prosperent_Api
     */
    public function set_extendedSortMode($extendedSortMode)
    {
        $this->_extendedSortMode = (string) $extendedSortMode;
        return $this;
    }

    /**
     * Get minPrice
     *
     * @return null|string
     */
    public function get_minPrice()
    {
        return $this->_minPrice;
    }

    /**
     * Set minPrice
     *
     * @param  mixed $minPrice
     * @return Prosperent_Api
     */
    public function set_minPrice($minPrice)
    {
        $this->_minPrice = number_format($minPrice, 2, '.', '');
        return $this;
    }

    /**
     * Get maxPrice
     *
     * @return null|string
     */
    public function get_maxPrice()
    {
        return $this->_maxPrice;
    }

    /**
     * Set maxPrice
     *
     * @param  mixed $maxPrice
     * @return Prosperent_Api
     */
    public function set_maxPrice($maxPrice)
    {
        $this->_maxPrice = number_format($maxPrice, 2, '.', '');
        return $this;
    }

    /**
     * Get minPriceSale
     *
     * @return null|string
     */
    public function get_minPriceSale()
    {
        return $this->_minPriceSale;
    }

    /**
     * Set minPriceSale
     *
     * @param  mixed $minPriceSale
     * @return Prosperent_Api
     */
    public function set_minPriceSale($minPriceSale)
    {
        $this->_minPriceSale = number_format($minPriceSale, 2, '.', '');
        return $this;
    }

    /**
     * Get maxPriceSale
     *
     * @return null|string
     */
    public function get_maxPriceSale()
    {
        return $this->_maxPriceSale;
    }

    /**
     * Set maxPriceSale
     *
     * @param  mixed $maxPriceSale
     * @return Prosperent_Api
     */
    public function set_maxPriceSale($maxPriceSale)
    {
        $this->_maxPriceSale = number_format($maxPriceSale, 2, '.', '');
        return $this;
    }

    /**
     * Get sortPrice
     *
     * @return null|string
     */
    public function get_sortPrice()
    {
        return $this->_sortPrice;
    }

    /**
     * Set sortPrice
     *
     * @param  string $sortPrice
     * @return Prosperent_Api
     */
    public function set_sortPrice($sortPrice)
    {
        $sortPrice = strtoupper($sortPrice);

        if (!in_array($sortPrice, array('ASC', 'DESC')))
        {
            $this->_sortPrice = null;
            return $this;
        }

        $this->_sortPrice = $sortPrice;
        return $this;
    }

    /**
     * Get sortPriceSale
     *
     * @return null|string
     */
    public function get_sortPriceSale()
    {
        return $this->_sortPriceSale;
    }

    /**
     * Set sortPriceSale
     *
     * @param  string $sortPriceSale
     * @return Prosperent_Api
     */
    public function set_sortPriceSale($sortPriceSale)
    {
        $sortPriceSale = strtoupper($sortPriceSale);

        if (!in_array($sortPriceSale, array('ASC', 'DESC')))
        {
            $this->_sortPriceSale = null;
            return $this;
        }

        $this->_sortPriceSale = $sortPriceSale;
        return $this;
    }

    /**
     * Get minPaymentAmount
     *
     * @return null|string
     */
    public function get_minPaymentAmount()
    {
        return $this->_minPaymentAmount;
    }

    /**
     * Set minPaymentAmount
     *
     * @param  mixed $minPaymentAmount
     * @return Prosperent_Api
     */
    public function set_minPaymentAmount($minPaymentAmount)
    {
        $this->_minPaymentAmount = number_format($minPaymentAmount, 2, '.', '');
        return $this;
    }

    /**
     * Get maxPaymentAmount
     *
     * @return null|string
     */
    public function get_maxPaymentAmount()
    {
        return $this->_maxPaymentAmount;
    }

    /**
     * Set maxPaymentAmount
     *
     * @param  mixed $maxPaymentAmount
     * @return Prosperent_Api
     */
    public function set_maxPaymentAmount($maxPaymentAmount)
    {
        $this->_maxPaymentAmount = number_format($maxPaymentAmount, 2, '.', '');
        return $this;
    }

    /**
     * Get minDaysToSale
     *
     * @return null|string
     */
    public function get_minDaysToSale()
    {
        return $this->_minDaysToSale;
    }

    /**
     * Set minDaysToSale
     *
     * @param  mixed $minDaysToSale
     * @return Prosperent_Api
     */
    public function set_minDaysToSale($minDaysToSale)
    {
        $this->_minDaysToSale = number_format($minDaysToSale, 2, '.', '');
        return $this;
    }

    /**
     * Get maxDaysToSale
     *
     * @return null|string
     */
    public function get_maxDaysToSale()
    {
        return $this->_maxDaysToSale;
    }

    /**
     * Set maxDaysToSale
     *
     * @param  mixed $maxDaysToSale
     * @return Prosperent_Api
     */
    public function set_maxDaysToSale($maxDaysToSale)
    {
        $this->_maxDaysToSale = number_format($maxDaysToSale, 2, '.', '');
        return $this;
    }

    /**
     * Get visitor ip
     *
     * @return null|string
     */
    public function get_visitor_ip()
    {
        return $this->_visitor_ip;
    }

    /**
     * Set visitor ip
     *
     * @param  string $visitor_ip
     * @return Prosperent_Api
     */
    public function set_visitor_ip($visitor_ip)
    {
        $this->_visitor_ip = (string) $visitor_ip;
        return $this;
    }

    /**
     * Get user agent
     *
     * @return null|string
     */
    public function get_userAgent()
    {
        return $this->_userAgent;
    }

    /**
     * Set user agent
     *
     * @param  string $userAgent
     * @return Prosperent_Api
     */
    public function set_userAgent($userAgent)
    {
        $this->_userAgent = (string) $userAgent;
        return $this;
    }

    /**
     * Get referrer
     *
     * @return null|string
     */
    public function get_referrer()
    {
        return $this->_referrer;
    }

    /**
     * Set referrer
     *
     * @param  string $referrer
     * @return Prosperent_Api
     */
    public function set_referrer($referrer)
    {
        if (!preg_match('/^http:\/\//i', $referrer))
        {
            return $this;
        }

        $this->_referrer = (string) $referrer;
        return $this;
    }

    /**
     * Get location
     *
     * @return null|string
     */
    public function get_location()
    {
        return $this->_location;
    }

    /**
     * Set location
     *
     * @param  string $location
     * @return Prosperent_Api
     */
    public function set_location($location)
    {
        if (!preg_match('/^http:\/\//i', $location))
        {
            return $this;
        }

        $this->_location = (string) $location;
        return $this;
    }

    /**
     * Get serpQuery
     *
     * @return null|string
     */
    public function get_serpQuery()
    {
        return $this->_serpQuery;
    }

    /**
     * Set serpQuery
     *
     * @param  string $serpQuery
     * @return Prosperent_Api
     */
    public function set_serpQuery($serpQuery)
    {
        $this->_serpQuery = (string) $serpQuery;
        return $this;
    }

    /**
     * Get channel id
     *
     * @deprecated
     * @return null|int
     */
    public function get_channel_id()
    {
        return null;
    }

    /**
     * Set channel id
     *
     * @deprecated
     * @param  int $channel_id
     * @return Prosperent_Api
     */
    public function set_channel_id($channel_id)
    {
        return $this;
    }

    /**
     * Get sid
     *
     * @return null|string
     */
    public function get_sid()
    {
        return $this->_sid;
    }

    /**
     * Set sid
     *
     * @param  string $sid
     * @return Prosperent_Api
     */
    public function set_sid($sid)
    {
        $this->_sid = (string) $sid;
        return $this;
    }

    /**
     * Get url
     *
     * @return null|string
     */
    public function get_url()
    {
    	return $this->_url;
    }

    /**
     * Set url
     *
     * @param  string $url
     * @return Prosperent_Api
     */
    public function set_url($url)
    {
    	$this->_url = (string) $url;
    	return $this;
    }

    /**
     * Get page
     *
     * @return null|int
     */
    public function get_page()
    {
        return $this->_page;
    }

    /**
     * Set page
     *
     * @param  int $page
     * @return Prosperent_Api
     */
    public function set_page($page)
    {
        $this->_page = (int) $page;
        return $this;
    }

    /**
     * Get groupBy
     *
     * @return null|int
     */
    public function get_groupBy()
    {
        return $this->_groupBy;
    }

    /**
     * Set groupBy
     *
     * @param  string $groupBy
     * @return Prosperent_Api
     */
    public function set_groupBy($groupBy)
    {
        $this->_groupBy = (string) $groupBy;
        return $this;
    }

    /**
     * Get clickMaskDomain
     *
     * @return null|int
     */
    public function get_clickMaskDomain()
    {
        return $this->_clickMaskDomain;
    }
    
    /**
     * Set clickMaskDomain
     *
     * @param  string $clickMaskDomain
     * @return Prosperent_Api
     */
    public function set_clickMaskDomain($clickMaskDomain)
    {
        $this->_clickMaskDomain = (string) $clickMaskDomain;
        return $this;
    }    
    
    /**
     * Get imageMaskDomain
     *
     * @return null|int
     */
    public function get_imageMaskDomain()
    {
        return $this->_imageMaskDomain;
    }
    
    /**
     * Set imageMaskDomain
     *
     * @param  string $imageMaskDomain
     * @return Prosperent_Api
     */
    public function set_imageMaskDomain($imageMaskDomain)
    {
        $this->_imageMaskDomain = (string) $imageMaskDomain;
        return $this;
    }    
    
    /**
     * Get imageType
     *
     * @return null|int
     */
    public function get_imageType()
    {
        return $this->_imageType;
    }
    
    /**
     * Set imageType
     *
     * @param  string $imageType
     * @return Prosperent_Api
     */
    public function set_imageType($imageType)
    {
        $this->_imageType = (string) $imageType;
        return $this;
    }    
    
    /**
     * Get sortBy
     *
     * @return string
     */
    public function get_sortBy()
    {
        return $this->_sortBy;
    }

    /**
     * Set sortBy
     *
     * @param  string $sortBy
     * @return Prosperent_Api
     */
    public function set_sortBy($sortBy)
    {
        $this->_sortBy = (array) $sortBy;
        return $this;
    }

    /**
     * Get limit
     *
     * @return string
     */
    public function get_limit()
    {
        return $this->_limit;
    }

    /**
     * Set limit
     *
     * @param  int $limit
     * @return Prosperent_Api
     */
    public function set_limit($limit)
    {
        $this->_limit = (int) $limit;
        return $this;
    }

    /**
     * Get imageSize
     *
     * @return null|int
     */
    public function get_imageSize()
    {
        return $this->_imageSize;
    }

    /**
     * Set imageSize
     *
     * @param  int $imageSize
     * @return Prosperent_Api
     */
    public function set_imageSize($imageSize)
    {
        $this->_imageSize = (string) $imageSize;
        return $this;
    }

    /**
     * Get relevancyThreshold
     *
     * @return null|float
     */
    public function get_relevancyThreshold()
    {
        return $this->_relevancyThreshold;
    }

    /**
     * Set relevancyThreshold
     *
     * @param  float $relevancyThreshold
     * @return Prosperent_Api
     */
    public function set_relevancyThreshold($relevancyThreshold)
    {
        $this->_relevancyThreshold = $relevancyThreshold;
        return $this;
    }

    /**
     * Get debugMode
     *
     * @return null|bool
     */
    public function get_debugMode()
    {
        return $this->_debugMode;
    }

    /**
     * Set debugMode
     *
     * @param  bool $debugMode
     * @return Prosperent_Api
     */
    public function set_debugMode($debugMode)
    {
        $this->_debugMode = ($this->_isFalse($debugMode) ? false : true);
        return $this;
    }

    /**
     * Get enableCoupons
     *
     * @return null|bool
     */
    public function get_enableCoupons()
    {
        return $this->_enableCoupons;
    }

    /**
     * Set enableCoupons
     *
     * @param  bool $enableCoupons
     * @return Prosperent_Api
     */
    public function set_enableCoupons($enableCoupons)
    {
        $this->_enableCoupons = ($this->_isFalse($enableCoupons) ? false : true);
        return $this;
    }

    /**
     * Get enableFacets
     *
     * @return null|bool|string
     */
    public function get_enableFacets()
    {
        return $this->_enableFacets;
    }

    /**
     * Set enableFacets
     *
     * @param  bool|string $enableFacets
     * @return Prosperent_Api
     */
    public function set_enableFacets($enableFacets)
    {
        if (is_string($enableFacets) && !in_array($enableFacets, array('true', 'false', '0', '1', '')))
        {
            $enableFacets = (array) $enableFacets;
        }

        $this->_enableFacets = is_array($enableFacets) && count($enableFacets) ? $enableFacets : ($this->_isFalse($enableFacets) ? false : true);
        return $this;
    }

    /**
     * Get enableQuerySuggestion
     *
     * @return null|bool
     */
    public function get_enableQuerySuggestion()
    {
        return $this->_enableQuerySuggestion;
    }

    /**
     * Set enableQuerySuggestion
     *
     * @param  bool $enableQuerySuggestion
     * @return Prosperent_Api
     */
    public function set_enableQuerySuggestion($enableQuerySuggestion)
    {
        $this->_enableQuerySuggestion = ($this->_isFalse($enableQuerySuggestion) ? false : true);
        return $this;
    }

    /**
     * Get enableJsonCompression
     *
     * @return null|bool
     */
    public function get_enableJsonCompression()
    {
        return $this->_enableJsonCompression;
    }

    /**
     * Set enableJsonCompression
     *
     * @param  bool $enableJsonCompression
     * @return Prosperent_Api
     */
    public function set_enableJsonCompression($enableJsonCompression)
    {
        $this->_enableJsonCompression = ($this->_isFalse($enableJsonCompression) ? false : true);
        return $this;
    }

    /**
     * Get enableFullData
     *
     * @return null|bool
     */
    public function get_enableFullData()
    {
        return $this->_enableFullData;
    }

    /**
     * Set enableFullData
     *
     * @param  bool $enableFullData
     * @return Prosperent_Api
     */
    public function set_enableFullData($enableFullData)
    {
        $this->_enableFullData = ($this->_isFalse($enableFullData) ? false : true);
        return $this;
    }

    /**
     * Returns whether string is false or not
     *
     * @param  mixed $value
     * @return bool
     */
    protected function _isFalse($value)
    {
        return (false == $value || 'false' == strtolower(trim($value)));
    }

    /**
     * Determines and returns the search query from the
     * SERP referrer
     *
     * @param  string $referrer
     * @return string
     */
    public static function getQueryFromReferrer($referrer)
    {
        $query = false;

        //clean the referrer
        $referer = trim(rawurldecode($referrer));
        $referer = preg_replace('/\\s/', '%20', $referer);

        //use the list of serp referrers
        $sr = array(
            array("q", "google"),
            array("q", "bing"),
            array("q", "search.msn"),
            array("q", "search.live"),
            array("q", "blogsearch.google"),
            array("q", "search.comcast"),
            array("q", "cuil"),
            array("query", "aolsearch.aol"),
            array("query", "aim.search.aol"),
            array("query", "search.aol"),
            array("query", "aolsearcht11.search.aol"),
            array("query", "search.hp.my.aol"),
            array("encquery", "search.aol"),
            array("query", "search.naver"),
            array("where", "search.naver"),
            array("p", "sq.search.yahoo"),
            array("p", "espanol.search.yahoo"),
            array("p", "ca.search.yahoo"),
            array("qid", "search.myway"),
            array("searchfor", "search.mywebsearch"),
            array("query", "search.netscape"),
            array("q", "toolbar.inbox"),
            array("q", "charter"),
            array("qs", "search.rr"),
            array("q", "int.ask"),
            array("q", "ask"),
            array("q", "charter"),
            array("qs", "search.rr"),
            array("p", "search.bt"),
            array("q", "aolsearcht5.search.aol"),
            array("query", "aim.search.aol"),
            array("query", "search.hp.my.aol"),
            array("p", "us.yhs.search.yahoo"),
            array("p", "search.bt"),
            array("q", "uk.ask"),
            array("q", "verizon"),
            array("q", "search.icq"),
            array("q", "search.conduit"),
            array("q", "search.incredimail"),
            array("q", "search.earthlink"),
            array("q", "suche.t-online"),
            array("q", "myembarq"),
            array("q", "search.sweetim"),
            array("query", "lo"),
            array("query", "search.cnn"),
            array("query", "aolsearcht3.search.aol"),
            array("query", "aolsearcht12.search.aol"),
            array("query", "aolsearcht10.search.aol"),
            array("query", "aolsearcht11.search.aol"),
            array("query", "aolsearcht2.search.aol"),
            array("query", "aolsearcht4.search.aol"),
            array("query", "aolsearcht5.search.aol"),
            array("query", "aolsearcht6.search.aol"),
            array("query", "aolsearcht7.search.aol"),
            array("query", "aolsearcht9.search.aol"),
            array("query", "tiscali.co"),
            array("q", "verden.abcsok"),
            array("query", "search.aol.co"),
            array("query", "univision"),
            array("q", "fastbrowsersearch"),
            array("q", "search.babylon"),
            array("q", "search.virginmedia"),
            array("as_q", "google"),
            array("q", "home.knology"),
            array("q", "search.pch"),
            array("term", "search1.sky"),
            array("q", "embarqmail"),
            array("q", "armstrongmywire"),
            array("find", "sensis.com"),
            array("q", "portal.tds"),
            array("q", "search.orange.co"),
            array("q", "search.alot"),
            array("q", "home.suddenlink"),
            array("qry", "searchservice.myspace"),
            array("q", "optimum"),
            array("q", "mypoints"),
            array("p", "search.yahoo")
        );

        if (strlen($referer) && preg_match('/^http:\/\//i', $referer) && $referrer = parse_url($referer))
        {
            parse_str(preg_replace('/^\\?/', '', $referrer['query']), $referrerQueries);

            foreach ($sr as $s)
            {
                if (preg_match('/'.str_replace('.', '\\.', $s[1]).'\\.[a-z\\.]{2,6}$/i', $referrer['host']) && array_key_exists($s[0], $referrerQueries))
                {
                    $query = trim(rawurldecode(urldecode($referrerQueries[$s[0]])));
                    return $query;
                }
            }
        }

        return $query;
    }
}

/**
 * The Prosperent API Cache class was created to provide
 * built in caching and decrease the number of requests
 * being made to the Prosperent API
 *
 * @copyright  Copyright (c) 2009-2011 Prosperent, Inc. (http://prosperent.com)
 * @license    See above (New BSD License)
 * @example    http://prosperent.com/affiliate/api View documentation on Prosperent.com
 * @package    Prosperent_Api
 * @subpackage Cache
 */
abstract class Prosperent_Api_Cache
{
    /**
     * Consts for clean() method
     */
    const CLEANING_MODE_ALL = 'all';
    const CLEANING_MODE_OLD = 'old';

    /**
     * Factory
     *
     * @param  string $backend
     * @param  array  $options
     * @return Prosperent_Api_Cache_Core
     */
    public static function factory($backend, $options = array())
    {
        /*
         * verify the options are an array
         */
        if (!is_array($options))
        {
            self::throwException('Backend parameters must be an array');
        }

        /*
         * Verify that an backend name has been specified.
         */
        if (!is_string($backend) || empty($backend))
        {
            self::throwException('Backend name must be specified in a string');
        }

        /*
         * Form full backend class name
         */
        $backendNamespace = 'Prosperent_Api_Cache_Backend';
        if (isset($options['backendNamespace']) && '' != $options['backendNamespace'])
        {
            $backendNamespace = $options['backendNamespace'];
            unset($options['backendNamespace']);
        }

        $backendName = $backendNamespace . '_';
        $backendName .= str_replace(' ', '_', ucwords(str_replace('_', ' ', strtolower($backend))));

        /*
         * Confirm the backend class exists
         */
        if (!class_exists($backendName))
        {
            self::throwException('Class ' . $backendName . ' does not exist');
        }

        /*
         * Create an instance of the backend class.
         * Pass the config to the backend class constructor.
         */
        $backendObj = new $backendName($options);

        /*
         * start core object
         */
        $core = new Prosperent_Api_Cache_Core($options);

        /*
         * pass the backend to the core
         */
        $core->setBackend($backendObj);

        /*
         * return core
         */
        return $core;
    }

    /**
     * Throw an exception
     *
     * If you prefer to use a different exception handler,
     * simply modify the code here to use said handler. All
     * errors thrown from the Prosperent_Api_Cache family
     * utilize this static method.
     *
     * @param string $msg Message for the exception
     */
    public static function throwException($msg)
    {
        throw new Exception($msg);
    }
}

/**
 * Core Cache Class
 *
 * @copyright  Copyright (c) 2009-2011 Prosperent, Inc. (http://prosperent.com)
 * @license    See above (New BSD License)
 * @example    http://prosperent.com/affiliate/api View documentation on Prosperent.com
 * @package    Prosperent_Api
 * @subpackage Cache
 */
class Prosperent_Api_Cache_Core
{
    /**
     * Backend Object
     *
     * @var Prosperent_Api_Cache_Backend $_backend
     */
    protected $_backend = null;

    /**
     * Available options
     *
     * ====> (boolean) caching :
     * - Enable / disable caching
     * (can be very useful for the debug of cached scripts)
     *
     * =====> (string) cache_id_prefix :
     * - prefix for cache ids (namespace)
     *
     * ====> (boolean) automatic_serialization :
     * - Enable / disable automatic serialization
     * - It can be used to save directly datas which aren't strings (but it's slower)
     *
     * ====> (int) automatic_cleaning_factor :
     * - Disable / Tune the automatic cleaning process
     * - The automatic cleaning process destroy too old (for the given life time)
     *   cache files when a new cache file is written :
     *     0               => no automatic cache cleaning
     *     1               => systematic cache cleaning
     *     x (integer) > 1 => automatic cleaning randomly 1 times on x cache write
     *
     * ====> (int) lifetime :
     * - Cache lifetime (in seconds)
     * - If null, the cache is valid forever.
     *
     * ====> (boolean) logging :
     * - If set to true, logging is activated (but the system is slower)
     *
     * ====> (boolean) ignore_user_abort
     * - If set to true, the core will set the ignore_user_abort PHP flag inside the
     *   save() method to avoid cache corruptions in some cases (default false)
     *
     * @var array $_options available options
     */
    protected $_options = array(
        'caching'                   => true,
        'cache_id_prefix'           => null,
        'automatic_serialization'   => false,
        'automatic_cleaning_factor' => 10,
        'lifetime'                  => 86400,
        'logging'                   => false,
        'logger'                    => null,
        'ignore_user_abort'         => false
    );

    /**
     * Array of options which have to be transfered to backend
     *
     * @var array $_directivesList
     */
    protected static $_directivesList = array('lifetime');

    /**
     * Not used for the core, just a sort a hint to get a common setOption() method (for the core and for frontends)
     *
     * @var array $_specificOptions
     */
    protected $_specificOptions = array();

    /**
     * Last used cache id
     *
     * @var string $_lastId
     */
    private $_lastId = null;

    /**
     * @var bool
     */
    protected $_extendedBackend = true;

    /**
     * Array of capabilities of the backend
     *
     * @var array
     */
    protected $_backendCapabilities = array();

    /**
     * Constructor
     *
     * @param  array $options
     * @return void
     */
    public function __construct($options = array())
    {
        if (!is_array($options)) {
            Prosperent_Api_Cache::throwException("Options passed were not an array");
        }
        while (list($name, $value) = each($options)) {
            $this->setOption($name, $value);
        }
    }

    /**
     * Set the backend
     *
     * @param  Prosperent_Api_Cache_Backend $backendObject
     * @return void
     */
    public function setBackend(Prosperent_Api_Cache_Backend $backendObject)
    {
        $this->_backend = $backendObject;
        // some options (listed in $_directivesList) have to be given
        // to the backend too (even if they are not "backend specific")
        $directives = array();
        foreach (Prosperent_Api_Cache_Core::$_directivesList as $directive) {
            $directives[$directive] = $this->_options[$directive];
        }
        $this->_backend->setDirectives($directives);
        $this->_backendCapabilities = $this->_backend->getCapabilities();
    }

    /**
     * Returns the backend
     *
     * @return Prosperent_Api_Cache_Backend backend object
     */
    public function getBackend()
    {
        return $this->_backend;
    }

    /**
     * Public frontend to set an option
     *
     * There is an additional validation (relative to the protected _setOption method)
     *
     * @param  string $name  Name of the option
     * @param  mixed  $value Value of the option
     * @return void
     */
    public function setOption($name, $value)
    {
        if (!is_string($name)) {
            Prosperent_Api_Cache::throwException("Incorrect option name : $name");
        }
        $name = strtolower($name);
        if (array_key_exists($name, $this->_options)) {
            // This is a Core option
            $this->_setOption($name, $value);
            return;
        }
        if (array_key_exists($name, $this->_specificOptions)) {
            // This a specic option of this frontend
            $this->_specificOptions[$name] = $value;
            return;
        }
    }

    /**
     * Public frontend to get an option value
     *
     * @param  string $name  Name of the option
     * @return mixed option value
     */
    public function getOption($name)
    {
        if (is_string($name)) {
            $name = strtolower($name);
            if (array_key_exists($name, $this->_options)) {
                // This is a Core option
                return $this->_options[$name];
            }
            if (array_key_exists($name, $this->_specificOptions)) {
                // This a specic option of this frontend
                return $this->_specificOptions[$name];
            }
        }
        Prosperent_Api_Cache::throwException("Incorrect option name : $name");
    }

    /**
     * Set an option
     *
     * @param  string $name  Name of the option
     * @param  mixed  $value Value of the option
     * @return void
     */
    private function _setOption($name, $value)
    {
        if (!is_string($name) || !array_key_exists($name, $this->_options)) {
            Prosperent_Api_Cache::throwException("Incorrect option name : $name");
        }
        if ($name == 'lifetime' && empty($value)) {
            $value = null;
        }
        $this->_options[$name] = $value;
    }

    /**
     * Force a new lifetime
     *
     * The new value is set for the core/frontend but for the backend too (directive)
     *
     * @param  int $newLifetime New lifetime (in seconds)
     * @return void
     */
    public function setLifetime($newLifetime)
    {
        $this->_options['lifetime'] = $newLifetime;
        $this->_backend->setDirectives(array(
            'lifetime' => $newLifetime
        ));
    }

    /**
     * Test if a cache is available for the given id and (if yes) return it (false else)
     *
     * @param  string  $id                     Cache id
     * @param  boolean $doNotTestCacheValidity If set to true, the cache validity won't be tested
     * @param  boolean $doNotUnserialize       Do not serialize (even if automatic_serialization is true) => for internal use
     * @return mixed|false Cached datas
     */
    public function load($id, $doNotTestCacheValidity = false, $doNotUnserialize = false)
    {
        if (!$this->_options['caching']) {
            return false;
        }
        $id = $this->_id($id); // cache id may need prefix
        $this->_lastId = $id;
        self::_validateIdOrTag($id);

        $data = $this->_backend->load($id, $doNotTestCacheValidity);
        if ($data===false) {
            // no cache available
            return false;
        }
        if ((!$doNotUnserialize) && $this->_options['automatic_serialization']) {
            // we need to unserialize before sending the result
            return unserialize($data);
        }
        return $data;
    }

    /**
     * Test if a cache is available for the given id
     *
     * @param  string $id Cache id
     * @return int|false Last modified time of cache entry if it is available, false otherwise
     */
    public function test($id)
    {
        if (!$this->_options['caching']) {
            return false;
        }
        $id = $this->_id($id); // cache id may need prefix
        self::_validateIdOrTag($id);
        $this->_lastId = $id;

        return $this->_backend->test($id);
    }

    /**
     * Save some data in a cache
     *
     * @param  mixed  $data             Data to put in cache (can be another type than string if automatic_serialization is on)
     * @param  string $id               Cache id (if not set, the last cache id will be used)
     * @param  int    $specificLifetime If != false, set a specific lifetime for this cache record (null => infinite lifetime)
     * @param  int    $priority         integer between 0 (very low priority) and 10 (maximum priority) used by some particular backends
     * @return boolean True if no problem
     */
    public function save($data, $id = null, $specificLifetime = false, $priority = 8)
    {
        if (!$this->_options['caching']) {
            return true;
        }
        if ($id === null) {
            $id = $this->_lastId;
        } else {
            $id = $this->_id($id);
        }
        self::_validateIdOrTag($id);
        if ($this->_options['automatic_serialization']) {
            // we need to serialize datas before storing them
            $data = serialize($data);
        } else {
            if (!is_string($data)) {
                Prosperent_Api_Cache::throwException("Datas must be string or set automatic_serialization = true");
            }
        }

        // automatic cleaning
        if ($this->_options['automatic_cleaning_factor'] > 0) {
            $rand = rand(1, $this->_options['automatic_cleaning_factor']);
            if ($rand==1) {
                if ($this->_extendedBackend) {
                    $this->clean(Prosperent_Api_Cache::CLEANING_MODE_OLD);
                }
            }
        }

        if ($this->_options['ignore_user_abort']) {
            $abort = ignore_user_abort(true);
        }
        if (($this->_extendedBackend) && ($this->_backendCapabilities['priority'])) {
            $result = $this->_backend->save($data, $id, $specificLifetime, $priority);
        } else {
            $result = $this->_backend->save($data, $id, $specificLifetime);
        }
        if ($this->_options['ignore_user_abort']) {
            ignore_user_abort($abort);
        }

        if (!$result) {
            $this->_backend->remove($id);
            return false;
        }

        return true;
    }

    /**
     * Remove a cache
     *
     * @param  string $id Cache id to remove
     * @return boolean True if ok
     */
    public function remove($id)
    {
        if (!$this->_options['caching']) {
            return true;
        }
        $id = $this->_id($id); // cache id may need prefix
        self::_validateIdOrTag($id);

        return $this->_backend->remove($id);
    }

    /**
     * Clean cache entries
     *
     * @param  string       $mode
     * @return boolean True if ok
     */
    public function clean($mode = Prosperent_Api_Cache::CLEANING_MODE_ALL)
    {
        if (!$this->_options['caching']) {
            return true;
        }
        if (!in_array($mode, array(Prosperent_Api_Cache::CLEANING_MODE_ALL,
                                   Prosperent_Api_Cache::CLEANING_MODE_OLD))) {
            Prosperent_Api_Cache::throwException('Invalid cleaning mode');
        }

        return $this->_backend->clean($mode);
    }

    /**
     * Return an array of stored cache ids
     *
     * @return array array of stored cache ids (string)
     */
    public function getIds()
    {
        $ids = $this->_backend->getIds();

        if (isset($this->_options['cache_id_prefix']) && $this->_options['cache_id_prefix'] !== '') {
            $prefix    = & $this->_options['cache_id_prefix'];
            $prefixLen = strlen($prefix);
            foreach ($ids as &$id) {
                if (strpos($id, $prefix) === 0) {
                    $id = substr($id, $prefixLen);
                }
            }
        }

        return $ids;
    }

    /**
     * Return the filling percentage of the backend storage
     *
     * @return int integer between 0 and 100
     */
    public function getFillingPercentage()
    {
        return $this->_backend->getFillingPercentage();
    }

    /**
     * Return an array of metadatas for the given cache id
     *
     * The array will include these keys :
     * - expire : the expire timestamp
     * - mtime : timestamp of last modification time
     *
     * @param string $id cache id
     * @return array array of metadatas (false if the cache id is not found)
     */
    public function getMetadatas($id)
    {
        $id = $this->_id($id); // cache id may need prefix
        return $this->_backend->getMetadatas($id);
    }

    /**
     * Give (if possible) an extra lifetime to the given cache id
     *
     * @param string $id cache id
     * @param int $extraLifetime
     * @return boolean true if ok
     */
    public function touch($id, $extraLifetime)
    {
        $id = $this->_id($id); // cache id may need prefix

        return $this->_backend->touch($id, $extraLifetime);
    }

    /**
     * Validate a cache id or a tag (security, reliable filenames, reserved prefixes...)
     *
     * Throw an exception if a problem is found
     *
     * @param  string $string Cache id or tag
     * @return void
     */
    protected static function _validateIdOrTag($string)
    {
        if (!is_string($string)) {
            Prosperent_Api_Cache::throwException('Invalid id or tag : must be a string');
        }
        if (stristr($string, Prosperent_Api_Cache_Backend_File::METADATA_PREFIX)) {
            Prosperent_Api_Cache::throwException('"' . Prosperent_Api_Cache_Backend_File::METADATA_PREFIX . '*" ids are reserved');
        }
        if (!preg_match('~^[a-zA-Z0-9_]+$~D', $string)) {
            Prosperent_Api_Cache::throwException("Invalid id or tag '$string' : must use only [a-zA-Z0-9_]");
        }
    }

    /**
     * Make and return a cache id
     *
     * Checks 'cache_id_prefix' and returns new id with prefix or simply the id if null
     *
     * @param  string $id Cache id
     * @return string Cache id (with or without prefix)
     */
    protected function _id($id)
    {
        if (($id !== null) && isset($this->_options['cache_id_prefix'])) {
            return $this->_options['cache_id_prefix'] . $id; // return with prefix
        }
        return $id; // no prefix, just return the $id passed
    }
}

/**
 * Abstract backend class
 *
 * @copyright  Copyright (c) 2009-2011 Prosperent, Inc. (http://prosperent.com)
 * @license    See above (New BSD License)
 * @example    http://prosperent.com/affiliate/api View documentation on Prosperent.com
 * @package    Prosperent_Api
 * @subpackage Cache
 */
class Prosperent_Api_Cache_Backend
{
    /**
     * Core directives
     *
     * =====> (int) lifetime :
     * - Cache lifetime (in seconds)
     * - If null, the cache is valid forever
     *
     * @var array directives
     */
    protected $_directives = array(
        'lifetime' => 86400
    );

    /**
     * Available options
     *
     * @var array available options
     */
    protected $_options = array();

    /**
     * Constructor
     *
     * @param  array $options Associative array of options
     * @return void
     */
    public function __construct(array $options = array())
    {
        while (list($name, $value) = each($options)) {
            $this->setOption($name, $value);
        }
    }

    /**
     * Set the frontend directives
     *
     * @param  array $directives Assoc of directives
     * @return void
     */
    public function setDirectives($directives)
    {
        if (!is_array($directives)) Prosperent_Api_Cache::throwException('Directives parameter must be an array');
        while (list($name, $value) = each($directives)) {
            if (!is_string($name)) {
                Prosperent_Api_Cache::throwException("Incorrect option name : $name");
            }
            $name = strtolower($name);
            if (array_key_exists($name, $this->_directives)) {
                $this->_directives[$name] = $value;
            }

        }
    }

    /**
     * Set an option
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    public function setOption($name, $value)
    {
        if (!is_string($name)) {
            Prosperent_Api_Cache::throwException("Incorrect option name : $name");
        }
        $name = strtolower($name);
        if (array_key_exists($name, $this->_options)) {
            $this->_options[$name] = $value;
        }
    }

    /**
     * Get the life time
     *
     * if $specificLifetime is not false, the given specific life time is used
     * else, the global lifetime is used
     *
     * @param  int $specificLifetime
     * @return int Cache life time
     */
    public function getLifetime($specificLifetime)
    {
        if ($specificLifetime === false) {
            return $this->_directives['lifetime'];
        }
        return $specificLifetime;
    }

    /**
     * Determine system TMP directory and detect if we have read access
     *
     * @return string
     */
    public function getTmpDir()
    {
        $tmpdir = array();
        foreach (array($_ENV, $_SERVER) as $tab) {
            foreach (array('TMPDIR', 'TEMP', 'TMP', 'windir', 'SystemRoot') as $key) {
                if (isset($tab[$key])) {
                    if (($key == 'windir') or ($key == 'SystemRoot')) {
                        $dir = realpath($tab[$key] . '\\temp');
                    } else {
                        $dir = realpath($tab[$key]);
                    }
                    if ($this->_isGoodTmpDir($dir)) {
                        return $dir;
                    }
                }
            }
        }
        $upload = ini_get('upload_tmp_dir');
        if ($upload) {
            $dir = realpath($upload);
            if ($this->_isGoodTmpDir($dir)) {
                return $dir;
            }
        }
        if (function_exists('sys_get_temp_dir')) {
            $dir = sys_get_temp_dir();
            if ($this->_isGoodTmpDir($dir)) {
                return $dir;
            }
        }
        // Attemp to detect by creating a temporary file
        $tempFile = tempnam(md5(uniqid(rand(), TRUE)), '');
        if ($tempFile) {
            $dir = realpath(dirname($tempFile));
            unlink($tempFile);
            if ($this->_isGoodTmpDir($dir)) {
                return $dir;
            }
        }
        if ($this->_isGoodTmpDir('/tmp')) {
            return '/tmp';
        }
        if ($this->_isGoodTmpDir('\\temp')) {
            return '\\temp';
        }
        Prosperent_Api_Cache::throwException('Could not determine temp directory, please specify a cache_dir manually');
    }

    /**
     * Verify if the given temporary directory is readable and writable
     *
     * @param string $dir temporary directory
     * @return boolean true if the directory is ok
     */
    protected function _isGoodTmpDir($dir)
    {
        if (is_readable($dir)) {
            if (is_writable($dir)) {
                return true;
            }
        }
        return false;
    }
}

/**
 * Inteface for backend classes
 *
 * @copyright  Copyright (c) 2009-2011 Prosperent, Inc. (http://prosperent.com)
 * @license    See above (New BSD License)
 * @example    http://prosperent.com/affiliate/api View documentation on Prosperent.com
 * @package    Prosperent_Api
 * @subpackage Cache_Backend
 */
interface Prosperent_Api_Cache_Backend_Interface
{
    public function setDirectives($directives);
    public function load($id, $doNotTestCacheValidity = false);
    public function test($id);
    public function save($data, $id, $specificLifetime = false);
    public function remove($id);
    public function clean($mode = Prosperent_Api_Cache::CLEANING_MODE_ALL);
    public function getIds();
    public function getFillingPercentage();
    public function getMetadatas($id);
    public function touch($id, $extraLifetime);
    public function getCapabilities();
}

/**
 * File caching subclass for Prosperent_Api_Cache
 *
 * @copyright  Copyright (c) 2009-2011 Prosperent, Inc. (http://prosperent.com)
 * @license    See above (New BSD License)
 * @example    http://prosperent.com/affiliate/api View documentation on Prosperent.com
 * @package    Prosperent_Api
 * @subpackage Cache_Backend
 */
class Prosperent_Api_Cache_Backend_File extends Prosperent_Api_Cache_Backend implements Prosperent_Api_Cache_Backend_Interface
{
    /**
     * Available options
     *
     * =====> (string) cache_dir :
     * - Directory where to put the cache files
     *
     * =====> (boolean) read_control :
     * - Enable / disable read control
     * - If enabled, a control key is embeded in cache file and this key is compared with the one
     * calculated after the reading.
     *
     * =====> (string) read_control_type :
     * - Type of read control (only if read control is enabled). Available values are :
     *   'md5' for a md5 hash control (best but slowest)
     *   'crc32' for a crc32 hash control (lightly less safe but faster, better choice)
     *   'adler32' for an adler32 hash control (excellent choice too, faster than crc32)
     *   'strlen' for a length only test (fastest)
     *
     * =====> (int) hashed_directory_level :
     * - Hashed directory level
     * - Set the hashed directory structure level. 0 means "no hashed directory
     * structure", 1 means "one level of directory", 2 means "two levels"...
     * This option can speed up the cache only when you have many thousands of
     * cache file. Only specific benchs can help you to choose the perfect value
     * for you. Maybe, 1 or 2 is a good start.
     *
     * =====> (int) hashed_directory_umask :
     * - Umask for hashed directory structure
     *
     * =====> (string) file_name_prefix :
     * - prefix for cache files
     * - be really carefull with this option because a too generic value in a system cache dir
     *   (like /tmp) can cause disasters when cleaning the cache
     *
     * =====> (int) cache_file_umask :
     * - Umask for cache files
     *
     * =====> (int) metatadatas_array_max_size :
     * - max size for the metadatas array (don't change this value unless you
     *   know what you are doing)
     *
     * @var array available options
     */
    protected $_options = array(
        'cache_dir'                => null,
        'read_control'             => true,
        'read_control_type'        => 'crc32',
        'hashed_directory_level'   => 1,
        'hashed_directory_umask'   => 0700,
        'file_name_prefix'         => 'prosperent_api_cache',
        'cache_file_umask'         => 0600,
        'metadatas_array_max_size' => 100
    );

    /**
     * Array of metadatas (each item is an associative array)
     *
     * @var array
     */
    protected $_metadatasArray = array();

    /**
     * Constants
     */
    const METADATA_PREFIX = 'cache-meta---';

    /**
     * Constructor
     *
     * @param  array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        if ($this->_options['cache_dir'] !== null) {
            $this->setCacheDir($this->_options['cache_dir']);
        } else {
            $this->setCacheDir(self::getTmpDir() . DIRECTORY_SEPARATOR, false);
        }
        if (isset($this->_options['file_name_prefix'])) {
            if (!preg_match('~^[a-zA-Z0-9_]+$~D', $this->_options['file_name_prefix'])) {
                Prosperent_Api_Cache::throwException('Invalid file_name_prefix : must use only [a-zA-Z0-9_]');
            }
        }
        if ($this->_options['metadatas_array_max_size'] < 10) {
            Prosperent_Api_Cache::throwException('Invalid metadatas_array_max_size, must be > 10');
        }
        if (isset($options['hashed_directory_umask']) && is_string($options['hashed_directory_umask'])) {
            $this->_options['hashed_directory_umask'] = octdec($this->_options['hashed_directory_umask']);
        }
        if (isset($options['cache_file_umask']) && is_string($options['cache_file_umask'])) {
            $this->_options['cache_file_umask'] = octdec($this->_options['cache_file_umask']);
        }
    }

    /**
     * Set the cache_dir (particular case of setOption() method)
     *
     * @param  string  $value
     * @param  boolean $trailingSeparator If true, add a trailing separator is necessary
     * @return void
     */
    public function setCacheDir($value, $trailingSeparator = true)
    {
        if (!is_dir($value)) {
            Prosperent_Api_Cache::throwException('cache_dir must be a directory');
        }
        if (!is_writable($value)) {
            Prosperent_Api_Cache::throwException('cache_dir is not writable');
        }
        if ($trailingSeparator) {
            // add a trailing DIRECTORY_SEPARATOR if necessary
            $value = rtrim(realpath($value), '\\/') . DIRECTORY_SEPARATOR;
        }
        $this->_options['cache_dir'] = $value;
    }

    /**
     * Test if a cache is available for the given id and (if yes) return it (false else)
     *
     * @param string $id cache id
     * @param boolean $doNotTestCacheValidity if set to true, the cache validity won't be tested
     * @return string|false cached datas
     */
    public function load($id, $doNotTestCacheValidity = false)
    {
        if (!($this->_test($id, $doNotTestCacheValidity))) {
            // The cache is not hit !
            return false;
        }
        $metadatas = $this->_getMetadatas($id);
        $file = $this->_file($id);
        $data = $this->_fileGetContents($file);
        if ($this->_options['read_control']) {
            $hashData = $this->_hash($data, $this->_options['read_control_type']);
            $hashControl = $metadatas['hash'];
            if ($hashData != $hashControl) {
                $this->remove($id);
                return false;
            }
        }
        return $data;
    }

    /**
     * Test if a cache is available or not (for the given id)
     *
     * @param  string $id cache id
     * @return mixed false (a cache is not available) or "last modified" timestamp (int) of the available cache record
     */
    public function test($id)
    {
        clearstatcache();
        return $this->_test($id, false);
    }

    /**
     * Save some string datas into a cache record
     *
     * @param  string $data             Datas to cache
     * @param  string $id               Cache id
     * @param  int    $specificLifetime If != false, set a specific lifetime for this cache record (null => infinite lifetime)
     * @return boolean true if no problem
     */
    public function save($data, $id, $specificLifetime = false)
    {
        clearstatcache();
        $file = $this->_file($id);
        $path = $this->_path($id);
        if ($this->_options['hashed_directory_level'] > 0) {
            if (!is_writable($path)) {
                // maybe, we just have to build the directory structure
                $this->_recursiveMkdirAndChmod($id);
            }
            if (!is_writable($path)) {
                return false;
            }
        }
        if ($this->_options['read_control']) {
            $hash = $this->_hash($data, $this->_options['read_control_type']);
        } else {
            $hash = '';
        }
        $metadatas = array(
            'hash' => $hash,
            'mtime' => time(),
            'expire' => $this->_expireTime($this->getLifetime($specificLifetime))
        );
        $res = $this->_setMetadatas($id, $metadatas);
        if (!$res) {
            return false;
        }
        $res = $this->_filePutContents($file, $data);
        return $res;
    }

    /**
     * Remove a cache record
     *
     * @param  string $id cache id
     * @return boolean true if no problem
     */
    public function remove($id)
    {
        $file = $this->_file($id);
        $boolRemove   = $this->_remove($file);
        $boolMetadata = $this->_delMetadatas($id);
        return $boolMetadata && $boolRemove;
    }

    /**
     * Clean some cache records
     *
     * @param  string $mode clean mode
     * @return boolean true if no problem
     */
    public function clean($mode = Prosperent_Api_Cache::CLEANING_MODE_ALL)
    {
        // We use this protected method to hide the recursive stuff
        clearstatcache();
        return $this->_clean($this->_options['cache_dir'], $mode);
    }

    /**
     * Return an array of stored cache ids
     *
     * @return array array of stored cache ids (string)
     */
    public function getIds()
    {
        return $this->_get($this->_options['cache_dir'], 'ids', array());
    }

    /**
     * Return the filling percentage of the backend storage
     *
     * @return int integer between 0 and 100
     */
    public function getFillingPercentage()
    {
        $free = disk_free_space($this->_options['cache_dir']);
        $total = disk_total_space($this->_options['cache_dir']);
        if ($total == 0) {
            Prosperent_Api_Cache::throwException('Cannot get disk_total_space');
        } else {
            if ($free >= $total) {
                return 100;
            }
            return ((int) (100. * ($total - $free) / $total));
        }
    }

    /**
     * Return an array of metadatas for the given cache id
     *
     * The array must include these keys :
     * - expire : the expire timestamp
     * - mtime : timestamp of last modification time
     *
     * @param string $id cache id
     * @return array array of metadatas (false if the cache id is not found)
     */
    public function getMetadatas($id)
    {
        $metadatas = $this->_getMetadatas($id);
        if (!$metadatas) {
            return false;
        }
        if (time() > $metadatas['expire']) {
            return false;
        }
        return array(
            'expire' => $metadatas['expire'],
            'mtime' => $metadatas['mtime']
        );
    }

    /**
     * Give (if possible) an extra lifetime to the given cache id
     *
     * @param string $id cache id
     * @param int $extraLifetime
     * @return boolean true if ok
     */
    public function touch($id, $extraLifetime)
    {
        $metadatas = $this->_getMetadatas($id);
        if (!$metadatas) {
            return false;
        }
        if (time() > $metadatas['expire']) {
            return false;
        }
        $newMetadatas = array(
            'hash' => $metadatas['hash'],
            'mtime' => time(),
            'expire' => $metadatas['expire'] + $extraLifetime
        );
        $res = $this->_setMetadatas($id, $newMetadatas);
        if (!$res) {
            return false;
        }
        return true;
    }

    /**
     * Return an associative array of capabilities (booleans) of the backend
     *
     * @return array associative of with capabilities
     */
    public function getCapabilities()
    {
        return array(
            'automatic_cleaning' => true,
            'expired_read' => true,
            'priority' => false,
            'infinite_lifetime' => true,
            'get_list' => true
        );
    }

    /**
     * Get a metadatas record
     *
     * @param  string $id  Cache id
     * @return array|false Associative array of metadatas
     */
    protected function _getMetadatas($id)
    {
        if (isset($this->_metadatasArray[$id])) {
            return $this->_metadatasArray[$id];
        } else {
            $metadatas = $this->_loadMetadatas($id);
            if (!$metadatas) {
                return false;
            }
            $this->_setMetadatas($id, $metadatas, false);
            return $metadatas;
        }
    }

    /**
     * Set a metadatas record
     *
     * @param  string $id        Cache id
     * @param  array  $metadatas Associative array of metadatas
     * @param  boolean $save     optional pass false to disable saving to file
     * @return boolean True if no problem
     */
    protected function _setMetadatas($id, $metadatas, $save = true)
    {
        if (count($this->_metadatasArray) >= $this->_options['metadatas_array_max_size']) {
            $n = (int) ($this->_options['metadatas_array_max_size'] / 10);
            $this->_metadatasArray = array_slice($this->_metadatasArray, $n);
        }
        if ($save) {
            $result = $this->_saveMetadatas($id, $metadatas);
            if (!$result) {
                return false;
            }
        }
        $this->_metadatasArray[$id] = $metadatas;
        return true;
    }

    /**
     * Drop a metadata record
     *
     * @param  string $id Cache id
     * @return boolean True if no problem
     */
    protected function _delMetadatas($id)
    {
        if (isset($this->_metadatasArray[$id])) {
            unset($this->_metadatasArray[$id]);
        }
        $file = $this->_metadatasFile($id);
        return $this->_remove($file);
    }

    /**
     * Clear the metadatas array
     *
     * @return void
     */
    protected function _cleanMetadatas()
    {
        $this->_metadatasArray = array();
    }

    /**
     * Load metadatas from disk
     *
     * @param  string $id Cache id
     * @return array|false Metadatas associative array
     */
    protected function _loadMetadatas($id)
    {
        $file = $this->_metadatasFile($id);
        $result = $this->_fileGetContents($file);
        if (!$result) {
            return false;
        }
        $tmp = @unserialize($result);
        return $tmp;
    }

    /**
     * Save metadatas to disk
     *
     * @param  string $id        Cache id
     * @param  array  $metadatas Associative array
     * @return boolean True if no problem
     */
    protected function _saveMetadatas($id, $metadatas)
    {
        $file = $this->_metadatasFile($id);
        $result = $this->_filePutContents($file, serialize($metadatas));
        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * Make and return a file name (with path) for metadatas
     *
     * @param  string $id Cache id
     * @return string Metadatas file name (with path)
     */
    protected function _metadatasFile($id)
    {
        $path = $this->_path($id);
        $fileName = $this->_idToFileName(self::METADATA_PREFIX . $id);
        return $path . $fileName;
    }

    /**
     * Check if the given filename is a metadatas one
     *
     * @param  string $fileName File name
     * @return boolean True if it's a metadatas one
     */
    protected function _isMetadatasFile($fileName)
    {
        $id = $this->_fileNameToId($fileName);
        if (stristr($id, self::METADATA_PREFIX)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove a file
     *
     * If we can't remove the file (because of locks or any problem), we will touch
     * the file to invalidate it
     *
     * @param  string $file Complete file path
     * @return boolean True if ok
     */
    protected function _remove($file)
    {
        if (!is_file($file)) {
            return false;
        }
        if (!@unlink($file)) {
            return false;
        }
        return true;
    }

    /**
     * Clean some cache records (protected method used for recursive stuff)
     *
     * @param  string $dir  Directory to clean
     * @param  string $mode Clean mode
     * @return boolean True if no problem
     */
    protected function _clean($dir, $mode = Prosperent_Api_Cache::CLEANING_MODE_ALL)
    {
        if (!is_dir($dir)) {
            return false;
        }
        $result = true;
        $prefix = $this->_options['file_name_prefix'];
        $glob = @glob($dir . $prefix . '--*');
        if ($glob === false) {
            // On some systems it is impossible to distinguish between empty match and an error.
            return true;
        }
        foreach ($glob as $file)  {
            if (is_file($file)) {
                $fileName = basename($file);
                if ($this->_isMetadatasFile($fileName)) {
                    // in CLEANING_MODE_ALL, we drop anything, even remainings old metadatas files
                    if ($mode != Prosperent_Api_Cache::CLEANING_MODE_ALL) {
                        continue;
                    }
                }
                $id = $this->_fileNameToId($fileName);
                $metadatas = $this->_getMetadatas($id);
                if ($metadatas === FALSE) {
                    $metadatas = array('expire' => 1);
                }
                switch ($mode) {
                    case Prosperent_Api_Cache::CLEANING_MODE_ALL:
                        $res = $this->remove($id);
                        if (!$res) {
                            // in this case only, we accept a problem with the metadatas file drop
                            $res = $this->_remove($file);
                        }
                        $result = $result && $res;
                        break;
                    case Prosperent_Api_Cache::CLEANING_MODE_OLD:
                        if (time() > $metadatas['expire']) {
                            $result = $this->remove($id) && $result;
                        }
                        break;
                    default:
                        Prosperent_Api_Cache::throwException('Invalid mode for clean() method');
                        break;
                }
            }
            if ((is_dir($file)) and ($this->_options['hashed_directory_level'] > 0)) {
                // Recursive call
                $result = $this->_clean($file . DIRECTORY_SEPARATOR, $mode) && $result;
                if ($mode == Prosperent_Api_Cache::CLEANING_MODE_ALL) {
                    // we try to drop the structure too
                    @rmdir($file);
                }
            }
        }
        return $result;
    }

    /**
     * Get file
     *
     * @param string $dir
     * @param string $mode
     * @return
     */
    protected function _get($dir, $mode)
    {
        if (!is_dir($dir)) {
            return false;
        }
        $result = array();
        $prefix = $this->_options['file_name_prefix'];
        $glob = @glob($dir . $prefix . '--*');
        if ($glob === false) {
            // On some systems it is impossible to distinguish between empty match and an error.
            return array();
        }
        foreach ($glob as $file)  {
            if (is_file($file)) {
                $fileName = basename($file);
                $id = $this->_fileNameToId($fileName);
                $metadatas = $this->_getMetadatas($id);
                if ($metadatas === FALSE) {
                    continue;
                }
                if (time() > $metadatas['expire']) {
                    continue;
                }
                switch ($mode) {
                    case 'ids':
                        $result[] = $id;
                        break;
                    default:
                        Prosperent_Api_Cache::throwException('Invalid mode for _get() method');
                        break;
                }
            }
            if ((is_dir($file)) and ($this->_options['hashed_directory_level'] > 0)) {
                // Recursive call
                $recursiveRs =  $this->_get($file . DIRECTORY_SEPARATOR, $mode);
                if ($recursiveRs !== false) {
                    $result = array_unique(array_merge($result, $recursiveRs));
                }
            }
        }
        return array_unique($result);
    }

    /**
     * Compute & return the expire time
     *
     * @return int expire time (unix timestamp)
     */
    protected function _expireTime($lifetime)
    {
        if ($lifetime === null) {
            return 9999999999;
        }
        return time() + $lifetime;
    }

    /**
     * Make a control key with the string containing datas
     *
     * @param  string $data        Data
     * @param  string $controlType Type of control 'md5', 'crc32' or 'strlen'
     * @return string Control key
     */
    protected function _hash($data, $controlType)
    {
        switch ($controlType) {
        case 'md5':
            return md5($data);
        case 'crc32':
            return crc32($data);
        case 'strlen':
            return strlen($data);
        case 'adler32':
            return hash('adler32', $data);
        default:
            Prosperent_Api_Cache::throwException("Incorrect hash function : $controlType");
        }
    }

    /**
     * Transform a cache id into a file name and return it
     *
     * @param  string $id Cache id
     * @return string File name
     */
    protected function _idToFileName($id)
    {
        $prefix = $this->_options['file_name_prefix'];
        $result = $prefix . '---' . $id;
        return $result;
    }

    /**
     * Make and return a file name (with path)
     *
     * @param  string $id Cache id
     * @return string File name (with path)
     */
    protected function _file($id)
    {
        $path = $this->_path($id);
        $fileName = $this->_idToFileName($id);
        return $path . $fileName;
    }

    /**
     * Return the complete directory path of a filename (including hashedDirectoryStructure)
     *
     * @param  string $id Cache id
     * @param  boolean $parts if true, returns array of directory parts instead of single string
     * @return string Complete directory path
     */
    protected function _path($id, $parts = false)
    {
        $partsArray = array();
        $root = $this->_options['cache_dir'];
        $prefix = $this->_options['file_name_prefix'];
        if ($this->_options['hashed_directory_level']>0) {
            $hash = hash('adler32', $id);
            for ($i=0 ; $i < $this->_options['hashed_directory_level'] ; $i++) {
                $root = $root . $prefix . '--' . substr($hash, 0, $i + 1) . DIRECTORY_SEPARATOR;
                $partsArray[] = $root;
            }
        }
        if ($parts) {
            return $partsArray;
        } else {
            return $root;
        }
    }

    /**
     * Make the directory strucuture for the given id
     *
     * @param string $id cache id
     * @return boolean true
     */
    protected function _recursiveMkdirAndChmod($id)
    {
        if ($this->_options['hashed_directory_level'] <=0) {
            return true;
        }
        $partsArray = $this->_path($id, true);
        foreach ($partsArray as $part) {
            if (!is_dir($part)) {
                @mkdir($part, $this->_options['hashed_directory_umask']);
                @chmod($part, $this->_options['hashed_directory_umask']);
            }
        }
        return true;
    }

    /**
     * Test if the given cache id is available (and still valid as a cache record)
     *
     * @param  string  $id                     Cache id
     * @param  boolean $doNotTestCacheValidity If set to true, the cache validity won't be tested
     * @return boolean|mixed false (a cache is not available) or "last modified" timestamp (int) of the available cache record
     */
    protected function _test($id, $doNotTestCacheValidity)
    {
        $metadatas = $this->_getMetadatas($id);
        if (!$metadatas) {
            return false;
        }
        if ($doNotTestCacheValidity || (time() <= $metadatas['expire'])) {
            return $metadatas['mtime'];
        }
        return false;
    }

    /**
     * Return the file content of the given file
     *
     * @param  string $file File complete path
     * @return string File content (or false if problem)
     */
    protected function _fileGetContents($file)
    {
        $result = false;
        if (!is_file($file)) {
            return false;
        }
        $f = @fopen($file, 'rb');
        if ($f) {
            $result = stream_get_contents($f);
            @fclose($f);
        }
        return $result;
    }

    /**
     * Put the given string into the given file
     *
     * @param  string $file   File complete path
     * @param  string $string String to put in file
     * @return boolean true if no problem
     */
    protected function _filePutContents($file, $string)
    {
        $result = false;
        $f = @fopen($file, 'ab+');
        if ($f) {
            fseek($f, 0);
            ftruncate($f, 0);
            $tmp = @fwrite($f, $string);
            if (!($tmp === FALSE)) {
                $result = true;
            }
            @fclose($f);
        }
        @chmod($file, $this->_options['cache_file_umask']);
        return $result;
    }

    /**
     * Transform a file name into cache id and return it
     *
     * @param  string $fileName File name
     * @return string Cache id
     */
    protected function _fileNameToId($fileName)
    {
        $prefix = $this->_options['file_name_prefix'];
        return preg_replace('~^' . $prefix . '---(.*)$~', '$1', $fileName);
    }
}

/**
 * Memcache subclass for Prosperent_Api_Cache
 *
 * @copyright  Copyright (c) 2009-2011 Prosperent, Inc. (http://prosperent.com)
 * @license    See above (New BSD License)
 * @example    http://prosperent.com/affiliate/api View documentation on Prosperent.com
 * @package    Prosperent_Api
 * @subpackage Cache_Memcache
 */
class Prosperent_Api_Cache_Backend_Memcache extends Prosperent_Api_Cache_Backend implements Prosperent_Api_Cache_Backend_Interface
{
    /**
     * Default Values
     */
    const DEFAULT_HOST             = '127.0.0.1';
    const DEFAULT_PORT             = 11211;
    const DEFAULT_PERSISTENT       = true;
    const DEFAULT_WEIGHT           = 1;
    const DEFAULT_TIMEOUT          = 1;
    const DEFAULT_RETRY_INTERVAL   = 15;
    const DEFAULT_STATUS           = true;
    const DEFAULT_FAILURE_CALLBACK = null;

    /**
     * Available options
     *
     * =====> (array) servers :
     * an array of memcached server ; each memcached server is described by an associative array :
     * 'host' => (string) : the name of the memcached server
     * 'port' => (int) : the port of the memcached server
     * 'persistent' => (bool) : use or not persistent connections to this memcached server
     * 'weight' => (int) : number of buckets to create for this server which in turn control its
     *                     probability of it being selected. The probability is relative to the total
     *                     weight of all servers.
     * 'timeout' => (int) : value in seconds which will be used for connecting to the daemon. Think twice
     *                      before changing the default value of 1 second - you can lose all the
     *                      advantages of caching if your connection is too slow.
     * 'retry_interval' => (int) : controls how often a failed server will be retried, the default value
     *                             is 15 seconds. Setting this parameter to -1 disables automatic retry.
     * 'status' => (bool) : controls if the server should be flagged as online.
     * 'failure_callback' => (callback) : Allows the user to specify a callback function to run upon
     *                                    encountering an error. The callback is run before failover
     *                                    is attempted. The function takes two parameters, the hostname
     *                                    and port of the failed server.
     *
     * =====> (boolean) compression :
     * true if you want to use on-the-fly compression
     *
     * =====> (boolean) compatibility :
     * true if you use old memcache server or extension
     *
     * @var array available options
     */
    protected $_options = array(
        'servers' => array(array(
            'host' => self::DEFAULT_HOST,
            'port' => self::DEFAULT_PORT,
            'persistent' => self::DEFAULT_PERSISTENT,
            'weight'  => self::DEFAULT_WEIGHT,
            'timeout' => self::DEFAULT_TIMEOUT,
            'retry_interval' => self::DEFAULT_RETRY_INTERVAL,
            'status' => self::DEFAULT_STATUS,
            'failure_callback' => self::DEFAULT_FAILURE_CALLBACK
        )),
        'compression' => false,
        'compatibility' => false,
    );

    /**
     * Memcache object
     *
     * @var mixed memcache object
     */
    protected $_memcache = null;

    /**
     * Constructor
     *
     * @param array $options associative array of options
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (!extension_loaded('memcache')) {
            Prosperent_Api_Cache::throwException('The memcache extension must be loaded for using this backend !');
        }
        parent::__construct($options);
        if (isset($this->_options['servers'])) {
            $value= $this->_options['servers'];
            if (isset($value['host'])) {
                // in this case, $value seems to be a simple associative array (one server only)
                $value = array(0 => $value); // let's transform it into a classical array of associative arrays
            }
            $this->setOption('servers', $value);
        }
        $this->_memcache = new Memcache;
        foreach ($this->_options['servers'] as $server) {
            if (!array_key_exists('port', $server)) {
                $server['port'] = self::DEFAULT_PORT;
            }
            if (!array_key_exists('persistent', $server)) {
                $server['persistent'] = self::DEFAULT_PERSISTENT;
            }
            if (!array_key_exists('weight', $server)) {
                $server['weight'] = self::DEFAULT_WEIGHT;
            }
            if (!array_key_exists('timeout', $server)) {
                $server['timeout'] = self::DEFAULT_TIMEOUT;
            }
            if (!array_key_exists('retry_interval', $server)) {
                $server['retry_interval'] = self::DEFAULT_RETRY_INTERVAL;
            }
            if (!array_key_exists('status', $server)) {
                $server['status'] = self::DEFAULT_STATUS;
            }
            if (!array_key_exists('failure_callback', $server)) {
                $server['failure_callback'] = self::DEFAULT_FAILURE_CALLBACK;
            }
            if ($this->_options['compatibility']) {
                $this->_memcache->addServer($server['host'], $server['port'], $server['persistent'],
                                        $server['weight'], $server['timeout'],
                                        $server['retry_interval']);
            } else {
                $this->_memcache->addServer($server['host'], $server['port'], $server['persistent'],
                                        $server['weight'], $server['timeout'],
                                        $server['retry_interval'],
                                        $server['status'], $server['failure_callback']);
            }
        }
    }

    /**
     * Test if a cache is available for the given id and (if yes) return it (false else)
     *
     * @param  string  $id                     Cache id
     * @param  boolean $doNotTestCacheValidity If set to true, the cache validity won't be tested
     * @return string|false cached datas
     */
    public function load($id, $doNotTestCacheValidity = false)
    {
        $tmp = $this->_memcache->get($id);
        if (is_array($tmp) && isset($tmp[0])) {
            return $tmp[0];
        }
        return false;
    }

    /**
     * Test if a cache is available or not (for the given id)
     *
     * @param  string $id Cache id
     * @return mixed|false (a cache is not available) or "last modified" timestamp (int) of the available cache record
     */
    public function test($id)
    {
        $tmp = $this->_memcache->get($id);
        if (is_array($tmp)) {
            return $tmp[1];
        }
        return false;
    }

    /**
     * Save some string datas into a cache record
     *
     * Note : $data is always "string" (serialization is done by the
     * core not by the backend)
     *
     * @param  string $data             Datas to cache
     * @param  string $id               Cache id
     * @param  int    $specificLifetime If != false, set a specific lifetime for this cache record (null => infinite lifetime)
     * @return boolean True if no problem
     */
    public function save($data, $id, $specificLifetime = false)
    {
        $lifetime = $this->getLifetime($specificLifetime);
        if ($this->_options['compression']) {
            $flag = MEMCACHE_COMPRESSED;
        } else {
            $flag = 0;
        }

        $result = @$this->_memcache->set($id, array($data, time(), $lifetime), $flag, $lifetime);

        return $result;
    }

    /**
     * Remove a cache record
     *
     * @param  string $id Cache id
     * @return boolean True if no problem
     */
    public function remove($id)
    {
        return $this->_memcache->delete($id, 0);
    }

    /**
     * Clean some cache records
     *
     * @param  string $mode Clean mode
     * @return boolean True if no problem
     */
    public function clean($mode = Prosperent_Api_Cache::CLEANING_MODE_ALL)
    {
        switch ($mode) {
            case Prosperent_Api_Cache::CLEANING_MODE_ALL:
            case Prosperent_Api_Cache::CLEANING_MODE_OLD:
                return $this->_memcache->flush();
                break;
               default:
                Prosperent_Api_Cache::throwException('Invalid mode for clean() method');
                   break;
        }
    }

    /**
     * Set the frontend directives
     *
     * @param  array $directives Assoc of directives
     * @return void
     */
    public function setDirectives($directives)
    {
        parent::setDirectives($directives);
        $lifetime = $this->getLifetime(false);
        if ($lifetime === null) {
            parent::setDirectives(array('lifetime' => 0));
        }
    }

    /**
     * Return an array
     *
     * @return array
     */
    public function getIds()
    {
        return array();
    }

    /**
     * Return the filling percentage of the backend storage
     *
     * @return int integer between 0 and 100
     */
    public function getFillingPercentage()
    {
        $mems = $this->_memcache->getExtendedStats();

        $memSize = null;
        $memUsed = null;
        foreach ($mems as $key => $mem) {
            if ($mem === false) {
                continue;
            }

            $eachSize = $mem['limit_maxbytes'];
            $eachUsed = $mem['bytes'];
            if ($eachUsed > $eachSize) {
                $eachUsed = $eachSize;
            }

            $memSize += $eachSize;
            $memUsed += $eachUsed;
        }

        if ($memSize === null || $memUsed === null) {
            Prosperent_Api_Cache::throwException('Cannot get filling percentage');
        }

        return ((int) (100. * ($memUsed / $memSize)));
    }

    /**
     * Return an array of metadatas for the given cache id
     *
     * @param string $id cache id
     * @return array array of metadatas (false if the cache id is not found)
     */
    public function getMetadatas($id)
    {
        $tmp = $this->_memcache->get($id);
        if (is_array($tmp)) {
            $data = $tmp[0];
            $mtime = $tmp[1];
            if (!isset($tmp[2])) {
                // because this record is only with 1.7 release
                // if old cache records are still there...
                return false;
            }
            $lifetime = $tmp[2];
            return array(
                'expire' => $mtime + $lifetime,
                'mtime' => $mtime
            );
        }
        return false;
    }

    /**
     * Give (if possible) an extra lifetime to the given cache id
     *
     * @param string $id cache id
     * @param int $extraLifetime
     * @return boolean true if ok
     */
    public function touch($id, $extraLifetime)
    {
        if ($this->_options['compression']) {
            $flag = MEMCACHE_COMPRESSED;
        } else {
            $flag = 0;
        }
        $tmp = $this->_memcache->get($id);
        if (is_array($tmp)) {
            $data = $tmp[0];
            $mtime = $tmp[1];
            if (!isset($tmp[2])) {
                return false;
            }
            $lifetime = $tmp[2];
            $newLifetime = $lifetime - (time() - $mtime) + $extraLifetime;
            if ($newLifetime <=0) {
                return false;
            }
            if (!($result = $this->_memcache->replace($id, array($data, time(), $newLifetime), $flag, $newLifetime))) {
                $result = $this->_memcache->set($id, array($data, time(), $newLifetime), $flag, $newLifetime);
            }
            return $result;
        }
        return false;
    }

    /**
     * Return an associative array of capabilities (booleans) of the backend
     *
     * @return array associative of with capabilities
     */
    public function getCapabilities()
    {
        return array(
            'automatic_cleaning' => false,
            'expired_read' => false,
            'priority' => false,
            'infinite_lifetime' => false,
            'get_list' => false
        );
    }
}
