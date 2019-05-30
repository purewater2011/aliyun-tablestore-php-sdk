<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: table_store_search.proto

namespace Aliyun\OTS\ProtoBuffer\Protocol;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>aliyun.OTS.ProtoBuffer.Protocol.BoolQuery</code>
 */
class BoolQuery extends \Aliyun\OTS\ProtoBuffer\Protocol\Message
{
    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query must_queries = 1;</code>
     */
    private $must_queries;
    private $has_must_queries = false;
    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query must_not_queries = 2;</code>
     */
    private $must_not_queries;
    private $has_must_not_queries = false;
    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query filter_queries = 3;</code>
     */
    private $filter_queries;
    private $has_filter_queries = false;
    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query should_queries = 4;</code>
     */
    private $should_queries;
    private $has_should_queries = false;
    /**
     * Generated from protobuf field <code>optional int32 minimum_should_match = 5;</code>
     */
    private $minimum_should_match = 0;
    private $has_minimum_should_match = false;

    public function __construct() {
        \GPBMetadata\TableStoreSearch::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query must_queries = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getMustQueries()
    {
        return $this->must_queries;
    }

    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query must_queries = 1;</code>
     * @param \Aliyun\OTS\ProtoBuffer\Protocol\Query[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setMustQueries($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Aliyun\OTS\ProtoBuffer\Protocol\Query::class);
        $this->must_queries = $arr;
        $this->has_must_queries = true;

        return $this;
    }

    public function hasMustQueries()
    {
        return $this->has_must_queries;
    }

    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query must_not_queries = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getMustNotQueries()
    {
        return $this->must_not_queries;
    }

    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query must_not_queries = 2;</code>
     * @param \Aliyun\OTS\ProtoBuffer\Protocol\Query[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setMustNotQueries($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Aliyun\OTS\ProtoBuffer\Protocol\Query::class);
        $this->must_not_queries = $arr;
        $this->has_must_not_queries = true;

        return $this;
    }

    public function hasMustNotQueries()
    {
        return $this->has_must_not_queries;
    }

    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query filter_queries = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getFilterQueries()
    {
        return $this->filter_queries;
    }

    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query filter_queries = 3;</code>
     * @param \Aliyun\OTS\ProtoBuffer\Protocol\Query[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setFilterQueries($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Aliyun\OTS\ProtoBuffer\Protocol\Query::class);
        $this->filter_queries = $arr;
        $this->has_filter_queries = true;

        return $this;
    }

    public function hasFilterQueries()
    {
        return $this->has_filter_queries;
    }

    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query should_queries = 4;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getShouldQueries()
    {
        return $this->should_queries;
    }

    /**
     * Generated from protobuf field <code>repeated .aliyun.OTS.ProtoBuffer.Protocol.Query should_queries = 4;</code>
     * @param \Aliyun\OTS\ProtoBuffer\Protocol\Query[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setShouldQueries($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Aliyun\OTS\ProtoBuffer\Protocol\Query::class);
        $this->should_queries = $arr;
        $this->has_should_queries = true;

        return $this;
    }

    public function hasShouldQueries()
    {
        return $this->has_should_queries;
    }

    /**
     * Generated from protobuf field <code>optional int32 minimum_should_match = 5;</code>
     * @return int
     */
    public function getMinimumShouldMatch()
    {
        return $this->minimum_should_match;
    }

    /**
     * Generated from protobuf field <code>optional int32 minimum_should_match = 5;</code>
     * @param int $var
     * @return $this
     */
    public function setMinimumShouldMatch($var)
    {
        GPBUtil::checkInt32($var);
        $this->minimum_should_match = $var;
        $this->has_minimum_should_match = true;

        return $this;
    }

    public function hasMinimumShouldMatch()
    {
        return $this->has_minimum_should_match;
    }

}

