<?php
namespace Pluf\Orm;

/**
 *
 * Flush mode setting.
 *
 * When queries are executed within a transaction, if FlushModeType::AUTO is set on the Query or
 * TypedQuery object, or if the flush mode setting for the persistence context is AUTO (the default)
 * and a flush mode setting has not been specified for the Query or TypedQuery object, the
 * persistence provider is responsible for ensuring that all updates to the state of all
 * entities in the persistence context which could potentially affect the result of the
 * query are visible to the processing of the query. The persistence provider implementation
 * may achieve this by flushing those entities to the database or by some other means.
 *
 * If FlushModeType::COMMIT is set, the effect of updates made to entities in the persistence
 * context upon queries is unspecified.
 *
 * If there is no transaction active, the persistence provider must not flush to the database.
 *
 * @author maso
 *        
 */
class FlushModeType
{

    /**
     * (Default) Flushing to occur at query execution.
     *
     * @var string
     */
    public const AUTO = "auto";

    /**
     * Flushing to occur at transaction commit.
     * The provider may flush at other times, but is not required to.
     *
     * @var string
     */
    public const COMMIT = "commit";
}

