<?php


namespace Aliyun\OTS\Consts;


class QueryTypeConst
{
    const MATCH_QUERY = 'MATCH_QUERY';
    const MATCH_PHRASE_QUERY = 'MATCH_PHRASE_QUERY';
    const TERM_QUERY = 'TERM_QUERY';
    const RANGE_QUERY = 'RANGE_QUERY';
    const PREFIX_QUERY = 'PREFIX_QUERY';
    const BOOL_QUERY = 'BOOL_QUERY';
    const CONST_SCORE_QUERY = 'CONST_SCORE_QUERY';
    const FUNCTION_SCORE_QUERY = 'FUNCTION_SCORE_QUERY';
    const NESTED_QUERY = 'NESTED_QUERY';
    const WILDCARD_QUERY = 'WILDCARD_QUERY';
    const MATCH_ALL_QUERY = 'MATCH_ALL_QUERY';
    const GEO_BOUNDING_BOX_QUERY = 'GEO_BOUNDING_BOX_QUERY';
    const GEO_DISTANCE_QUERY = 'GEO_DISTANCE_QUERY';
    const GEO_POLYGON_QUERY = 'GEO_POLYGON_QUERY';
    const TERMS_QUERY = 'TERMS_QUERY';
    const EXISTS_QUERY = 'EXISTS_QUERY';
}