<?php
namespace Pluf;

/**
 * Utilities to work with model
 *
 * @author maso
 *        
 */
class ModelUtils
{

    public const MODEL_CACHE_KEY = '_PX_models_init_cache';

    public const MODEL_KEY = '_PX_models';

    public const MODEL_VIEW_CACHE_KEY = '_PX_models_views';

    public static function getModelCacheKey(Model $model)
    {
        $objr = new \ReflectionObject($model);
        $key = $objr->getName();
        if (strpos($key, '\\')) {
            $key = '\\' . $key;
        }
        return $key;
    }

    public static function loadFromCache(Model $model): bool
    {
        $key = self::getModelCacheKey($model);
        if (isset($GLOBALS[self::MODEL_CACHE_KEY][$key])) {
            $init_cache = $GLOBALS[self::MODEL_CACHE_KEY][$key];

            $model->_cache = $init_cache['cache'];
            $model->_m = $init_cache['m'];
            $model->_a = $init_cache['a'];
            $model->_fk = $init_cache['fk'];
            $model->_data = $init_cache['data'];

            return true;
        }
        return false;
    }

    public static function putModelToCache(Model $model): void
    {
        $key = self::getModelCacheKey($model);
        if (isset($GLOBALS[self::MODEL_CACHE_KEY][$key])) {
            return;
        }
        $GLOBALS[self::MODEL_CACHE_KEY][$key] = array(
            'cache' => $model->_cache,
            'm' => $model->_m,
            'a' => $model->_a,
            'fk' => $model->_fk,
            'data' => $model->_data
        );
    }

    public static function getRelatedModels(Model $model, string $type)
    {
        $key = self::getModelCacheKey($model);
        $relations = [];
        if (isset($GLOBALS['_PX_models_related'][$type][$key])) {
            $relations = $GLOBALS['_PX_models_related'][$type][$key];
        }
        return $relations;
    }
}

