<?php
/**
 * Created by PhpStorm.
 * User: ratno
 * Date: 28/11/18
 * Time: 17.06
 */

namespace Ratno\Resources;


class ResourceConstant
{
    const MORPH_MANY = "morphMany";
    const MORPH_TO = "morphTo";
    const MORPH_TO_MANY = "morphToMany";
    const MORPHED_BY_MANY = "morphedByMany";

    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    const DELETED_AT = "deleted_at";
    const RESTORED_AT = "restored_at";

    const CREATED_BY_ID = "created_by_id";
    const UPDATED_BY_ID = "updated_by_id";
    const DELETED_BY_ID = "deleted_by_id";
    const RESTORED_BY_ID = "restored_by_id";
}