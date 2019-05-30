<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: table_store_search.proto

namespace Aliyun\OTS\ProtoBuffer\Protocol;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>aliyun.OTS.ProtoBuffer.Protocol.PrefixQuery</code>
 */
class PrefixQuery extends \Aliyun\OTS\ProtoBuffer\Protocol\Message
{
    /**
     * Generated from protobuf field <code>optional string field_name = 1;</code>
     */
    private $field_name = '';
    private $has_field_name = false;
    /**
     * Generated from protobuf field <code>optional string prefix = 2;</code>
     */
    private $prefix = '';
    private $has_prefix = false;

    public function __construct() {
        \GPBMetadata\TableStoreSearch::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>optional string field_name = 1;</code>
     * @return string
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     * Generated from protobuf field <code>optional string field_name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setFieldName($var)
    {
        GPBUtil::checkString($var, True);
        $this->field_name = $var;
        $this->has_field_name = true;

        return $this;
    }

    public function hasFieldName()
    {
        return $this->has_field_name;
    }

    /**
     * Generated from protobuf field <code>optional string prefix = 2;</code>
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Generated from protobuf field <code>optional string prefix = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setPrefix($var)
    {
        GPBUtil::checkString($var, True);
        $this->prefix = $var;
        $this->has_prefix = true;

        return $this;
    }

    public function hasPrefix()
    {
        return $this->has_prefix;
    }

}

